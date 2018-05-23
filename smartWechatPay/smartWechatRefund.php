<?php
//微信退款
namespace common\components\smartWechatPay;
use Yii;
use yii\base\Component;
use yii\base\SmartException;
class smartWechatRefund extends Component{
	//回调地址
	public $notifyUrl=NULL;
	//随机字符串
	public $nonceStr=false;
	//========================================
	//初始化
	public function init(){
		//随机字符串
		$this->nonceStr=md5(rand(1,99999));
	}
	//========================================
	public function refund($command,smartWechatPay $smartWechatPay){
		//校验指令集的必须项
		if(!isset($command['appid'])) throw new Exception("command miss appid");
		if(!isset($command['mch_id'])) throw new Exception("command miss mch_id");
		if(!isset($command['transaction_id'])) throw new Exception("command miss transaction_id");
		if(!isset($command['out_refund_no'])) throw new Exception("command miss out_refund_no");
		if(!isset($command['total_fee'])) throw new Exception("command miss total_fee");
		if(!isset($command['refund_fee'])) throw new Exception("command miss refund_fee");
		//完善指令集
		$command['notify_url']=$this->notifyUrl;
		$command['nonce_str']=$this->nonceStr;
		$command['sign']=$smartWechatPay->sign($command,$command['mch_id']);
		//拼接xml
		$xml="<xml>";
		$xml.="<appid>{$command['appid']}</appid>";
		$xml.="<mch_id>{$command['mch_id']}</mch_id>";
		$xml.="<transaction_id>{$command['transaction_id']}</transaction_id>";
		$xml.="<out_refund_no>{$command['out_refund_no']}</out_refund_no>";
		$xml.="<total_fee>{$command['total_fee']}</total_fee>";
		$xml.="<refund_fee>{$command['refund_fee']}</refund_fee>";
		$xml.="<nonce_str>{$command['nonce_str']}</nonce_str>";
		$xml.="<notify_url>{$command['notify_url']}</notify_url>";
		$xml.="<sign>{$command['sign']}</sign>";
		$xml.="</xml>";
		//请求app微信统一下单api
		$api="https://api.mch.weixin.qq.com/secapi/pay/refund";
		$curlConf=array();
		$curlConf[CURLOPT_SSL_VERIFYPEER]=false;
		$curlConf[CURLOPT_SSL_VERIFYHOST]=false;
		$curlConf[CURLOPT_SSLCERT]=getcwd().'/apiclient_cert.pem';
		$curlConf[CURLOPT_SSLKEY]=getcwd().'/apiclient_key.pem';
		$curlConf[CURLOPT_CAINFO]=getcwd().'/rootca.pem';
		$curlConf[CURLOPT_HTTPHEADER]=array("Content-type: text/xml");
		$reponse=Yii::$app->smartApi->post($api,$xml,$curlConf);
		//检查结果集数据
		if(!isset($reponse['state']))
			throw new SmartException("callUnifiedorderApi reponse miss state");
		if(!isset($reponse['reponse']))
			throw new SmartException("callUnifiedorderApi reponse miss reponse");
		if(!$reponse['state'])
			throw new SmartException("callUnifiedorderApi reponse error {$reponse['reponse']}");
		//api返回的xml结果,将其转换为数组
		libxml_disable_entity_loader(true);
		$reponse=simplexml_load_string($reponse['reponse'],'SimpleXMLElement',LIBXML_NOCDATA);
		$reponse=json_decode(json_encode($reponse),true);
		//调用失败
		if(!(isset($reponse['result_code']) && $reponse['result_code']=="SUCCESS"))
			throw new SmartException("call wx fund api error");
	}
}