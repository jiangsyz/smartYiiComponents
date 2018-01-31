<?php
//短信发送类
namespace common\components\smartSms;
use Yii;
use yii\base\Component;
use yii\base\SmartException;
//====================================================
class smartSms extends Component{
	//配置
	public $config=false;
	//具体发送渠道
	private $sender=false;
	//====================================================
	//初始化
	public function init(){
		parent::init();
		//校验配置
		if(!isset($this->config['type'])) throw new SmartException("miss config[type]");
		if(!isset($this->config['conf'])) throw new SmartException("miss config[conf]");
		//根据配置信息,获取不同的发送渠道
		$this->sender=smsSender::getSender($this->config['type'],$this->config['conf']);
	}
	//====================================================
	//发送,成功返回true,失败会抛出异常
	public function send($phone,$msg){
		if(!$this->sender) throw new SmartException("miss sender"); else return $this->sender->send($phone,$msg);
	}
}
?>