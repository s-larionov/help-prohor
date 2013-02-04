<?php

/**
 * @property string id
 * @property string[] htmlOptions
 * @property NavigationMenuItem[] items
 * @property NavigationMenuItem[] visibleItems
 * @property boolean isEmpty
 */
class NavigationMenu extends CComponent {
	/** @var string */
	protected $_id;
	/** @var \CMap|NavigationMenuItem[] */
	protected $_items;
	/** @var string[] */
	protected $_htmlOptions = array();
	/** @var string */
	protected $_widget = 'zii.widgets.CMenu';
	/** @var array */
	protected $_widgetOptions = array();

	public function __construct($id, $htmlOptions = array()) {
		$this->setId($id);
		$this->setHtmlOptions($htmlOptions);
		$this->_items = new CMap();
		$this->init();
	}

	public function init() {
	}

	/**
	 * @return string
	 */
	public function getId() {
		return $this->_id;
	}

	/**
	 * @return array
	 */
	public function getHtmlOptions() {

		return $this->_htmlOptions;
	}

	public function setId($id) {
		if (!is_string($id)) {
			throw new CException(Yii::t('navigation', 'The id must be a string'));
		}
		$this->_id = $id;
		return $this;
	}

	/**
	 * @param array $htmlOptions
	 * @param bool $mergeOld
	 * @return Menu
	 * @throws CException
	 */
	public function setHtmlOptions(array $htmlOptions, $mergeOld = false) {
		if ($mergeOld) {
			$this->_htmlOptions = CMap::mergeArray($this->_htmlOptions, $htmlOptions);
		} else {
			$this->_htmlOptions = $htmlOptions;
		}
		return $this;
	}

	/**
	 * @param array $itemData
	 * @return \NavigationMenu
	 * @throws CException
	 */
	public function addItem($itemData) {
		if (isset($itemData['id'], $itemData['label'])) {
			$id          = $itemData['id'];
			$label       = $itemData['label'];
			$url         = isset($itemData['url'])? $itemData['url']: '#';
			$weight      = isset($itemData['weight'])? $itemData['weight']: 0;
			$htmlOptions = isset($itemData['htmlOptions'])? $itemData['htmlOptions']: array();
			$linkOptions = isset($itemData['linkOptions'])? $itemData['linkOptions']: array();
			$visible     = isset($itemData['visible'])? $itemData['visible']: true;
			$active      = isset($itemData['active'])? $itemData['active']: false;

			$this->_items->add($id, new NavigationMenuItem($id, $label, $url, $weight, $htmlOptions, $linkOptions, $active, $visible));
		} else {
			throw new CException(Yii::t('navigation', 'The item id and label must at least be specified'));
		}
		return $this;
	}

	/**
	 * @param array[] $items
	 * @return \NavigationMenu
	 */
	public function addItems($items) {
		foreach ($items as $itemData) {
			$this->addItem($itemData);
		}
		return $this;
	}

	/**
	 * @param string $itemId
	 * @return \NavigationMenu
	 */
	public function removeItem($itemId) {
		if ($this->_items->contains($itemId)) {
			$this->_items->remove($itemId);
		}
		return $this;
	}

	/**
	 * @return CMap|NavigationMenuItem[]
	 */
	public function getItems() {
		return $this->_items;
	}

	/**
	 * @param string $itemId
	 * @return NavigationMenuItem|null
	 */
	public function getItem($itemId) {
		return $this->_items->contains($itemId)? $this->_items[$itemId]: null;
	}

	/**
	 * @param $itemId
	 * @return bool
	 */
	public function hasItem($itemId) {
		return $this->getItem($itemId) !== null;
	}

	/**
	 * @return bool
	 */
	public function getIsEmpty() {
		$count = 0;
		foreach ($this->_items as $item) {
			if ($item->getVisible()) {
				$count++;
			}
		}
		return $count === 0;
	}

	/**
	 * @return CMap
	 */
	public function getVisibleItems() {
		$visibleItems = new CMap();

		foreach ($this->getItems() as $id => $item) {
			if ($item->getVisible() === true) {
				$visibleItems->add($id, $item);
			}
		}
		return $visibleItems;
	}

	/**
	 * @param NavigationMenuItem $itemA
	 * @param NavigationMenuItem $itemB
	 * @return int
	 * @throws CException
	 */
	protected function compareItems($itemA, $itemB) {
		if ($itemA instanceof NavigationMenuItem && $itemB instanceof NavigationMenuItem) {
			if ($itemA->getWeight() === $itemB->getWeight()) {
				return 0;
			}
			return ($itemA->getWeight() <= $itemB->getWeight())? -1: 1;
		} else {
			throw new CException(Yii::t('navigation', 'Both items must be instances of NavigationMenuItem or one of it\'s children.'));
		}
	}

	/**
	 * @param CMap $items
	 * @return CMap
	 */
	protected function sortItems($items) {
		$items = $items->toArray();

		uasort($items, array($this, 'compareItems'));

		return new CMap($items);
	}

	/**
	 * @param array $widgetOptions
	 * @param bool $return
	 * @return string
	 */
	public function render(array $widgetOptions = array(), $return = false) {
		$widgetOptions = array_merge(
			$this->_widgetOptions,
			array('htmlOptions' => $this->getHtmlOptions()),
			$widgetOptions,
			array('items' => $this->getPreparedItems()));

		return Yii::app()->controller
			->widget($this->widget, $widgetOptions, $return);
	}

	protected function getPreparedItems() {
		$items = array();
		foreach($this->getItems() as $item) {
			if (!$item->visible) {
				continue;
			}

			$items[] = array(
				'url' => $item->getUrl(),
				'label' => $item->getLabel(),
				'active' => $item->getActive()? true: null,
				'htmlOptions' => $item->getHtmlOptions(),
				'linkOptions' => $item->getLinkOptions(),
			);
		}
		return $items;
	}

	/**
	 * @param string $widget
	 * @param array $options
	 * @return \NavigationMenu
	 */
	public function setWidget($widget, array $options = array()) {
		$this->_widget = (string) $widget;
		$this->_widgetOptions = $options;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getWidget() {
		return $this->_widget;
	}
}
