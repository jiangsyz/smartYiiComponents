<?php
namespace common\components\smartLog;
use Yii;
use yii\db\SmartActiveRecord;
//========================================
class smartLogRecord extends SmartActiveRecord{
	static function getDb(){return Yii::$app->smartLog->dbConnection;}
	//========================================
	static function tableName(){return "smart_log_record";}
}
?>