<?php

namespace Mode19\Mello\Widget;

use Mode19\Mello\Service;

abstract class BaseWidget
{
    /**
     * @var Service
     */
    protected $service;

    /**
     * @var BaseWidget
     */
    public array $elements;

    /**
     * @param Service $service
     */
    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    function clear()
    {
        print("\033[2J\033[;H");
    }

    public abstract function fetchData();

    public abstract function render();

    public abstract function click() : BaseWidget;

    public abstract function keyUp();

    public abstract function keyDown();

    public abstract function keyLeft();

    public abstract function keyRight();

}