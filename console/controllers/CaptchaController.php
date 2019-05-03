<?php
/**
 * Created by PhpStorm.
 * User: ioioj5
 * Date: 2017/6/4
 * Time: 17:27
 */

namespace console\controllers;


use common\components\ConsoleBaseController;
use common\components\Snoopy;

class CaptchaController extends ConsoleBaseController {
	public $snoopy;
	public $time;

	public function init () {
		parent::init (); // TODO: Change the autogenerated stub
		$this->snoopy = new Snoopy();
		$this->snoopy->agent   = 'Mozilla/5.0 (iPhone; CPU iPhone OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1';
		$this->snoopy->cookies['KDTSESSIONID'] = 'a0be846f60cef4f9ff2714b7ae';
		$this->snoopy->cookies['gr_user_id'] = 'abf9699d-46b2-4880-b576-b9048e8d058e';
		$this->snoopy->cookies['Hm_lvt_7bec91b798a11b6f175b22039b5e7bdd'] = '1496473236,1496474408,1496474530';
		$this->snoopy->cookies['UM_distinctid'] = '15c6cc2d72018b-0facc4f79d697c8-1263684a-15f900-15c6cc2d7213be';
		$this->snoopy->cookies['Hm_lvt_a711e9614d89051798b9fdcc227bdc79'] = '1496474557';
		$this->snoopy->cookies['redirect'] = 'http%3A%2F%2Fwww.youzan.com%2Fv2%2Ffenxiao%2Fmarket%2Fdetail%2Fr8ivwibx%3Fspm%3Dmarket-search';
		$this->snoopy->cookies['captcha_response'] = '5c92a18a7bfafd6b1ad00fc27548dbc2c102d34f';



		$this->snoopy->accept = 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8';
		$this->snoopy->host = 'login.youzan.com';
		//$this->snoopy->referer = 'http://www.youzan.com/v2/fenxiao/market/goods/woman?order=desc&orderby=fx_count';

		$this->time = time();
	}

	public function actionIndex(){
		$this->snoopy->fetch("https://login.youzan.com/sso/index/captcha");
		$result = $this->snoopy->results;

		echo $result;exit;
		print_r($this->snoopy);
		exit;
		$response = $this->snoopy->headers;
		print_r($response);
	}
}