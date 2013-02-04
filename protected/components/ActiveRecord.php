<?php

class ActiveRecord extends CActiveRecord {
	protected $alias;

	public function all() {
		$this->resetScope();
		return $this;
	}

	public function getTableAlias($quote = false, $checkScopes = true) {
		$criteria = $this->getDbCriteria();
		if ($criteria->alias) {
			return $criteria->alias;
		}
		$defaultScope = $this->defaultScope();
		if (is_array($defaultScope) && isset($defaultScope['alias'])) {
			return $defaultScope['alias'];
		}
		if ($this->alias === null) {
			$this->alias = strtolower(get_class($this));
		}
		return $quote? $this->getDbConnection()->getSchema()->quoteTableName($this->alias): $this->alias;
	}

	public function setAttributes($values, $safeOnly = true) {
		if (!is_array($values)) {
			return;
		}
		$attributes = array_flip($safeOnly? $this->getSafeAttributeNames(): $this->attributeNames());
		foreach ($values as $name => $value) {
			$nameRest = substr($name, 1);
			$func     = 'set' . strtoupper($name[0]) . $nameRest;
			if (method_exists($this, $func)) {
				$this->$func($value);
			} else {
				if (isset($attributes[$name])) {
					$this->$name = $value;
				} else if ($safeOnly) {
					$this->onUnsafeAttribute($name, $value);
				}
			}
		}
	}
}
