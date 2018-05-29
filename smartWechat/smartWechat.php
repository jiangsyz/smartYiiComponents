<?php
//微信组件
namespace common\components\smartWechat;
use Yii;
use yii\base\Component;
use yii\base\SmartException;
class smartWechat extends Component{
	//公众号信息
	public $appID=false;
	public $appSecret=false;
	//========================================
	//通过jscode获取sessionKey和openid
	public function jscode2session($jscode){
		$uri="https://api.weixin.qq.com/sns/jscode2session?appid={$this->appID}&secret={$this->appSecret}&js_code={$jscode}&grant_type=authorization_code";
		//调用接口
		$reponse=Yii::$app->smartApi->get($uri,array(CURLOPT_SSL_VERIFYPEER=>false));
		//处理数据
		$reponse=json_decode($reponse['reponse'],true);
		if(!isset($reponse['session_key'])) throw new SmartException("获取session_key失败",-2);
		if(!isset($reponse['openid'])) throw new SmartException("获取openid失败",-2);
		//返回数据
		return array('sessionKey'=>$reponse['session_key'],'openid'=>$reponse['openid']);
	}
	//========================================
	//解码用户加密信息
	public function encrypteData($sessionKey,$iv,$encryptedData){
		if(strlen($sessionKey)!=24) throw new SmartException("sessionKey不合法",-2);
		$aesKey=base64_decode($sessionKey);
		if(strlen($iv)!=24) throw new SmartException("iv不合法",-2);
		$aesIV=base64_decode($iv);
		$aesCipher=base64_decode($encryptedData);
		$result=openssl_decrypt($aesCipher,"AES-128-CBC",$aesKey,1,$aesIV);
		$data=json_decode($result,true);
		return $data;
	}
	//========================================
}
