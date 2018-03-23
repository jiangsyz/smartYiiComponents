<?php
//微信支付
namespace common\components\smartWechatPay;
use Yii;
use yii\base\Component;
use yii\base\SmartException;
class smartWechatPay extends Component{
	public $conf=NULL;
	//========================================
	//获取配置
	private function getConf($appType){
		if(!isset($this->conf[$appType])) 
			throw new SmartException("error appType"); 
		else 
			return $this->conf[$appType];
	}
	//========================================
	//申请支付
	public function applyPay($appType,$command){
		//获取支付管理器
		$payManagement=NULL;
		if($appType=='android') $payManagement=new smartAppWechatPay($this->getConf($appType));
		if($appType=='ios') $payManagement=new smartAppWechatPay($this->getConf($appType));
		if(!$payManagement) throw new SmartException("error appType");
		//申请支付
		return $payManagement->applyPay($command);
	}
}