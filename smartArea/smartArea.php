<?php
//区域
namespace common\components\smartArea;
use Yii;
use yii\base\SmartException;
use common\components\smartComponents\smartComponentsNeedDb;
class smartArea extends smartComponentsNeedDb{
	public $smartAreaRecord=false;
	//====================================================
	public function init(){
		parent::init();
		//设置smartAreaRecord的类名
		$this->smartAreaRecord=smartAreaRecord::className();
	}
	//====================================================
	//获取顶级地域
	public function getTopAreas(){return smartAreaRecord::find()->where("`parent_id`='0'")->all();}
	//====================================================
	//获取某个地域的子地域
	public function getChildAreas($areaId){return smartAreaRecord::find()->where("`parent_id`='$areaId'")->all();}
	//====================================================
	//获取某个地域
	public function getArea($areaId){return smartAreaRecord::find()->where("`area_id`='$areaId'")->one();}
}