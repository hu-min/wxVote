<?php 
require("../lib/util.php");
require("../lib/get_access_token.php");

$token = myToken::getToken();
$expires = myToken::getExpires_in();
echo "token:".$token."\n\rexpires:".$expires;

?>