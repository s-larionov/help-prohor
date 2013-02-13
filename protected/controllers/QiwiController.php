<?php

class QiwiController extends BaseController {
	public function actionBill() {
		/** @var $qiwi QiwiComponent */
		$qiwi = Yii::app()->qiwi;
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
