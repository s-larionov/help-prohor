<?php


/**
 * @method QiwiBill findByPk(mixed $pk, mixed $condition = '', array $params = array())
 */
class QiwiBill extends ActiveRecord {
	/** @var int */
	public $id;
	/** @var string */
	public $user;
	/** @var float */
	public $amount;
	/** @var string */
	public $date;
	/** @var string */
	public $lifetime;
	/** @var int */
	public $status;
	/** @var string */
	public $created;

	public function tableName() {
		return 'qiwi_bill';
	}

	/**
	 * @param string $className
	 * @return QiwiBill
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function rules() {
		return array(
			array('user, amount', 'required'),
			array('id, status', 'numerical'),
			array('amount', 'numerical', 'min' => 0.01, 'max' => 15000),
			array('id, created', 'unsafe'),
			array('user', 'match', 'pattern' => '~^\d{10}$~', 'allowEmpty' => false),
			array('user, amount, date, lifetime, status', 'safe'),
		);
	}

	public function attributeLabels() {
		return array(
			'user' => 'Номер телефона',
			'amount' => 'Сумма',
		);
	}
}
