<?php
namespace common\components\smartIdentifyingCode;
use Yii;
use yii\base\SmartException;
use common\components\smartComponents\smartComponentsNeedDb;
//========================================
class smartIdentifyingCode extends smartComponentsNeedDb{
	//验证码订单过期秒数
	public $timeOut=false;
	//====================================================
	public function init(){
		parent::init();
		//设置超时信息
		if(!$this->timeOut) throw new SmartException("miss timeOut");
		if($this->timeOut<0) throw new SmartException("error timeOut");
	}
	//====================================================
	//创建一个验证码订单
	public function creatOrder($type,$data){return smartIdentifyingCodeOrder::addObj(array('type'=>$type,'data'=>$data));
	}
	//====================================================
	//验证,错误抛异常,正确返回验证码订单记录
	public function check($orderId,$identifyingCode){
		//验证一定要开启事务,不然下面的加锁没有意义
		if(!$this->dbConnection->getTransaction()) throw new SmartException("need beginTransaction");
		//加锁
		$sql="SELECT `id` FROM ".smartIdentifyingCodeOrder::tableName()." WHERE `id`='{$orderId}' FOR UPDATE";
		$this->dbConnection->createCommand($sql)->query();
		//查找订单
		$order=smartIdentifyingCodeOrder::find()->where("`id`='{$orderId}'")->one();
		if(!$order) throw new SmartException("miss smartIdentifyingCodeOrder");
		//校验
		return $order->check($identifyingCode,$this->timeOut);
	}
}