<?php
if(!defined("PATH")){define("PATH", dirname(dirname(__FILE__))); }
class JSSDK {
  private $appId = 'wx2b823442f4ed975c';
  private $appSecret = '63dfb9ce066d7ecce0aef4be6280bf1d';
  private $path = PATH;

  public function init($appId, $appSecret, $path) {
    $this->appId = $appId;
    $this->appSecret = $appSecret;
	$this->path = $path;
  }

  public function getSignPackage() {
    $jsapiTicket = $this->getJsApiTicket();

    // 注意 URL 一定要动态获取，不能 hardcode.
   	//内网穿透可能会获取到本地的host，用下面这句替代
   	$host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
   
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
   	$url	=	$protocol.$host.$_SERVER["REQUEST_URI"];
   // $url = $protocol.$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI];

    $timestamp = time();
    $nonceStr = $this->createNonceStr();

    // 这里参数的顺序要按照 key 值 ASCII 码升序排序
    $string = "jsapi_ticket=".$jsapiTicket."&noncestr=".$nonceStr."&timestamp=".$timestamp."&url=".$url;

    $signature = sha1($string);
		
    $signPackage = array(
      "appId"     => $this->appId,
      "nonceStr"  => $nonceStr,
      "timestamp" => $timestamp,
      "url"       => $url,
      "signature" => $signature,
      "rawString" => $string
    );
    return $signPackage; 
  }
  
	public function getAccessToken() {
    // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
    $data = json_decode( file_get_contents($this->path."/conf/access_token.json") );
    if ($data->expire_time < time()) {
      // 如果是企业号用以下URL获取access_token
      // $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
      $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
      $res = json_decode($this->httpGet($url));
      $access_token = $res->access_token;
      if ($access_token) {
        $data->expire_time = time() + 7000;
        $data->access_token = $access_token;
        file_put_contents($this->path."/conf/access_token.json", json_encode($data));
      }
    } else {
      $access_token = $data->access_token;
    }
    return $access_token;
  }
	//获取用户基本资料
	public function getUser( $OpenId ){
		$accessToken = $this->getAccessToken();
		$url ="https://api.weixin.qq.com/cgi-bin/user/info?access_token=$accessToken&openid=$OpenId&lang=zh_CN";
		$res = json_decode($this->httpGet($url));
		
		if(!empty($res->subscribe)){
			return $res;
		}else{
			return false;
		}
	}
  private function createNonceStr($length = 16) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
      $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
  }

  private function getJsApiTicket() {
    // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
    $data = json_decode(file_get_contents($this->path.'/conf/jsapi_ticket.json'));
    if ($data->expire_time < time()) {
      $accessToken = $this->getAccessToken();
      // 如果是企业号用以下 URL 获取 ticket
      // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
      $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
      $res = json_decode($this->httpGet($url));
      $ticket = $res->ticket;
      if ($ticket) {
        $data->expire_time = time() + 7000;
        $data->jsapi_ticket = $ticket;
        file_put_contents($this->path."/conf/jsapi_ticket.json", json_encode($data));
      }
    } else {
      $ticket = $data->jsapi_ticket;
    }

    return $ticket;
  }


  private function httpGet($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
    // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
 //   curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
 //  curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
 		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//本地测试绕过SSL证书验证，生产环境需要验证
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);//本地测试绕过SSL证书验证，生产环境需要验证
    curl_setopt($curl, CURLOPT_URL, $url);

    $res = curl_exec($curl);
    curl_close($curl);

    return $res;
  }

  private function httpPost($url, $option, $contentStr) {
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

  private function get_php_file($filename) {
    return trim(substr(file_get_contents($filename), 15));
  }
  private function set_php_file($filename, $content) {
    $fp = fopen($filename, "w");
    fwrite($fp, "<?php exit();?>" . $content);
    fclose($fp);
  }
}

