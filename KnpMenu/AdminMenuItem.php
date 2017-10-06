<?php

namespace Arkemlar\Admin\ThemeBundle\KnpMenu;

use Knp\Menu\ItemInterface;

class AdminMenuItem extends \Knp\Menu\MenuItem
{
    /**
     * Bind route to menu item.
     * Routes added via this method resolved later using MenuBuilderHelper::finishMenu method.
     *
     * It enables you the way to add routes which has parameters without actually specifying them - these parameters will be obtained
     * using current request route attributes. If route has required parameters - make item not displayable (displayed => false),
     * than route will be resolved only if item is marked as "current" or belongs to one of current's ancestors chain - very useful for breadcrumbs generation.
     * You can override any route parameter using $routeParameters argument.
     * Item's URI would not be generated if it is already set.
     *
     * For example you have a set of routes:
     * 1) @Route("/products/{id}/edit", name="admin.products.edit")
     * 2) @Route("/products/{id}/parts/{part_id}/edit", name="admin.products.parts.edit")
     * and you have defined items:
     * $prodEdit->setRoute('admin.products.show')->setDisplay(false);
     * $prodPartsEdit->setRoute('admin.products.parts.edit')->setDisplay(false);
     * Then if you open page (1) - $prodEdit menu becomes current and its URI will be generated using current request route attributes (the "id" in this case).
     * And if you open page (2) the same happens with $prodPartsEdit ("id" and "part_id" are taken from current request).
     * BUT!: if $prodEdit is parent for $prodPartsEdit, then $prodEdit's URI will be generated as well since it is ancestor of "current" item.
     * This will work because these two routes has shared "id" parameter.
     *
     * Also, you can add menu item with overridden or extra parameters:
     * @Route("/products/list", name="admin.products.list", default={"count":10})
     * $prodListRecent->setRoute('admin.products.list', ['count' => 3, 'recent' => true]);   // Always show 3 products
     * If you have two items with same route, then the latest (deepest) one wins to be "current", but you can forbid some items to become current:
     * $prodList->setRoute('admin.products.list', [], false);   // Will never be marked as "current"
     *
     *
     * @ Route("/products/{eanCode}/show", name="admin.db.products.show_product")
     *
     * @param string $routeName         Route name
     * @param array  $routeParameters   Overridden or query route parameters
     * @param bool   $isCurrentAllowed  Node will never be marked as "current" if this option set to false
     *
     * @return $this
     */
    public function setRoute(string $routeName, array $routeParameters = [], bool $isCurrentAllowed = true)
    {
        $this->setExtra('_route', [
            'route_name' => $routeName,
            'route_parameters' => $routeParameters,
            'current_allowed' => $isCurrentAllowed,
        ]);
        
        return $this;
    }
    
    /**
     * Add font awesome icon
     *
     * @param string $iconName  It must be "some-name" part in "fa-some-name" icon class
     *
     * @return $this
     */
    public function setIcon(string $iconName)
    {
        $this->setExtra('icon', $iconName);
    
        return $this;
    }
    
    /**
     * Add a child menu item to this menu
     *
     * Returns the child item
     *
     * @param ItemInterface|string $child   An ItemInterface instance or the name of a new item to create
     * @param array                $options If creating a new item, the options passed to the factory for the item
     *
     * @return AdminMenuItem
     * @throws \InvalidArgumentException if the item is already in a tree
     */
    public function addChild($child, array $options = array())
    {
        return parent::addChild($child, $options);  // This method overridden only for proper return value phpdoc typehint
    }
    
    
}
