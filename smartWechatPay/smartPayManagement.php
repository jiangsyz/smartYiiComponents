<?php
//支付管理器
namespace common\components\smartWechatPay;
use Yii;
use yii\base\Component;
use yii\base\SmartException;
abstract class smartPayManagement extends Component{
	//申请支付
	abstract public function applyPay($command);
}