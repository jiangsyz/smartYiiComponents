<?php
//微信组件
namespace common\components\smartWechat;
use Yii;
use yii\base\Component;
use yii\base\SmartException;
class smartWechat extends Component{
	//通过jscode获取sessionKey和openid
	public function jscode2session($appId,$appSecret,$jscode){
		$uri="https://api.weixin.qq.com/sns/jscode2session?appid={$appId}&secret={$appSecret}&js_code={$jscode}&grant_type=authorization_code";
		//调用接口
		$response=Yii::$app->smartApi->get($uri,array(CURLOPT_SSL_VERIFYPEER=>false));
		//处理数据
		$response=json_decode($response['response'],true);
		if(!isset($response['session_key'])) throw new SmartException("获取session_key失败",-2);
		if(!isset($response['openid'])) throw new SmartException("获取openid失败",-2);
		//返回数据
		return array('sessionKey'=>$response['session_key'],'openid'=>$response['openid']);
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
	//获取accessToken
	public function getAccessToken($appId,$appSecret){
		$uri="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appId}&secret={$appSecret}";
		//调用接口
		$response=Yii::$app->smartApi->get($uri,array(CURLOPT_SSL_VERIFYPEER=>false));
		//处理数据
		$response=json_decode($response['response'],true);
		if(!isset($response['access_token'])) throw new SmartException("获取accessToken失败",-2);
		if(!isset($response['expires_in'])) throw new SmartException("获取expiresIn失败",-2);
		//返回数据
		return array('accessToken'=>$response['access_token'],'expiresIn'=>$response['expires_in']);
	}
}
