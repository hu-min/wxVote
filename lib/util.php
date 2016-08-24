<?php
class util {
	public static function getSafeStr($str) {
		//转换字符串编码
		$s1 = iconv('utf-8', 'gbk', $str);
		$s0 = iconv('gbk', 'utf-8', $s1);
		if ($s0 == $str) {
			return $s1;
		} else {
			return $str;
		}
	}

	public static function getcode($str) {
		//返回字符串的编码方式
		$s1 = iconv('utf-8', 'gbk', $str);
		$s0 = iconv('gbk', 'utf-8', $s1);
		if ($s0 == $str) {
			return 'utf-8';
		} else {
			return 'gbk';
		}
	}

	public static function encodeJson($array) {

		function arrayRecursive($array, $function, $apply_to_keys_also = false) {
			static $recursive_counter = 0;
			if (++$recursive_counter > 1000) {
				die('possible deep recursion attack');
			}
			foreach ($array as $key => $value) {
				if (is_array($value)) {
					arrayRecursive($array[$key], $function, $apply_to_keys_also);
				} else {
					$array[$key] = $function($value);
				}
				if ($apply_to_keys_also && is_string($key)) {
					$new_key = $function($key);
					if ($new_key != $key) {
						$array[$new_key] = $array[$key];
						unset($array[$key]);
					}
				}
			}
			$recursive_counter--;

		}

		arrayRecursive($array, 'urlencode', true);
		$json = json_encode($array, JSON_FORCE_OBJECT);
		
		return urldecode($json);

	}

	public static function getHost(){
			//内网穿透可能会获取到本地的host，用下面这句替代
	   	$host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
	   
	    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	   	$url	=	$protocol.$host;
		return $url;
	},
	/*
	 * 发送curl请求
	 */		
	public static function httpReq($url, $option='', $contentStr='') {
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
	    // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
	    // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
	 //   curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
	 //  curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
	 	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//本地测试绕过SSL证书验证，生产环境需要验证
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);//本地测试绕过SSL证书验证，生产环境需要验证
	    if(strtoupper($option) == "POST" )
		{
			curl_setopt($curl, CURLOPT_POST, 1);	//设置为POST方式
			curl_setopt($curl, CURLOPT_POSTFIELDS, $contentStr); //POST上传的数据
			curl_setopt($curl, CURLOPT_URL, $url);
			$res = curl_exec($curl);
			
			if(curl_errno($curl))
			{
				echo 'Errno'.curl_error($curl);
			}
			
			curl_close($curl);
			
			return $res;
		}
		
	    curl_setopt($curl, CURLOPT_URL, $url);
	    $res = curl_exec($curl);
		
		if(curl_errno($curl))
			{
				echo 'Errno'.curl_error($curl);
			}
		
	    curl_close($curl);
	
	    return $res;
  }
	
}
?>