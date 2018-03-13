<?php
//调用API组件
namespace common\components\smartQiNiu;
require 'autoload.php';
use Yii;
use yii\base\Component;
use yii\base\SmartException;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
class smartQiNiu extends Component{
	public $accessKey=NULL;
	public $secretKey=NULL;
	public $bucket=NULL;
	private $auth=false;
	private $token=false;
	//========================================
	public function init(){
		parent::init();
		$this->auth=new Auth($this->accessKey,$this->secretKey);
		$this->token=$this->auth->uploadToken($this->bucket);
	}
	//========================================
	//获取令牌
	public function getToken(){return $this->token;}
}