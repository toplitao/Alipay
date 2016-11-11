<?php
/**
 * Created by PhpStorm.
 * User: luckerdj
 * Date: 16/6/27
 * Time: 上午11:11
 */

namespace Yilu\Alipay\BatchTrans;

require 'src/alipay_submit.class.php';

class BatchTrans
{
    private $alipay_submit;

    private $__https_verify_url = 'https://mapi.alipay.com/gateway.do?service=notify_verify&';

    private $__http_verify_url = 'http://notify.alipay.com/trade/notify_query.do?';

    private $partner;

    private $_input_charset = 'UTF-8';

    private $sign_type = 'MD5';

    private $private_key_path;

    private $public_key_path;

    private $notify_url;

    private $out_trade_no;

    private $subject;

    private $payment_type = 1;

    private $seller_id;

    private $total_fee;

    private $body;

    private $show_url;

    private $anti_phishing_key;

    private $exter_invoke_ip;

    private $key;

    private $transport='http';

    private $cacert;

    public function __construct()
    {
        $this->cacert = getcwd() . DIRECTORY_SEPARATOR .'cacert.pem';
    }

    public function buildRequestHttp($params)
    {
        return $this->alipay_submit->buildRequestHttp($params);
    }

    public function buildRequestForm($params)
    {
        if (empty($this->partner)) {
            throw new \Exception('请设置商户ID');
        }
        $this->alipay_submit = new \AlipaySubmit([
            'partner' => $this->partner,
            'key' => $this->key,
            'sign_type' => $this->sign_type,
            'input_charset' => $this->_input_charset,
            'cacert' => $this->cacert,
            'transport' => $this->transport
        ]);
        $parameter = array( "service" => "batch_trans_notify", 
			"partner" => trim(Config::$partner), 
			"notify_url" => 'notify_url', 
			"email" => 'xxx@xxx 支付宝账号', 
			"account_name" => 'xxxxx公司', 
			"pay_date" => '20160627', 
			"batch_no" => '201008010000001', 
			"batch_fee" => '0.01', 
			"batch_num" => '1', 
			"detail_data" => '000001^支付宝账号^姓名^0.01^测试', 
			"_input_charset" => trim(strtolower(Config::$input_charset)) 
		);


        return $this->alipay_submit->buildRequestForm($params, 'post', 'submit');
    }


    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    public function setNotifyUrl($notify_url)
    {
        $this->notify_url = $notify_url;
        return $this;
    }

    public function setOutTradeNo($out_trade_no)
    {
        $this->out_trade_no = $out_trade_no;
        return $this;
    }

    public function setPartner($partner)
    {
        $this->partner = $partner;
        return $this;
    }

    public function setPrivateKeyPath($private_key_path)
    {
        $this->private_key_path = $private_key_path;
        return $this;
    }

    public function setPublicKeyPath($public_key_path)
    {
        $this->public_key_path = $public_key_path;
        return $this;
    }

    public function setSellerId($seller_id)
    {
        $this->seller_id = $seller_id;
        return $this;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function setTotalFee($total_fee)
    {
        $this->total_fee = $total_fee;
        return $this;
    }

    public function setSignType($sign_type)
    {
        $this->sign_type = $sign_type;
        return $this;
    }

    public function setCacert($cacert)
    {
        $this->cacert = $cacert;
        return $this;
    }
}
