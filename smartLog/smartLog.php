<?php
//日志组件
namespace common\components\smartLog;
use Yii;
use yii\base\SmartException;
use common\components\smartComponents\smartComponentsNeedDb;
//====================================================
class smartLog extends smartComponentsNeedDb{
	//日志开启等级
	public $level=0;
	//日志类型
	const HTTP_LOG=1;//http请求日志
	const CALL_API_LOG=2;//调用api日志
	const EXCEPTION_LOG=4;//异常日志
	const DEBUG_LOG=8;//调试日志
	const CONSOLE_LOG=16;//命令行日志
	//====================================================
	private function log($logType,$data){
		//该类型在配置中被关闭
		if(!($this->level&$logType)) return;
		//记录日志
		$logRecord=array();
		$logRecord['runningId']=Yii::$app->controller->runningId;
		$logRecord['controllerId']=Yii::$app->controller->id;
		$logRecord['actionId']=Yii::$app->controller->action->id;
		$logRecord['logType']=$logType;
		$logRecord['data']=$data;
		$logRecord['time']=time();
		if($logType==self::HTTP_LOG) 
			return smartLogRecord::addObj($logRecord);
		else
			return smartLogRecordBak::addObj($logRecord);
	}
	//====================================================
	public function httpLog($data){return $this->log(self::HTTP_LOG,$data);}
	public function callApiLog($data){return $this->log(self::CALL_API_LOG,$data);}
	public function exceptionLog($data){return $this->log(self::EXCEPTION_LOG,$data);}
	public function debugLog($data){return $this->log(self::DEBUG_LOG,$data);}
	public function consoleLog($data){return $this->log(self::CONSOLE_LOG,$data);}
}