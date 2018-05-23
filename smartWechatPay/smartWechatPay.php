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
	//退款配置
	public $refundConf=NULL;
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
	//========================================
	//退款
	public function refund($command){
		$smartWechatRefund=new smartWechatRefund($this->refundConf);
		$smartWechatRefund->refund($command,$this);
	}
	//========================================
	//获取退款通知结果
	public function getRefundResult($mchId,$reqInfo){
		//对商户key做md5,得到32位小写key
		$key=md5($this->getMchSecret($mchId));
		//返回结果
		return $this->refundDecrypt($reqInfo,$key);
	}
	//========================================
	//解密退款通知
	public function refundDecrypt($str,$key){  
        $str=base64_decode($str);  
        $str=mcrypt_decrypt(MCRYPT_RIJNDAEL_128,$key,$str,MCRYPT_MODE_ECB);  
        $block=mcrypt_get_block_size('rijndael_128','ecb');  
        $pad=ord($str[($len=strlen($str))-1]);
        $len=strlen($str); 
        $pad=ord($str[$len-1]);  
        return substr($str,0,strlen($str)-$pad);  
	}
}