<?php
namespace common\components\smartIdentifyingCode;
use Yii;
use yii\base\SmartException;
use yii\db\SmartActiveRecord;
//========================================
class smartIdentifyingCodeOrder extends SmartActiveRecord{
	static function getDb(){return Yii::$app->smartIdentifyingCode->dbConnection;}
	//========================================
	static function tableName(){return "smart_identifying_code_order";}
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
		if(!$this->identifyingCode) $this->identifyingCode=rand(111111,999999);
		$this->createTime=time();
		$this->state=0;
	}
	//========================================
	//验证
	public function check($identifyingCode,$timeOut){
		//判断订单是否被校验
		if($this->state) throw new SmartException("验证码已被使用",-2);
		//判断验证码
		if($this->identifyingCode!=$identifyingCode) throw new SmartException("验证码错误",-2);
		//判断超时
		if((time()-$this->createTime)>$timeOut) throw new SmartException("验证码超时",-2);
		//修改状态
		$this->updateObj(array('state'=>1));
		//验证成功
		return $this;
	}
}
?>