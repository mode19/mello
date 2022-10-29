<?php

namespace Mode19\Mello\DataModel;

class TrelloBoard extends BaseTrelloEntity
{
    /**
     * @var TrelloList[]
     */
    public $lists;

    public bool $isClosed;

    public function renderRow(bool $isSelected)
    {
        if ($isSelected) {
            printf(" > ");
        }
        printf("%s\n", $this->name);
    }
}