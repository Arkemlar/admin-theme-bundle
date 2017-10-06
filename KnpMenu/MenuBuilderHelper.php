<?php


namespace Arkemlar\Admin\ThemeBundle\KnpMenu;

use Knp\Menu\Factory\ExtensionInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MenuBuilderHelper
{
    /**
     * @var Request
     */
    private $request;
    
    /**
     * @var UrlGeneratorInterface
     */
    private $generator;
    
    /**
     * @var AdminMenuFactory
     */
    protected $factory;
    
    /**
     * MenuBuilderHelper constructor.
     *
     * @param RequestStack $requestStack
     * @param UrlGeneratorInterface $generator
     */
    public function __construct(RequestStack $requestStack, UrlGeneratorInterface $generator)
    {
        $this->request = $requestStack->getMasterRequest();
        $this->generator = $generator;
        $this->factory = new AdminMenuFactory();
        $this->factory->addExtension(new \Knp\Menu\Integration\Symfony\RoutingExtension($this->generator));
    }
    
    public function addExtension(ExtensionInterface $extension, int $priority = 0)
    {
        $this->factory->addExtension($extension, $priority);
    }
    
    /**
     * Use this method before menu building.
     * It will create root menu element using custom factory which creates advanced item objects.
     *
     * @param array $rootMenuOptions
     *
     * @return ItemInterface|AdminMenuItem
     */
    public function startMenu(array $rootMenuOptions = [])
    {
        $menu = $this->factory->createItem('root', $rootMenuOptions);
        
        return $menu;
    }
    
    /**
     * Use this method when menu is built.
     * It will generate URIs for routes added via $item->setRoute(..), define "current" state for each node and find the actual current node.
     *
     * @param AdminMenuItem $menu
     */
    public function finishMenu(AdminMenuItem $menu)
    {
        $currentRoute = $this->request->attributes->get('_route');
        // Create the iterator
        $itemIterator = new \Knp\Menu\Iterator\RecursiveItemIterator($menu);
        $iterator = new \RecursiveIteratorIterator($itemIterator, \RecursiveIteratorIterator::SELF_FIRST);
        // Iterate recursively on the iterator to find current menu item
        $currentItem = null;
        /** @var AdminMenuItem $item */
        foreach ($iterator as $item) {
            $extra = $item->getExtra('_route', false);   // '_route' extra is set when $item->setRoute(..) is called
            if(!$extra){
                $item->setCurrent(false);
                continue;
            }
            // Generate route added with $item->setRoute(..)
            if(null === $item->getUri() && $item->isDisplayed()){
                $this->generateUri($item);
            }
            // Determine "current" state
            if($extra['route_name'] == $currentRoute && $extra['current_allowed']){
                $currentItem = $item;
            } else {
                $item->setCurrent(false);
            }
        }
        if(!$currentItem)
            throw new \LogicException(sprintf('Menu item for current route "%s" missing', $currentRoute));
        
        $menu->setExtra('current_item', $currentItem);  // We already know current node, lets save it to use in Twig's knp_menu_get_current_item() function modified in custom MenuTwigExtension
        $currentItem->setCurrent(true); // This will be used by matcher while searching for current item
    
        // Generate URLs for current element ancestors
        while ($currentItem && null === $currentItem->getUri() && $currentItem->getExtra('_route', false)){
            $this->generateUri($currentItem);
            $currentItem = $currentItem->getParent();
        }
    }
    
    /**
     * @param AdminMenuItem $item
     */
    protected function generateUri(AdminMenuItem $item)
    {
        $params = array_merge(
            $this->request->attributes->get('_route_params'),
            $item->getExtra('_route')['route_parameters']
        );
        $item->setUri($this->generator->generate($item->getExtra('_route')['route_name'], $params));
    }
    
    
}