<?php
//调用API组件
namespace common\components\smartApi;
use Yii;
use yii\base\Component;
use yii\base\SmartException;
class smartApi extends Component{
	public $timeOut=false;
	private $log=false;
	//========================================
	public function init(){
		parent::init();
		if(isset(Yii::$app->smartLog)) $this->log=Yii::$app->smartLog;
	}
	//========================================
	//记录日志
	private function log($logData){if($this->log) $this->log->callApiLog($logData);}
	//========================================
	//执行curl_exec,并确定状态和结果
	private function curlExec($ch){
		//请求接口
		$reponse=curl_exec($ch);
		//确定状态和结果
		$state=$reponse===false?false:true;
		$reponse=$reponse===false?curl_error($ch):$reponse;
		//返回状态和结果
		return array('state'=>$state,'reponse'=>$reponse);
	}
	//========================================
	//get方法请求
	public function get($uri,$curlConf=array()){
		$ch=curl_init();
		//curl基础设置
		curl_setopt($ch,CURLOPT_URL,$uri);
		curl_setopt($ch,CURLOPT_HEADER,0);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		if($this->timeOut) curl_setopt($ch,CURLOPT_TIMEOUT,$this->timeOut);
		//根据传入的curl进行设置
		foreach($curlConf as $key=>$val) curl_setopt($ch,$key,$val);
		//请求接口
		$re=$this->curlExec($ch);
		//记录日志
		$this->log(json_encode(array('type'=>'get','uri'=>$uri,'reponse'=>$re)));
		//关闭句柄
		curl_close($ch);
		//返回api数据
		return $re;
	}
	//========================================
	//post方法
	public function post($uri,$postData,$curlConf=array()){
		$ch=curl_init();
		//curl基础设置
		curl_setopt($ch,CURLOPT_URL,$uri);
		curl_setopt($ch,CURLOPT_HEADER,0);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$postData);
		if($this->timeOut) curl_setopt($ch,CURLOPT_TIMEOUT,$this->timeOut);
		//根据传入的curl进行设置
		foreach($curlConf as $key=>$val) curl_setopt($ch,$key,$val);
		//请求接口
		$re=$this->curlExec($ch);
		//记录日志
		$this->log(json_encode(array('type'=>'post','uri'=>$uri,'data'=>$postData,'reponse'=>$re)));
		//关闭句柄
		curl_close($ch);
		//返回api数据
		return $re;
	}
}