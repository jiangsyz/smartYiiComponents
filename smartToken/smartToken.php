<?php
namespace common\components\smartToken;
use Yii;
use yii\base\SmartException;
use common\components\smartComponents\smartComponentsNeedDb;
//========================================
class smartToken extends smartComponentsNeedDb{
	//令牌超时的秒数
	public $timeOut=false;
	//====================================================
	public function init(){
		parent::init();
		//设置超时信息
		if(!$this->timeOut) throw new SmartException("miss timeOut");
		if($this->timeOut<0) throw new SmartException("error timeOut");
	}
	//====================================================
	//创建令牌
	public function createToken($type,$data){
		$tokens=smartTokenRecord::find()->where("`type`='{$type}' AND `data`='{$data}' AND `isTimeOut`='0'")->all();
		foreach($tokens as $token) if(!$token->isTimeOut()) return $token;
		return smartTokenRecord::addObj(array('type'=>$type,'data'=>$data));
	}
	//====================================================
	//获取令牌
	public function getToken($tokenStr,$type=array(),$checkTimeOut=true){
		//查找令牌
		$token=smartTokenRecord::find()->where("`token`='{$tokenStr}'")->one();
		//没找到
		if(!$token) return NULL;
		//校验type
		if(!empty($type) && !in_array($token->type,$type)) return NULL;
		//校验超时
		if($token->isTimeOut() && $checkTimeOut) return NULL;
		//返回令牌
		return $token;
	}
	//====================================================
	//获取令牌的失效时间
	public function getTokenTimeOutTimestamp(smartTokenRecord $token){return $token->createTime+$this->timeOut;}
}