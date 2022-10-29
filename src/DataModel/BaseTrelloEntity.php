<?php

namespace Mode19\Mello\DataModel;

use DateTime;
use Mode19\Mello\Utils;

abstract class BaseTrelloEntity
{
    public string $name;
    public string $id;
    public \DateTime $dateCreated;

    /**
     * @param string $name
     */
    public function __construct(string $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
        $this->dateCreated = Utils::getDateFromId($id);
    }

    public abstract function renderRow(bool $isSelected);


}