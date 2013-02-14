<?php

class MainController extends BaseController {
	public function actionIndex() {
		$this->render('index', array(
			'needAmount' => Yii::app()->params->itemAt('needAmount'),
			'earnedAmount' => Earning::model()->summary()->find()->amount,
			'currentMonthEarnings' => Earning::model()->currentMonth()->findAll(),
			'earningsGroupedByMonth' => Earning::model()->groupByMonth()->excludeCurrentMonth()->findAll(),
		));
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
