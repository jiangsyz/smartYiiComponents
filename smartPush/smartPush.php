<?php
//推送
namespace common\components\smartPush;
require 'jpush/autoload.php';
use Yii;
use yii\base\Component;
use yii\base\SmartException;
use JPush\Client as JPush;
class smartPush extends Component{
	//配置	
	public $appKey;
	public $masterSecret;
	//客户端
	public $client=false;
	//========================================
	//初始化
	public function init(){
		parent::init();
		$this->client=new JPush($this->appKey,$this->masterSecret);
	}
	//========================================
	//点对点推送
	public function pushByRegistrationId($appType,$registrationId,$msg){
		if(!$registrationId) throw new SmartException("miss registrationId");
		if(!$msg) throw new SmartException("miss msg");
		if(!isset($msg['title'])) throw new SmartException("msg miss title");
		if(!isset($msg['msg_content'])) throw new SmartException("msg miss msg_content");
		if(!isset($msg['content_type'])) throw new SmartException("msg miss content_type");
		if(!isset($msg['extras'])) throw new SmartException("msg miss extras");
		//推到安卓
		if($appType=='Android') $this->pushByRegistrationIdToAndroid($registrationId,$msg);
		//推到苹果
		elseif($appType=='Ios') $this->pushByRegistrationIdToIos($registrationId,$msg);
		//异常渠道
		else throw new SmartException("error appType");
	}
	//========================================
	//点对点推送至安卓
	public function pushByRegistrationIdToAndroid($registrationId,$msg){
		$this->client
				->push()
				->setPlatform(array('android'))
				->addRegistrationId($registrationId)
				->androidNotification($msg['title'],array('extras'=>$msg['extras']))
				->message($msg['title'],$msg)
				->send();
	}
	//========================================
	//点对点推送至苹果
	public function pushByRegistrationIdToIos($registrationId,$msg){
		$this->client
				->push()
				->setPlatform(array('ios'))
				->addRegistrationId($registrationId)
				->iosNotification($msg['title'],array('extras'=>$msg['extras']))
				->message($msg['title'],$msg)
				->send();
	}
}
