<?php

class QiwiComponent extends CApplicationComponent {
	private $server;
	private $username;
	private $password;

	private $soapClient;

	public function setServer($server) {
		$this->server = $server;
	}

	public function setPassword($password) {
		$this->password = $password;
	}

	public function setUsername($username) {
		$this->username = $username;
	}

	public function calcSignature($txn) {
		// uppercase(md5(txn + uppercase(md5(password))))
		return strtoupper(md5(mb_convert_encoding($txn, 'CP-1251', 'UTF-8') . strtoupper(md5(mb_convert_encoding($this->password, 'CP-1251', 'UTF-8')))));
	}

	public function checkSignature($txn, $username, $signature) {
		$usernameIsCorrect  = ($username == $this->username);
		$signatureIsCorrect = ($signature == $this->calcSignature($txn));
		return $usernameIsCorrect && $signatureIsCorrect;
	}

	public function init() {
		if (!extension_loaded('soap')) {
			throw new CException('SOAP:: You must have SOAP enabled in order to use this extension.');
		}
		if (!extension_loaded('mbstring')) {
			throw new CException('MBSTRING:: You must have mbstring enabled in order to use this extension.');
		}
		if (empty($this->username) || empty($this->password)) {
			throw new CException('Qiwi component not configured');
		}
		parent::init();
	}

	public function getSoapClient() {
		if ($this->soapClient === null) {
			$this->soapClient = new EQiwiSoapClient(array(
				'location' => $this->server,
			));
		}
		return $this->soapClient;
	}

	/**
	 * @param string $txn
	 * @return QiwiBill
	 */
	public function checkBill($txn) {
		return $this->getSoapClient()
			->checkBill(new EQiwiCheckBillRequest($this->username, $this->password, $txn));
	}

	/**
	 * @param string $dateFrom    Дата начала периода
	 * @param string $dateTo      Дата окончания периода
	 * @param int $status         Статус счетов (Для получения счетов всех статусов указать "0")
	 * @return EQiwiBillList
	 */
	public function getBillList($dateFrom, $dateTo, $status = EQiwiBill::STATUS_ALL) {
		return $this->getSoapClient()
			->getBillList(new EQiwiBillListRequest($this->username, $this->password, $dateFrom, $dateTo, $status));
	}

	/**
	 * @param string $txn
	 * @return EQiwiCancelBillResponse
	 */
	public function cancelBill($txn) {
		return $this->getSoapClient()
			->cancelBill(new EQiwiCancelBillRequest($this->username, $this->password, $txn));
	}

	/**
	 * @see QiwiSoapClient::getBillList
	 * @param string $user
	 * @param float $amount
	 * @param string $txn
	 * @param string $comment
	 * @param string $lifetime
	 * @param int $alarm
	 * @param bool $create
	 * @throws CException
	 * @return boolean
	 */
	public function createBill($user, $amount, $txn, $comment, $lifetime = '', $alarm = EQiwiCreateBillRequest::ALARM_NONE, $create = true) {
		return $this->getSoapClient()
			->createBill(new EQiwiCreateBillRequest($this->username, $this->password, $user, $amount, $txn, $comment, $lifetime, $alarm, $create));
	}

	public function createSoapServer($handlerClass) {
		$server = new EQiwiSoapServer($handlerClass, array());
		$server->handle();
		return $server;
	}
}

