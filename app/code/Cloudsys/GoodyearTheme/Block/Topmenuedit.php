<?php
namespace Cloudsys\GoodyearTheme\Block;
use Magento\Theme\Block\Html\Topmenu;
use Magento\Framework\Data\Tree\NodeFactory;
use Magento\Framework\Data\TreeFactory;
use Magento\Framework\View\Element\Template\Context;
use Magento\Backend\Model\Menu;
use Magento\Framework\Data\Tree\Node;
use Magento\Framework\Data\Tree\Node\Collection;
use Magento\Framework\DataObject;
use Magento\Framework\DataObject\IdentityInterface;

class Topmenuedit extends Topmenu
{ 
    
    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollection,
        Context $context,
        NodeFactory $nodeFactory,
        TreeFactory $treeFactory,
        array $data = []
    ) {
        $this->_categoryCollection=$categoryCollection;
        $this->nodeFactory = $nodeFactory;
        $this->treeFactory = $treeFactory;
        parent::__construct($context,$nodeFactory,$treeFactory,$data);
    }

   /**
     * Get block cache life time
     *
     * @return int
     * @since 100.1.0
     */
    protected function getCacheLifetime()
    {
        return parent::getCacheLifetime() ?: 3600;
    }

    /**
     * Get top menu html
     *
     * @param string $outermostClass
     * @param string $childrenWrapClass
     * @param int $limit
     * @return string
     */
    public function getHtml($outermostClass = '', $childrenWrapClass = '', $limit = 0)
    {
        $this->_eventManager->dispatch(
            'page_block_html_topmenu_gethtml_before',
            ['menu' => $this->getMenu(), 'block' => $this, 'request' => $this->getRequest()]
        );

        $this->getMenu()->setOutermostClass($outermostClass);
        $this->getMenu()->setChildrenWrapClass($childrenWrapClass);

        $transportObject = new DataObject(
            [
                'html' => $this->_getHtml(
                    $this->getMenu(),
                    $childrenWrapClass,
                    $limit
                )
            ]
        );

        $this->_eventManager->dispatch(
            'page_block_html_topmenu_gethtml_after',
            ['menu' => $this->getMenu(), 'transportObject' => $transportObject]
        );

        return $transportObject->getHtml();
    }

    /**
     * Count All Subnavigation Items
     *
     * @param Menu $items
     * @return int
     */
    protected function _countItems($items)
    {
        $total = $items->count();
        foreach ($items as $item) {
            /** @var $item Menu\Item */
            if ($item->hasChildren()) {
                $total += $this->_countItems($item->getChildren());
            }
        }
        return $total;
    }

    /**
     * Building Array with Column Brake Stops
     *
     * @param Menu $items
     * @param int $limit
     * @return array|void
     *
     * @todo: Add Depth Level limit, and better logic for columns
     */
    protected function _columnBrake($items, $limit)
    {
        $total = $this->_countItems($items);
        if ($total <= $limit) {
            return;
        }

        $result[] = ['total' => $total, 'max' => (int)ceil($total / ceil($total / $limit))];

        $count = 0;
        $firstCol = true;

        foreach ($items as $item) {
            $place = $this->_countItems($item->getChildren()) + 1;
            $count += $place;

            if ($place >= $limit) {
                $colbrake = !$firstCol;
                $count = 0;
            } elseif ($count >= $limit) {
                $colbrake = !$firstCol;
                $count = $place;
            } else {
                $colbrake = false;
            }

            $result[] = ['place' => $place, 'colbrake' => $colbrake];

            $firstCol = false;
        }

        return $result;
    }

    /**
     * Add sub menu HTML code for current menu item
     *
     * @param Node $child
     * @param string $childLevel
     * @param string $childrenWrapClass
     * @param int $limit
     * @return string HTML code
     */
    protected function _addSubMenu($child, $childLevel, $childrenWrapClass, $limit)
    {
        $html = '';
        if (!$child->hasChildren()) {
            return $html;
        }

        $colStops = [];
        if ($childLevel == 0 && $limit) {
            $colStops = $this->_columnBrake($child->getChildren(), $limit);
        }

        $html .= '<ul class="level' . $childLevel . ' ' . $childrenWrapClass . '">';
        $html .= $this->_getHtml($child, $childrenWrapClass, $limit, $colStops);
        $html .= '</ul>';

        return $html;
    }

    /**
     * Recursively generates top menu html from data that is specified in $menuTree
     *
     * @param Node $menuTree
     * @param string $childrenWrapClass
     * @param int $limit
     * @param array $colBrakes
     * @return string
     */
    protected function _getHtml(
        Node $menuTree,
        $childrenWrapClass,
        $limit,
        array $colBrakes = []
    ) {
        $html = '';

        $children = $menuTree->getChildren();
        $childLevel = $this->getChildLevel($menuTree->getLevel());
        $this->removeChildrenWithoutActiveParent($children, $childLevel);

        $counter = 1;
        $childrenCount = $children->count();

        $parentPositionClass = $menuTree->getPositionClass();
        $itemPositionClassPrefix = $parentPositionClass ? $parentPositionClass . '-' : 'nav-';

        $iflevel = true;

        /** @var Node $child */
        foreach ($children as $child) {
            $child->setLevel($childLevel);
            $child->setIsFirst($counter === 1);
            $child->setIsLast($counter === $childrenCount);
            $child->setPositionClass($itemPositionClassPrefix . $counter);

            
           /* if ($child->getName() == "Pre-order Products") {
                $html .= "<li class=\"level0 nav-5 category-item level-top ui-menu-item\">";
                $html .= "<a href=\"" .$this->getBaseUrl(). @noEscape  __("new-items") . "\" class=\"level-top ui-corner-all\"><span class=\"ui-menu-icon ui-icon ui-icon-carat-1-e\"></span><span>" . __("New Item") . "</span></a>";
                $html .= "</li>";
            }*/
            $outermostClassCode = '';
            $outermostClass = $menuTree->getOutermostClass();

            if ($childLevel === 0 && $outermostClass) {
                $outermostClassCode = ' class="' . $outermostClass . '" ';
                $this->setCurrentClass($child, $outermostClass);
            }

            if ($this->shouldAddNewColumn($colBrakes, $counter)) {
                $html .= '</ul></li><li class="column"><ul>';
            }

            if($iflevel == true && $childLevel == 1)
            {
                $html .= '<li class="level1 nav-1-0 category-item ui-menu-item">';
                $html .= '<a href="' . $this->getBaseUrl().'products/industrial' . '" ' . $outermostClassCode . '><span>' . $this->escapeHtml('Industrial') . '</span></a>' . $this->_addSubMenu(
                    $child,
                    $childLevel,
                    $childrenWrapClass,
                    $limit
                ) . '</li>';
                $iflevel = false;
                $counter++;
            }


            $html .= '<li ' . $this->_getRenderedMenuItemAttributes($child) . '>';
            if ($child->getName() == "Products") {

                
                $html .= '<a href="' . $this->getBaseUrl().'products/industrial' . '" ' . $outermostClassCode . '><span>' . $this->escapeHtml(
                    $child->getName()
                ) . '</span></a>' . $this->_addSubMenu(
                    $child,
                    $childLevel,
                    $childrenWrapClass,
                    $limit
                ) . '</li>';
            }
            else
            {

                $html .= '<a href="' . $child->getUrl() . '" ' . $outermostClassCode . '><span>' . $this->escapeHtml(
                    $child->getName()
                ) . '</span></a>' . $this->_addSubMenu(
                    $child,
                    $childLevel,
                    $childrenWrapClass,
                    $limit
                ) . '</li>';
            }

            $counter++;
        }

        if (is_array($colBrakes) && !empty($colBrakes) && $limit) {
            $html = '<li class="column"><ul>' . $html . '</ul></li>';
        }

        return $html;
    }

    /**
     * Generates string with all attributes that should be present in menu item element
     *
     * @param Node $item
     * @return string
     */
    protected function _getRenderedMenuItemAttributes(Node $item)
    {
        $html = '';
        foreach ($this->_getMenuItemAttributes($item) as $attributeName => $attributeValue) {
            $html .= ' ' . $attributeName . '="' . str_replace('"', '\"', $attributeValue) . '"';
        }
        return $html;
    }

    /**
     * Returns array of menu item's attributes
     *
     * @param Node $item
     * @return array
     */
    protected function _getMenuItemAttributes(Node $item)
    {
        return ['class' => implode(' ', $this->_getMenuItemClasses($item))];
    }

    /**
     * Returns array of menu item's classes
     *
     * @param Node $item
     * @return array
     */
    protected function _getMenuItemClasses(Node $item)
    {
        $classes = [
            'level' . $item->getLevel(),
            $item->getPositionClass(),
        ];

        if ($item->getIsCategory()) {
            $classes[] = 'category-item';
        }

        if ($item->getIsFirst()) {
            $classes[] = 'first';
        }

        if ($item->getIsActive()) {
            $classes[] = 'active';
        } elseif ($item->getHasActive()) {
            $classes[] = 'has-active';
        }

        if ($item->getIsLast()) {
            $classes[] = 'last';
        }

        if ($item->getClass()) {
            $classes[] = $item->getClass();
        }

        if ($item->hasChildren()) {
            $classes[] = 'parent';
        }

        return $classes;
    }

    /**
     * Add identity
     *
     * @param string|array $identity
     * @return void
     */
    public function addIdentity($identity)
    {
        if (!in_array($identity, $this->identities)) {
            $this->identities[] = $identity;
        }
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return $this->identities;
    }

    /**
     * Get tags array for saving cache
     *
     * @return array
     * @since 100.1.0
     */
    protected function getCacheTags()
    {
        return array_merge(parent::getCacheTags(), $this->getIdentities());
    }

    /**
     * Get menu object.
     *
     * Creates Tree root node object.
     * The creation logic was moved from class constructor into separate method.
     *
     * @return Node
     * @since 100.1.0
     */
    public function getMenu()
    {
        if (!$this->_menu) {
            $this->_menu = $this->nodeFactory->create(
                [
                    'data' => [],
                    'idField' => 'root',
                    'tree' => $this->treeFactory->create()
                ]
            );
        }
        return $this->_menu;
    }

    /**
     * Remove children from collection when the parent is not active
     *
     * @param Collection $children
     * @param int $childLevel
     * @return void
     */
    private function removeChildrenWithoutActiveParent(Collection $children, int $childLevel): void
    {
        /** @var Node $child */
        foreach ($children as $child) {
            if ($childLevel === 0 && $child->getData('is_parent_active') === false) {
                $children->delete($child);
            }
        }
    }

    /**
     * Retrieve child level based on parent level
     *
     * @param int $parentLevel
     *
     * @return int
     */
    private function getChildLevel($parentLevel): int
    {
        return $parentLevel === null ? 0 : $parentLevel + 1;
    }

    /**
     * Check if new column should be added.
     *
     * @param array $colBrakes
     * @param int $counter
     * @return bool
     */
    private function shouldAddNewColumn(array $colBrakes, int $counter): bool
    {
        return count($colBrakes) && $colBrakes[$counter]['colbrake'];
    }

    /**
     * Set current class.
     *
     * @param Node $child
     * @param string $outermostClass
     */
    private function setCurrentClass(Node $child, string $outermostClass): void
    {
        $currentClass = $child->getClass();
        if (empty($currentClass)) {
            $child->setClass($outermostClass);
        } else {
            $child->setClass($currentClass . ' ' . $outermostClass);
        }
    }
}
