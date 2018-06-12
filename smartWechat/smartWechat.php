<?php
//微信组件
namespace common\components\smartWechat;
use Yii;
use yii\base\Component;
use yii\base\SmartException;
class smartWechat extends Component{
	const API_ACCESS_TOKEN="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential";
	const API_JSCODE_TO_CODE="https://api.weixin.qq.com/sns/jscode2session?";
	const API_GET_USER="https://api.weixin.qq.com/cgi-bin/user/get";
	const API_GET_USER_INFO_BATCH="https://api.weixin.qq.com/cgi-bin/user/info/batchget";
	//========================================
	//通过jscode获取sessionKey和openid
	public function jscode2session($appId,$appSecret,$jscode){
		//调用接口
		$uri=self::API_JSCODE_TO_CODE."appid={$appId}&secret={$appSecret}&js_code={$jscode}&grant_type=authorization_code";
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
		//确定索引
		$index="{$appId}_accessToken";
		//先尝试从redis中尝试获取
		$accessToken=Yii::$app->redis->get($index);
		if($accessToken) return $accessToken;
		//调用接口		
		$uri=self::API_ACCESS_TOKEN."&appid={$appId}&secret={$appSecret}";
		$response=Yii::$app->smartApi->get($uri,array(CURLOPT_SSL_VERIFYPEER=>false));
		//处理数据
		$response=json_decode($response['response'],true);
		if(!isset($response['access_token'])) throw new SmartException("获取accessToken失败",-2);
		if(!isset($response['expires_in'])) throw new SmartException("获取expiresIn失败",-2);
		//缓存
		Yii::$app->cache->set($index,$response['access_token'],$response['expires_in']); 
		//返回令牌
		return $response['access_token'];
	}
	//========================================
	//获取公众号所有用户的openid
	public function getOpenidsFromPublicAccount($appId,$appSecret){
		//获取accessToken
		$accessToken=$this->getAccessToken($appId,$appSecret);
		//调用借口
		$uri=self::API_GET_USER."?access_token={$accessToken}";
		$response=Yii::$app->smartApi->get($uri,array(CURLOPT_SSL_VERIFYPEER=>false));
		//处理数据
		$response=json_decode($response['response'],true);
		if(!isset($response['total'])) throw new SmartException("获取total失败",-2);
		if(!isset($response['count'])) throw new SmartException("获取count失败",-2);
		if(!isset($response['data']['openid'])) throw new SmartException("获取openid失败",-2);
		//总数
		$total=$response['total'];
		//已获取数量
		$count=$response['count'];
		//获取openid
		$openids=array();
		$openids=array_merge($openids,$response['data']['openid']);
		while($total>$count){
			if(!isset($response['next_openid'])) throw new SmartException("获取next_openid失败",-2);
			//调用借口
			$uri=self::API_GET_USER."?access_token={$accessToken}&next_openid={$response['next_openid']}";
			$response=Yii::$app->smartApi->get($uri,array(CURLOPT_SSL_VERIFYPEER=>false));
			//处理数据
			$response=json_decode($response['response'],true);
			if(!isset($response['count'])) throw new SmartException("获取count失败",-2);
			if(!isset($response['data']['openid'])) throw new SmartException("获取openid失败",-2);
			//追加openid
			$openids=array_merge($openids,$response['data']['openid']);
			//更新已获取数量
			$count+=$response['count'];
		}
		//返回openid列表
		return $openids;
	}
	//========================================
	//获取公众号用户的unionid
	public function getUnionidsFromPublicAccount($appId,$appSecret,$openids){
		//最多只接收100个openid
		if(count($openids)>100) throw new SmartException("openids count > 100");
		//获取accessToken
		$accessToken=$this->getAccessToken($appId,$appSecret);
		//组织参数
		$param['user_list']=array();
		foreach($openids as $v) $param['user_list'][]=array('openid'=>$v,'lang'=>'zh-CN');
		$param=json_encode($param);
		//调用接口
		$uri=self::API_GET_USER_INFO_BATCH."?access_token={$accessToken}";
		$response=Yii::$app->smartApi->post($uri,$param,array(CURLOPT_SSL_VERIFYPEER=>false));
		//处理数据
		$response=json_decode($response['response'],true);
		if(!isset($response['user_info_list'])) throw new SmartException("获取user_info_list失败",-2);
		//以openid为key,unionid为val作为数据返回
		$unionids=array();
		foreach($response['user_info_list'] as $v){
			if(!isset($v['openid'])) continue;
			if(!isset($v['unionid'])) continue;
			$unionids[$v['openid']]=$v['unionid'];
		}
		return $unionids;
	}
}
