<?php
namespace frontend\controllers;

use common\components\FrontController;
use frontend\models\Order;
use frontend\models\UserAddress;
use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class MyController extends FrontController {
	public $layout = 'my-main';

	public function init () {
		parent::init (); // TODO: Change the autogenerated stub

		// 检测用户是否登入
		if (Yii::$app->user->isGuest ) {
			$this->redirect ( [ '/auth/sign-in', 'forwart'=>Yii::$app->request->url ] );
		}
	}

	/**
	 * 我的首页
	 */
	public function actionIndex () {
		return $this->render ( 'index' );
	}

	/**
	 * 我的订单
	 * @return string
	 */
	public function actionOrder(){

		$page = intval ( Yii::$app->request->get ( 'page', 1 ) );

		$limit  = 20;
		$offset = ( $page - 1 ) * $limit;
		$params = array ( 'my/order', 'page' => '{page}' ); // 生成URL参数数组
		$condition = [];
		$list      = Order::getOrderList($condition, $limit, $offset);
		$totalPage = ceil ( $list[ 'count' ] / $limit );
		$link      = Url::toRoute ( $params ); //$this->createUrl ( 'admin/index', $params ); // '/page/{page}';
		$navbar    = $this->pager ( $page, $limit, $list[ 'count' ], $link, 'active', '' );

		return $this->render ( 'order', [
			'list'      => $list,
			'navbar'    => $navbar,
			'totalPage' => $totalPage,
			'total'     => $list[ 'count' ],
			'page'      => $page,
			'limit'     => $limit,
			'params'    => $params
		] );
	}

	/**
	 * 我的设置
	 * @return string
	 */
	public function actionSetting () {
		return $this->render ( 'setting' );
	}

	/**
	 * 我的收获地址
	 */
	public function actionAddress () {
		$list = UserAddress::getAddressListByUserId ( Yii::$app->user->id );


		return $this->render ( 'address', [ 'list' => $list ] );
	}

	/**
	 * 添加或修改收货地址
	 * @return string
	 * @throws NotFoundHttpException
	 */
	public function actionAddAddress () {
		if ( Yii::$app->request->isPost ) {
			$addressId = intval ( Yii::$app->request->post ( 'addressId' ) );
			if ( $addressId > 0 ) {
				$model             = UserAddress::findOne ( $addressId );
				$model->updateTime = $this->time;
			} else {
				$model           = new UserAddress();
				$model->postTime = $this->time;
			}
			$model->userId = Yii::$app->user->id;

			if ( $model->load ( Yii::$app->request->post () ) && $model->save () ) {
				$this->redirect ( [ 'my/address' ] );
			}
		}
		$addressId = intval ( Yii::$app->request->get ( 'addressId' ) );
		if ( $addressId > 0 ) {
			$model = UserAddress::findOne ( $addressId );
		} else {
			$model = new UserAddress();
			$model->isDefault = 1;
		}

		return $this->render ( 'add-address', [ 'model' => $model ] );
	}

	/**
	 * 删除收货地址
	 */
	public function actionDelAddress () {
		$addressId = intval ( Yii::$app->request->get ( 'addressId' ) ); // 收货地址Id
		if ( $addressId < 1 ) {
			throw new NotFoundHttpException( '缺少收货地址参数', 404 );
		}

		$addressInfo = UserAddress::getAddressById ( $addressId, Yii::$app->user->id );
		if ( empty( $addressInfo ) ) {
			throw new NotFoundHttpException( '收货地址不存在', 404 );
		}

		$return = Yii::$app->db->createCommand ()->delete (
			"{{%user_address}}",
			'`id` = ' . $addressId
		)->execute ();
		if ( $return ) {
			$this->redirect ( [ 'my/address' ] );
		} else {
			throw new NotFoundHttpException( '删除收货地址失败', 404 );
		}
	}
}