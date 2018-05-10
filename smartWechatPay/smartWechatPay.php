<?php
//微信支付
namespace common\components\smartWechatPay;
use Yii;
use yii\base\Component;
use yii\base\SmartException;
class smartWechatPay extends Component{
	//支付配置
	public $payConf=NULL;
	//商户配置
	public $mchConf=NULL;
	//========================================
	//根据商户id获取商户密钥
	private function getMchSecret($mchId){
		if(!isset($this->mchConf[$mchId])) 
			throw new SmartException("miss mchConf");
		else 
			return $this->mchConf[$mchId];
	}
	//========================================
	//获取支付配置
	private function getPayConf($appType){
		if(!isset($this->payConf[$appType])) 
			throw new SmartException("error appType"); 
		else 
			return $this->payConf[$appType];
	}
	//========================================
	//签名
	public function sign($command,$mchId){
		//获取商户密钥
		$mchSecret=$this->getMchSecret($mchId);
		//生成签名第一步:对参数按照key=value的格式，并按照参数名ASCII字典序排序如下
		ksort($command);
		$string="";
		foreach($command as $k=>$v) $string.=$string?"&{$k}={$v}":"{$k}={$v}";
		//生成签名第二步:拼接API密钥
		$stringSignTemp="{$string}&key={$mchSecret}";
		//生成签名第三步:md5
		return strtoupper(md5($stringSignTemp));
	}
	//========================================
	//申请支付
	public function applyPay($appType,$command){
		//获取支付管理器
		$payManagement=NULL;
		if($appType=='android') $payManagement=new smartAppWechatPay($this->getPayConf($appType));//安卓
		if($appType=='ios') $payManagement=new smartAppWechatPay($this->getPayConf($appType));//苹果
		if($appType=='miniApp') $payManagement=new smartAppWechatPay($this->getPayConf($appType));//小程序
		if(!$payManagement) throw new SmartException("error appType");
		//申请支付
		return $payManagement->applyPay($command,$this);
	}
}