<?php
/*
组件本身需要数据库,可以自定义yii的数据连接模块
*/
namespace common\components\smartComponents;
use Yii;
use yii\base\Component;
use yii\base\SmartException;
class smartComponentsNeedDb extends Component{
	public $dbComponent=false;
	public $dbConnection=false;
	//====================================================
	public function init(){
		parent::init();
		//根据配置获得yii\db\Connection
		if(!$this->dbComponent) throw new SmartException("miss dbComponent");
		$componentName=$this->dbComponent;
		if(!isset(Yii::$app->$componentName)) throw new SmartException("miss dbConnection");
		$this->dbConnection=Yii::$app->$componentName;
	}
	//====================================================
}