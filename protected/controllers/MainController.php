<?php

class MainController extends BaseController {
	public function actionIndex() {
		$this->render('index');
	}

	public function actionError() {
		$this->layout = 'error';
		if ($error = Yii::app()->errorHandler->error) {
			if (Yii::app()->request->isAjaxRequest) {
				echo $error['message'];
			} else {
				try {
					$this->render("error{$error['code']}", $error);
				} catch (CException $e) {
					$this->render("error", $error);
				}
			}
		}
	}
}
