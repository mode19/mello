<?php
namespace Mode19\Mello\Widget\interfaces;

interface WidgetListInterface {
    public function addItem() : void;
    public function removeItem() : void;
    public function getTotalItems() : int;
    public function getCurrentSelectedItem() : int;
    public function getItems() : array;
    public function getCurrentPage();
    public function getTotalPages();
}
