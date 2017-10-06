<?php

namespace Arkemlar\Admin\ThemeBundle\Helper;


use Avanzu\AdminThemeBundle\Routing\RouteAliasCollection;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfigHelper extends \ArrayObject
{
    /**
     * ContextHelper constructor.
     *
     * @param array                $config
     * @param RouteAliasCollection $router
     */
    public function __construct(array $config)
    {
        $this->initialize($config);
    }

    /**
     * @param array $config
     */
    protected function initialize(array $config = [])
    {
        $this->exchangeArray($config);
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->getArrayCopy();
    }

    /**
     * @param $name
     * @param $value
     *
     * @return $this
     */
    public function setOption($name, $value)
    {
        $this->offsetSet($name, $value);
        return $this;
    }

    /**
     * @param $name
     *
     * @return bool
     */
    public function hasOption($name)
    {
        return $this->offsetExists($name);
    }

    /**
     * @param      $name
     * @param null $default
     *
     * @return mixed|null
     */
    public function getOption($name, $default = null)
    {
        return $this->offsetExists($name) ? $this->offsetGet($name): $default;
    }
}