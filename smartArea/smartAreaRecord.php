<?php
namespace common\components\smartArea;
use Yii;
use yii\base\Exception;
use yii\db\SmartActiveRecord;
//========================================
class smartAreaRecord extends SmartActiveRecord{
	static function getDb(){return Yii::$app->smartArea->dbConnection;}
	//========================================
	static function tableName(){return "smart_area_record";}
	//========================================
	//是否是叶子区域
	public function isLeafArea(){
		if(self::find()->where("`parent_id`='{$this->area_id}'")->one()) return true; else false;
	}
}
?>