<?php

class Navigation {
	/** @var Navigation */
	private static $_instance;
	/** @var NavigationMenu[]|\CMap */
	private $_NavigationMenus = array();

	public function __construct() {
		$this->_NavigationMenus = new CMap();
	}

	/**
	 * @return Navigation
	 */
	public static function getInstance() {
		if (self::$_instance === null) {
			self::$_instance = new self;
		}
		return self::$_instance;
	}

	/**
	 * @param string $NavigationMenuId
	 * @return NavigationMenu
	 */
	public static function getMenu($NavigationMenuId) {
		$instance = self::getInstance();

		if (!$instance->_NavigationMenus->contains($NavigationMenuId)) {
			$instance->_NavigationMenus[$NavigationMenuId] = new NavigationMenu($NavigationMenuId);
		}
		return $instance->_NavigationMenus[$NavigationMenuId];
	}

	/**
	 * @param string $NavigationMenuId
	 */
	public static function removeMenu($NavigationMenuId) {
		$instance = self::getInstance();
		if ($instance->_NavigationMenus->contains($NavigationMenuId)) {
			$instance->_NavigationMenus->remove($NavigationMenuId);
		}
	}

	/**
	 * @param string $NavigationMenuId
	 * @param array $options
	 * @param boolean $return
	 * @return string
	 */
	public static function render($NavigationMenuId, array $options = array(), $return = false) {
		if (($NavigationMenu = self::getMenu($NavigationMenuId)) !== null) {
			return $NavigationMenu->render($options, $return);
		}

		return null;
	}
}
