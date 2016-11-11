<?php
namespace Yilu\Alipay;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application as LaravelApplication;
use Laravel\Lumen\Application as LumenApplication;

class AlipayServiceProvider extends ServiceProvider
{

	/**
	 * boot process
	 */
	public function boot()
	{
		$this->setupConfig();
	}

	/**
	 * Setup the config.
	 *
	 * @return void
	 */
	protected function setupConfig()
	{
		$source_config = realpath(__DIR__ . '/../../config/config.php');
		$source_mobile = realpath(__DIR__ . '/../../config/mobile.php');
		$source_web = realpath(__DIR__ . '/../../config/web.php');
		$source_batch_trans = realpath(__DIR__ . '/../../config/batch_trans.php');
		if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
			$this->publishes([
				$source_config => config_path('alipay/yilu-alipay.php'),
				$source_mobile => config_path('alipay/yilu-alipay-mobile.php'),
				$source_web => config_path('alipay/yilu-alipay-web.php'),
				$source_batch_trans =>config_path('alipay/yilu-alipay-batch_trans.php'),
			]);
		} elseif ($this->app instanceof LumenApplication) {
			$this->app->configure('alipay/yilu-alipay');
			$this->app->configure('alipay/yilu-alipay-mobile');
			$this->app->configure('alipay/yilu-alipay-web');
			$this->app->configure('alipay/yilu-alipay-batch_trans');
		}
		
		$this->mergeConfigFrom($source_config, 'alipay/yilu-alipay');
		$this->mergeConfigFrom($source_mobile, 'alipay/yilu-alipay-mobile');
		$this->mergeConfigFrom($source_web, 'alipay/yilu-alipay-web');
		$this->mergeConfigFrom($source_web, 'alipay/yilu-alipay-batch_trans');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		
		$this->app->bind('alipay.mobile', function ($app)
		{
			$alipay = new Mobile\SdkPayment();

			$alipay->setPartner($app->config->get('alipay.yilu-alipay.partner_id'))
				->setSellerId($app->config->get('alipay.yilu-alipay.seller_id'))
				->setSignType($app->config->get('alipay.yilu-alipay-mobile.sign_type'))
				->setPrivateKeyPath($app->config->get('alipay.yilu-alipay-mobile.private_key_path'))
				->setPublicKeyPath($app->config->get('alipay.yilu-alipay-mobile.public_key_path'))
				->setNotifyUrl($app->config->get('alipay.yilu-alipay-mobile.notify_url'));

			return $alipay;
		});

		$this->app->bind('alipay.web', function ($app)
		{
			$alipay = new Web\SdkPayment();

			$alipay->setPartner($app->config->get('alipay.yilu-alipay.partner_id'))
				->setSellerId($app->config->get('alipay.yilu-alipay.seller_id'))
				->setKey($app->config->get('alipay.yilu-alipay-web.key'))
				->setSignType($app->config->get('alipay.yilu-alipay-web.sign_type'))
				->setNotifyUrl($app->config->get('alipay.yilu-alipay-web.notify_url'))
				->setReturnUrl($app->config->get('alipay.yilu-alipay-web.return_url'))
				->setExterInvokeIp($app->request->getClientIp());

			return $alipay;
		});

		$this->app->bind('alipay.wap', function ($app)
		{
			$alipay = new Wap\SdkPayment();

			$alipay->setPartner($app->config->get('alipay.yilu-alipay.partner_id'))
			->setSellerId($app->config->get('alipay.yilu-alipay.seller_id'))
			->setKey($app->config->get('alipay.yilu-alipay-wap.key'))
			->setSignType($app->config->get('alipay.yilu-alipay-wap.sign_type'))
			->setNotifyUrl($app->config->get('alipay.yilu-alipay-wap.notify_url'))
			->setReturnUrl($app->config->get('alipay.yilu-alipay-wap.return_url'))
			->setExterInvokeIp($app->request->getClientIp());

			return $alipay;
		});

		$this->app->bind('alipay.batch_trans', function ($app)
		{
			$alipay = new BatchTrans\SdkPayment();

			$alipay->setPartner($app->config->get('alipay.yilu-alipay.partner_id'))
			->setSellerId($app->config->get('alipay.yilu-alipay.seller_id'))
			->setKey($app->config->get('alipay.yilu-alipay-batch_trans.key'))
			->setSignType($app->config->get('alipay.yilu-alipay-batch_trans.sign_type'))
			->setAccountName($app->config->get('alipay.yilu-alipay-batch_trans.account_name'))
			->setNotifyUrl($app->config->get('alipay.yilu-alipay-batch_trans.notify_url'))
			->setReturnUrl($app->config->get('alipay.yilu-alipay-batch_trans.return_url'))
			->setExterInvokeIp($app->request->getClientIp());

			return $alipay;
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [
			'alipay.mobile',
			'alipay.web',
			'alipay.wap',
			'alipay.batch_trans',
		];
	}
}
