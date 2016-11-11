<?php
/**
 * Created by PhpStorm.
 * User: luckerdj
 * Date: 16/6/27
 * Time: 上午11:11
 */

namespace Yilu\Alipay\BatchTrans;

require 'src/alipay_submit.class.php';

class SdkPayment
{
    private $alipay_submit;

    private $__https_verify_url = 'https://mapi.alipay.com/gateway.do?service=notify_verify&';

    private $__http_verify_url = 'http://notify.alipay.com/trade/notify_query.do?';

    private $service = 'batch_trans_notify';

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

        $params["service"] = $this->service;
        $params["_input_charset"] = $this->_input_charset; 
        //从配置文件传入的配置
        $params["partner"] = $this->partner;
        $params["email"] = $this->seller_id;
        $params["account_name"] = $this->account_name;


        return $this->alipay_submit->buildRequestForm($params, 'post', 'submit');
    }


    public function setPartner($partner)
	{
		$this->partner = $partner;
		return $this;
	}

	public function setNotifyUrl($notify_url)
	{
		$this->notify_url = $notify_url;
		return $this;
	}

	public function setReturnUrl($return_url)
	{
		$this->return_url = $return_url;
		return $this;
	}

	public function setOutTradeNo($out_trade_no)
	{
		$this->out_trade_no = $out_trade_no;
		return $this;
	}

	public function setKey($key)
	{
		$this->key = $key;
		return $this;
	}

	public function setSellerId($seller_id)
	{
		$this->seller_id = $seller_id;
		return $this;
	}

	public function setTotalFee($total_fee)
	{
		$this->total_fee = $total_fee;
		return $this;
	}

	public function setSubject($subject)
	{
		$this->subject = $subject;
		return $this;
	}

	public function setBody($body)
	{
		$this->body = $body;
		return $this;
	}

	public function setItBPay($it_b_pay)
	{
		$this->it_b_pay = $it_b_pay;
		return $this;
	}

	public function setShowUrl($show_url)
	{
		$this->show_url = $show_url;
		return $this;
	}

	public function setSignType($sign_type)
	{
		$this->sign_type = $sign_type;
		return $this;
	}

	public function setExterInvokeIp($exter_invoke_ip)
	{
		$this->exter_invoke_ip = $exter_invoke_ip;
		return $this;
	}

	public function setAppPay($app_pay)
	{
		$this->app_pay = $app_pay;
		return $this;
	}

	public function setCacert($cacert)
	{
		$this->cacert = $cacert;
		return $this;
	}
    public function setAccountName($account_name)
    {
        $this->account_name = $account_name;
        return $this;
    }
}
