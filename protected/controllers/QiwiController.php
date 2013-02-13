<?php

class QiwiController extends BaseController {
//	public function filters() {
//		return array(
//			'postOnly +bill',
//			'ajaxOnly +bill',
//		);
//	}

	public function actionBill() {
		$request = Yii::app()->request;

		$bill             = new QiwiBill();
		$bill->attributes = array(
			'user'   => str_replace([' ', '-'], ['', ''], $request->getPost('user')),
			'amount' => $request->getPost('amount'),
		);

		if ($bill->save()) {
			try {
				Yii::app()->qiwi->createBill($bill->user, $bill->amount, $bill->id, Yii::app()->params->itemAt('qiwiPaymentComment')? : 'Payment on website ' . Yii::app()->name);
				$this->renderJson(array(
					'status' => 'success',
					'id'     => $bill->id,
				));
			} catch (EQiwiSoapException $e) {
				$this->renderJson(array(
					'status' => 'error',
					'errors' => $e->getMessage(),
				));
			}
		}

		$this->renderJson(array(
			'status' => 'error',
			'errors' => $bill->errors,
		));
	}

	public function actionSuccess() {
		$this->render('success');
	}

	public function actionFail() {
		$this->render('fail');
	}

	public function actionCallback() {
		Yii::app()->qiwi->createSoapServer('QiwiSoapHandler');
	}
}

Yii::import('ext.qiwi-soap.*');
Yii::import('ext.qiwi-soap.models.*');
class QiwiSoapHandler implements EIQiwiSoapHandler {
	/**
	 * @param EQiwiInUpdateBillRequest $request
	 * @return EQiwiInUpdateBillResponse
	 */
	function updateBill(EQiwiInUpdateBillRequest $request) {
		if (!Yii::app()->qiwi->checkSignature($request->txn, $request->login, $request->password)) {
			return new EQiwiInUpdateBillResponse(EQiwiHelper::RESPONSE_AUTH_FAILED);
		}

		$bill = QiwiBill::model()->findByPk($request->txn);

		if (!$bill) {
			return new EQiwiInUpdateBillResponse(EQiwiHelper::RESPONSE_BILL_NOT_FOUND);
		}

		Yii::log("Status of bill #{$bill->id} changed from {$bill->status} to {$request->status} by QiwiHandler", CLogger::LEVEL_INFO, 'qiwi');

		$bill->status = $request->status;
		if (!$bill->save()) {
			Yii::log("Can't save bill #{$bill->id} because:\n{$bill->errors}\n\nRequest:\n" . json_encode($request), CLogger::LEVEL_ERROR, 'qiwi');
			return new EQiwiInUpdateBillResponse(EQiwiHelper::RESPONSE_UNKNOWN_ERROR);
		}

		return new EQiwiInUpdateBillResponse(EQiwiHelper::RESPONSE_SUCCESS);
	}
}
