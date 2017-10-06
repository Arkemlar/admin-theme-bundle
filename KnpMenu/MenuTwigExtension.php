<?php

namespace Arkemlar\Admin\ThemeBundle\KnpMenu;

use Knp\Menu\ItemInterface;
use Knp\Menu\Twig\Helper;
use Knp\Menu\Util\MenuManipulator;
use Knp\Menu\Matcher\MatcherInterface;

class MenuTwigExtension extends \Knp\Menu\Twig\MenuExtension
{
    protected $helper;
    protected $matcher;
    protected $menuManipulator;
    
    /**
     * @param Helper $helper
     */
    public function __construct(Helper $helper, MatcherInterface $matcher = null, MenuManipulator $menuManipulator = null)
    {
        parent::__construct($helper, $matcher, $menuManipulator);   // What a stupid idea to define such properties as private!
        $this->helper = $helper;
        $this->matcher = $matcher;
        $this->menuManipulator = $menuManipulator;
    }
    
    /**
     * Returns the current item of a menu.
     *
     * @param ItemInterface|string $menu
     *
     * @return ItemInterface
     */
    public function getCurrentItem($menu)
    {
        $rootItem = $this->get($menu);
    
        // Customized code is here - we skip a tonn of ineffective inner calls by setting reference to current item directly to menu root
        if($currentItem = $rootItem->getExtra('current_item', false)){
            return $currentItem;
        }

        $currentItem = $this->helper->getCurrentItem($rootItem);

        if (null === $currentItem) {
            $currentItem = $rootItem;
        }

        return $currentItem;
    }
}
