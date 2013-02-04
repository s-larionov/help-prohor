<?php

/**
 * @property string label
 * @property string|array url
 * @property int weight
 * @property array linkOptions
 * @property boolean visible
 */
class NavigationMenuItem extends NavigationMenu {
	/** @var string */
	protected $_label;
	/** @var string|array */
	protected $_url;
	/** @var int */
	protected $_weight;
	/** @var array */
	protected $_linkOptions = array();
	/** @var boolean */
	protected $_visible;
	/** @var boolean */
	protected $_active;

	/**
	 * @param string $id
	 * @param string $label
	 * @param string|array $url
	 * @param int $weight
	 * @param array $htmlOptions
	 * @param array $linkOptions
	 * @param bool $active
	 * @param bool $visible
	 */
	public function __construct($id, $label, $url = '#', $weight = 0, $htmlOptions = array(), $linkOptions = array(), $active = false, $visible = true) {
		$this->setId($id);
		$this->setLabel($label);
		$this->setUrl($url);
		$this->setWeight($weight);
		$this->setHtmlOptions($htmlOptions);
		$this->setLinkOptions($linkOptions);
		$this->setActive($active);
		$this->setVisible($visible);

		$this->_items = new CMap();

		$this->init();
	}

	public function init() {
		parent::init();
	}

	/**
	 * @return string
	 */
	public function getLabel() {
		return $this->_label;
	}

	/**
	 * @return array|string
	 */
	public function getUrl() {
		return $this->_url;
	}

	/**
	 * @return int
	 */
	public function getWeight() {
		return $this->_weight;
	}

	/**
	 * @return array
	 */
	public function getLinkOptions() {
		return $this->_linkOptions;
	}

	/**
	 * @return bool
	 */
	public function getVisible() {
		return $this->_visible;
	}

	/**
	 * @return bool
	 */
	public function getActive() {
		return $this->_active;
	}

	/**
	 * @param string $label
	 * @return NavigationMenuItem
	 * @throws CException
	 */
	public function setLabel($label) {
		if (!is_string($label)) {
			throw new CException(Yii::t('navigation', 'The item label must be a string.'));
		}
		$this->_label = $label;
		return $this;
	}

	/**
	 * @param string|array $url
	 * @return NavigationMenuItem
	 * @throws CException
	 */
	public function setUrl($url) {
		if (!(is_array($url) || is_string($url))) {
			throw new CException(Yii::t('navigation', 'The item url must be a string or an array.'));
		}
		$this->_url = $url;
		return $this;
	}

	/**
	 * @param int $weight
	 * @return NavigationMenuItem
	 * @throws CException
	 */
	public function setWeight($weight) {
		if (!is_numeric($weight)) {
			throw new CException(Yii::t('navigation', 'The item weight must be a number.'));
		}
		$this->_weight = (int) $weight;
		return $this;
	}

	/**
	 * @param array $linkOptions
	 * @param bool $mergeOld
	 * @return NavigationMenuItem
	 * @throws CException
	 */
	public function setLinkOptions(array $linkOptions, $mergeOld = false) {
		if ($mergeOld) {
			$this->_linkOptions = CMap::mergeArray($this->_linkOptions, $linkOptions);
		} else {
			$this->_linkOptions = $linkOptions;
		}
		return $this;
	}

	/**
	 * @param boolean $visible
	 * @return NavigationMenuItem
	 * @throws CException
	 */
	public function setVisible($visible) {
		if (!is_bool($visible)) {
			throw new CException(Yii::t('navigation', 'The item visibility must be set to a boolean value.'));
		}
		$this->_visible = $visible;
		return $this;
	}

	/**
	 * @param boolean $active
	 * @return NavigationMenuItem
	 * @throws CException
	 */
	public function setActive($active) {
		if (!is_bool($active)) {
			throw new CException(Yii::t('navigation', 'The item active flag must be set to a boolean value.'));
		}
		$this->_active = $active;
		return $this;
	}
}
