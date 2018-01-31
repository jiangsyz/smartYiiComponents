<?php
//短信发送渠道
namespace common\components\smartSms;
use Yii;
use yii\base\Object;
use yii\base\Exception;
//====================================================
abstract class smsSender extends Object{
	//获取具体的发送渠道
	public static function getSender($senderName,$conf=array()){
		$sender="\\common\\components\\smartSms\\".$senderName;
		return new $sender($conf);
	}
	//====================================================
	//发送短信(每个发送渠道自己实现)
	abstract public function send($phone,$msg);
}
?>