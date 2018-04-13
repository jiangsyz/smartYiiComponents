<?php
//签名
namespace common\components\smartSignature;
use Yii;
use yii\base\SmartException;
use yii\base\Component;
use yii\web\SmartWebController;
//====================================================
class smartSignature extends Component{
	//签名算法的密钥
	public $secretKey=NULL;
	//签名的失效秒数
	public $timeOut=NULL;
	//完全匹配白名单
	public $whiteList=NULL;
	//controller完全匹配的白名单
	public $controllerWhiteList=NULL;
	//action完全匹配的白名单
	public $actionWhiteList=NULL;
	//action前缀匹配的白名单
	public $actionPrefixWhiteList=NULL;
	//====================================================
	public function init(){
		parent::init();
		if(!$this->secretKey) throw new SmartException("miss secretKey");
		if(!$this->timeOut) throw new SmartException("miss timeOut");
		if($this->timeOut<0) throw new SmartException("error timeout");
		if(!is_array($this->whiteList)) throw new SmartException("error whiteList");
		if(!is_array($this->controllerWhiteList)) throw new SmartException("error controllerWhiteList");
		if(!is_array($this->actionWhiteList)) throw new SmartException("error actionWhiteList");
		if(!is_array($this->actionPrefixWhiteList)) throw new SmartException("error actionPrefixWhiteList");
	}
	//====================================================
	//检查签名
	public function checkWebSignature(SmartWebController $c){
		//白名单检测
		$controller=$c->id;
		$action=$c->action->id;
		$actionPrefix=explode('-',$action)[0];
		if(in_array($controller.'/'.$action,$this->whiteList)) return;
		if(in_array($controller,$this->controllerWhiteList)) return;
		if(in_array($action,$this->actionWhiteList)) return;
		if(in_array($actionPrefix,$this->actionPrefixWhiteList)) return;
		//获取http信息
		$httpInfo=$c->getHttpInfo();
		//获取请求参数
		$requestData=$httpInfo['requestData'];
		if(!isset($requestData['requestTime'])) throw new SmartException("miss requestTime");
		if(!isset($requestData['signature'])) throw new SmartException("miss signature");
		//判断时间
		$timeDifference=$httpInfo['requestTime']-$requestData['requestTime'];
		if($timeDifference<0) throw new SmartException("error timeDifference");
		if($timeDifference>$this->timeOut) throw new SmartException("request timeOut");
		//提取签名
		$signature=$requestData['signature'];
		//剔除不参与加密的参数
		unset($requestData['r']);
		unset($requestData['signature']);
		//升序排列
		ksort($requestData);
		//json编码
		$requestData=json_encode($requestData);
		//连接密钥
		$requestData=$requestData."^".$this->secretKey;
		//签名
		if($signature!=md5($requestData)) throw new SmartException("error signature");
	}
}
