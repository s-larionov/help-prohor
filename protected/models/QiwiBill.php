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
			array('id, amount, status', 'numerical'),
			array('id, created', 'unsafe'),
			array('user, amount, date, lifetime, status', 'safe'),
		);
	}
}
