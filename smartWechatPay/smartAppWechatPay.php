<?php
//APP微信支付
namespace common\components\smartWechatPay;
use Yii;
use yii\base\Component;
use yii\base\SmartException;
class smartAppWechatPay extends smartPayManagement{
	//应用id
	public $appId=NULL;
	//商户号
	public $mchId=NULL;
	//回调地址
	public $notifyUrl=NULL;
	//设备号
	public $deviceInfo=NULL;
	//交易类型
	public $tradeType=NULL;
	//随机字符串
	public $nonceStr=false;
	//客户端ip
	public $spbillCreateIp=false;
	//========================================
	//初始化
	public function init(){
		parent::init();
		//随机字符串
		$this->nonceStr=md5(rand(1,99999));
		//客户端ip
		$this->spbillCreateIp=$_SERVER['REMOTE_ADDR'];
		//校验
		if(!$this->appId) throw new SmartException("miss appId");
		if(!$this->mchId) throw new SmartException("miss mchId");
		if(!$this->notifyUrl) throw new SmartException("miss notifyUrl");
		if(!$this->deviceInfo) throw new SmartException("miss deviceInfo");
	}
	//========================================
	//申请支付
	public function applyPay($command,smartWechatPay $smartWechatPay){
		//校验指令集的必须项
		if(!isset($command['attach'])) throw new SmartException("cmd miss attach");
		if(!isset($command['body'])) throw new SmartException("cmd miss body");
		if(!isset($command['out_trade_no'])) throw new SmartException("cmd miss outTradeNo");
		if(!isset($command['total_fee'])) throw new SmartException("cmd miss totalFee");
		//完善指令集
		$command['appid']=$this->appId;
		$command['mch_id']=$this->mchId;
		$command['notify_url']=$this->notifyUrl;
		$command['nonce_str']=$this->nonceStr;
		$command['spbill_create_ip']=$this->spbillCreateIp;
		$command['trade_type']=$this->tradeType;
		$command['sign']=$smartWechatPay->sign($command,$this->mchId);
		//拼接xml
		$xml="<xml>";
		$xml.="<appid>{$command['appid']}</appid>";
		$xml.="<attach>{$command['attach']}</attach>";
		$xml.="<body>{$command['body']}</body>";
		$xml.="<mch_id>{$command['mch_id']}</mch_id>";
		$xml.="<nonce_str>{$command['nonce_str']}</nonce_str>";
		$xml.="<notify_url>{$command['notify_url']}</notify_url>";
		$xml.="<out_trade_no>{$command['out_trade_no']}</out_trade_no>";
		$xml.="<spbill_create_ip>{$command['spbill_create_ip']}</spbill_create_ip>";
		$xml.="<total_fee>{$command['total_fee']}</total_fee>";
		$xml.="<trade_type>{$command['trade_type']}</trade_type>";
		$xml.="<sign>{$command['sign']}</sign>";
		$xml.="</xml>";
		//微信支付统一下单,获取预支付交易会话标识
		$prepayId=$this->callUnifiedorderApi($xml);
		//返回sdk拉去支付所需的数据
		$sdkData=array();
		$sdkData['appid']=$this->appId;
		$sdkData['partnerid']=$this->mchId;
		$sdkData['prepayid']=$prepayId;
		$sdkData['package']='Sign=WXPay';
		$sdkData['noncestr']=$this->nonceStr;
		$sdkData['timestamp']=time();
		$sdkData['sign']=$smartWechatPay->sign($sdkData,$this->mchId);
		return $sdkData;
	}
	//========================================
	//微信支付统一下单
	private function callUnifiedorderApi($xml){
		//请求app微信统一下单api
		$api="https://api.mch.weixin.qq.com/pay/unifiedorder";
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
		//获取预支付交易会话标识	
		if(!isset($reponse['prepay_id'])) throw new SmartException("miss prepay_id");
		//返回预支付交易会话标识
		return $reponse['prepay_id'];
	}
}