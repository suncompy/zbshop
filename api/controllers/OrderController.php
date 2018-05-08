<?php
namespace api\controllers;

use Yii;

use api\controllers\bases\BaseController;
use common\helpers\HttpHelper;
use common\helpers\WxPay;
use common\models\TempModel;
/**
 * 订单类
 * Site controller
 */
class OrderController extends BaseController
{
	/**
	 * 微信支付
	 * @return [type] [description]
	 */
	public function actionPay()
	{
		$request = Yii::$app->request;
		$openid = $request->post('openid');
		$out_trade_no = (new TempModel)->buildOrderNo();
		$body = '支付测试'; 
		$total_fee = '0.01';
		$pay = new WxPay;
		$result = $pay->pay($openid, $out_trade_no, $body, $total_fee);
		return $this->send(200, 'success', $result);
	}
	/**
	 * 微信支付异步通知
	 * @return [type]        [description]
	 */
	public function actionPayNotify()
	{
		$xml = $GLOBALS['HTTP_RAW_POST_DATA'];  
      	Yii::$app->cache('notify', $xml);
	    //将服务器返回的XML数据转化为数组  
	    $payModel = new WxPay;
	    $data = $payModel->xmlToArray($xml);  
	    // 保存微信服务器返回的签名sign  
	    $data_sign = $data['sign'];  
	    // sign不参与签名算法  
	    unset($data['sign']); 
	    $sign = $payModel->getSign($data);  
	      
	    // 判断签名是否正确  判断支付状态  
	    if ( ($sign === $data_sign) && ($data['return_code'] == 'SUCCESS') && ($data['result_code'] == 'SUCCESS') ) {  
	        $result = $data;  
	        //获取服务器返回的数据  
	        $order_sn = $data['out_trade_no'];          //订单单号  
	        $openid = $data['openid'];                  //付款人openID  
	        $total_fee = $data['total_fee'];            //付款金额  
	        $transaction_id = $data['transaction_id'];  //微信支付流水号  
	          
	        //更新数据库  
	        // ($order_sn,$openid,$total_fee,$transaction_id);  
	          
	    }else{  
	        $result = false;  
	    }  
	    // 返回状态给微信服务器  
	    if ($result) {  
	        $str='<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';  
	    }else{  
	        $str='<xml><return_code><![CDATA[FAIL]]></return_code><return_msg><![CDATA[签名失败]]></return_msg></xml>';  
	    }  
	    echo $str;  
	    // return $result;  
	}
}