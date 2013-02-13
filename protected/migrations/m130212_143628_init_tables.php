<?php

class m130212_143628_init_tables extends DbMigration {
	public function safeUp() {
		$this->createTable('qiwi_bill', [
			'id'       => 'int(8) unsigned NOT NULL AUTO_INCREMENT',
			'user'     => 'varchar(11) NOT NULL',
			'amount'   => 'float NOT NULL',
			'date'     => 'timestamp NULL DEFAULT NULL',
			'lifetime' => 'timestamp NULL DEFAULT NULL',
			'status'   => 'tinyint(1) unsigned NOT NULL DEFAULT 50', // default status READY (@see EQiwiBill::STATUS_*)
			'created'  => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
			'PRIMARY KEY (id)',
		]);
	}

	public function safeDown() {
		throw new CException("Don't allow downgrade this migration");
	}
}
