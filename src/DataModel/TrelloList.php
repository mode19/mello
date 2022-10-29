<?php

namespace Mode19\Mello\DataModel;

class TrelloList extends BaseTrelloEntity
{
    /**
     * @var TrelloCard[]
     */
    public array $cards;

    public bool $isClosed;

    /**
     * @var string
     */
    public string $idBoard;

    public function renderRow(bool $isSelected)
    {
        if ($isSelected) {
            printf(" > ");
        }
        printf("%s\n", $this->name);
    }
}