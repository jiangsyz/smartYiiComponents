<?php
namespace common\components\smartToken;
use Yii;
use yii\base\SmartException;
use yii\db\SmartActiveRecord;
//========================================
class smartTokenRecord extends SmartActiveRecord{
	static function getDb(){return Yii::$app->smartToken->dbConnection;}
	//========================================
	static function tableName(){return "smart_token_record";}
	//========================================
	public function rules(){
	    return array(
	        //去空格
	        array(array('data'),'trim'),
	        //必填
	        array(array('type','data'),'required'),
	    );
	}
	//========================================
	public function init(){
		parent::init();
		$this->on(self::EVENT_BEFORE_INSERT,array($this,"initData"));
	}
	//========================================
	//初始化数据
	public function initData(){
		$this->token=uniqid().rand(111111,999999);
		$this->createTime=time();
		$this->isTimeOut=0;
	}
	//========================================
	//判断是否超时
	public function isTimeOut(){
		if(time()<=$this->getTimeOutTimestamp()) return false;
		$this->updateObj(array('isTimeOut'=>1));
		return true;
	}
	//========================================
	//获取超时时间戳
	public function getTimeOutTimestamp(){return Yii::$app->smartToken->getTokenTimeOutTimestamp($this);}
}
?>