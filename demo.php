<?php
include "wechat.class.php";
$options = array(
		'token'=>'zhanglei', //填写你设定的key
        'encodingaeskey'=>'encodingaeskey', //填写加密用的EncodingAESKey，如接口为明文模式可忽略
        'appid'=>'wx705d54c7844eb5bf', //填写高级调用功能的app id, 请在微信开发模式后台查询
		'appsecret'=>'6e3009ecff1d25610bac30b88fcc05d8' //填写高级调用功能的密钥
	);

$weObj = new Wechat($options);

$weObj->valid();//明文或兼容模式可以在接口验证通过后注释此句，但加密模式一定不能注释，否则会验证失败

$type = $weObj->getRev()->getRevType();
switch($type) {
	case Wechat::MSGTYPE_TEXT:
			$weObj->text("hello, I'm wechat")->reply();
			exit;
			break;
	case Wechat::MSGTYPE_EVENT:
			break;
	case Wechat::MSGTYPE_IMAGE:
			break;
	default:
			$weObj->text("help info")->reply();
}