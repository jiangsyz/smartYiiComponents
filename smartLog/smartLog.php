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
	const HTTP_REQUEST_LOG=1;
	const HTTP_RESPONSE_LOG=2;
	const CALL_API_LOG=4;
	const EXCEPTION_LOG=8;
	//====================================================
	private function log($logType,$data){
		//该类型在配置中被关闭
		if(!($this->level&$logType)) return;
		//记录日志
		$logRecord=array();
		$logRecord['runningId']=Yii::$app->controller->runningId;
		$logRecord['logType']=$logType;
		$logRecord['data']=$data;
		$logRecord['time']=time();
		smartLogRecord::addObj($logRecord);
	}
	//====================================================
	public function httpRequestLog($data){$this->log(self::HTTP_REQUEST_LOG,$data);}
	public function httpResponseLog($data){$this->log(self::HTTP_RESPONSE_LOG,$data);}
	public function callApiLog($data){$this->log(self::CALL_API_LOG,$data);}
	public function exceptionLog($data){$this->log(self::EXCEPTION_LOG,$data);}
}