<?php
//微信组件
namespace common\components\smartWechat;
use Yii;
use yii\base\Component;
use yii\base\SmartException;
class smartWechat extends Component{
	const CACHE_WECHAT_ACCESS_TOKEN="cacheWechatAccessToken";
	//========================================
	//获取access token的api
	const API_GET_ACCESS_TOKEN="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential";
	const API_GET_OPENID="https://api.weixin.qq.com/sns/oauth2/";
	//========================================
	//公众号信息
	public $appID=false;
	public $appSecret=false;
	//生成签名用的随机字符串
	private $noncestr="smartWechat";
	//========================================
	//调用获取access_token的api
	public function callAccessTokenApi(){
		$uri=self::API_GET_ACCESS_TOKEN."&appid={$this->appID}&secret={$this->appSecret}";
		//调用接口
		$reponse=Yii::$app->smartApi->get($uri,array(CURLOPT_SSL_VERIFYPEER=>false));
		if(!$reponse['state']) throw new SmartException($reponse['reponse']);
		//处理数据
		$reponse=json_decode($reponse['reponse'],true);
		if(!isset($reponse['access_token'])) throw new SmartException("reponse miss access_token");
		if(!isset($reponse['expires_in'])) throw new SmartException("reponse miss expires_in");
		return $reponse;
	}
	//========================================
}