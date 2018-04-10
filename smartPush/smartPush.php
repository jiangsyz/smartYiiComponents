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
	public function pushByRegistrationId($registrationId,$msg){
		if(!$registrationId) throw new SmartException("miss registrationId");
		if(!$msg) throw new SmartException("miss msg");
		if(!isset($msg['title'])) throw new SmartException("msg miss title");
		if(!isset($msg['msg_content'])) throw new SmartException("msg miss msg_content");
		if(!isset($msg['content_type'])) throw new SmartException("msg miss content_type");
		if(!isset($msg['extras'])) throw new SmartException("msg miss extras");
		$this->client
				->push()
				->setPlatform(array('ios','android'))
				->addRegistrationId($registrationId)
				->androidNotification('notice',array('extras'=>$msg['extras']))
				->message('notice',$msg)
				->send();
	}
}