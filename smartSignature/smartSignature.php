<?php
//签名
namespace common\components\smartSignature;
use Yii;
use yii\base\SmartException;
use yii\base\Component;
//====================================================
class smartSignature extends Component{
	//签名算法的私钥
	public $secretKey=false;
	//签名的失效秒数
	public $timeOut=false;
	//controller完全匹配的白名单
	public $controllerWhiteList=array();
	//action完全匹配的白名单
	public $actionWhiteList=array();
	//action前缀匹配的白名单
	public $actionPrefixWhiteList=array();
	//====================================================
	public function init(){
		//设置超时信息
		if(!$this->timeOut) throw new SmartException("miss timeOut");
		if($this->timeOut<0) throw new SmartException("error timeOut");
		parent::init();
	}
	//====================================================
	//检查签名
	public function checkSignature(){
		
	}
}