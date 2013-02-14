<?php


/**
 * @method Earning findByPk(mixed $pk, mixed $condition = '', array $params = array())
 */
class Earning extends ActiveRecord {
	/** @var int */
	public $id;
	/** @var string */
	public $date;
	/** @var float */
	public $amount;
	/** @var string */
	public $created;

	public function tableName() {
		return 'earning';
	}

	/**
	 * @param string $className
	 * @return Earning
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function rules() {
		return array(
			array('date, amount', 'required'),
			array('amount', 'numerical'),
			array('id, created', 'unsafe'),
			array('amount, date', 'safe'),
		);
	}

	public function attributeLabels() {
		return array(
			'date'   => 'Дата',
			'amount' => 'Сумма',
		);
	}

	public function currentMonth() {
		$this->getDbCriteria()->mergeWith(array(
			'condition' => '`date` >= DATE_FORMAT(NOW(), "%Y-%m-01")',
		));
		return $this;
	}

	public function excludeCurrentMonth() {
		$this->getDbCriteria()->mergeWith(array(
			'condition' => '`date` < DATE_FORMAT(NOW(), "%Y-%m-01")',
		));
		return $this;
	}

	public function groupByMonth() {
		$this->getDbCriteria()->mergeWith(array(
			'group'   => 'DATE_FORMAT(`date`, "%Y-%m")',
			'select' => 'DATE_FORMAT(`date`, "%Y-%m-01") AS date, SUM(amount) AS amount',
		));

		return $this;
	}

	public function summary() {
		$this->getDbCriteria()->mergeWith(array(
			'select' => 'SUM(amount) AS amount',
		));

		return $this;
	}

	public function defaultScope() {
		return array(
			'order' => 'date DESC',
		);
	}
}
