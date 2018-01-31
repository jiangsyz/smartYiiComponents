<?php
//螺丝帽渠道
namespace common\components\smartSms;
use yii;
use yii\base\SmartException;
class luosimaoSender extends smsSender{
	public $key=false;
	public $signature=false;
	public $title=false;
	//========================================
	const URI="http://sms-api.luosimao.com/v1/send.json";
	//========================================
	//发送
	public function send($phone,$msg){
		//校验
		if($this->key===false) throw new SmartException("miss key");
		if($this->signature===false) throw new SmartException("miss signature");
		if($this->title===false) throw new SmartException("miss title");
		//组织内容
		$postData=array();
		$postData['mobile']=$phone;
		$postData['message']=$this->title.$msg."【".$this->signature."】";
		//调用api
		$reponse=Yii::$app->smartApi->post(self::URI,$postData,array(CURLOPT_USERPWD=>'api:key-'.$this->key));
		if(!$reponse['state']) throw new SmartException($reponse['reponse']);
		//处理数据
		$reponse=json_decode($reponse['reponse'],true);
		//处理返回值
		if(!isset($reponse['error'])) throw new SmartException("error reponse");
		switch($reponse['error']){
			case 0:return true;
			case -10:throw new SmartException("验证信息失败");
			case -20:throw new SmartException("短信余额不足");
			case -30:throw new SmartException("短信内容为空");
			case -31:throw new SmartException("短信内容存在敏感词");
			case -32:throw new SmartException("短信内容缺少签名信息");
			case -40:throw new SmartException("错误的手机号");
			case -41:throw new SmartException("号码因频繁发送");
			case -42:throw new SmartException("验证码类短信发送频率过快");
			case -50:throw new SmartException("请求发送IP不在白名单内");
			default:throw new SmartException("未知错误".$reponse['error']);
		}
	}
}
?>