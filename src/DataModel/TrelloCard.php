<?php

namespace Mode19\Mello\DataModel;

class TrelloCard extends BaseTrelloEntity
{
    const DESC_MAX_LINES = 5;
    /**
     * @var bool
     */
    public bool $isClosed;

    /**
     * @var string
     */
    public string $idList;

    /**
     * @var string
     */
    public string $idBoard;

    /**
     * var
     */
    public string $desc;

    public function renderRow(bool $isSelected)
    {
        if ($isSelected) {
            printf(" > ");
        }
        $closedString = '[ ]';
        if ($this->isClosed) {
            $closedString = '[x]';
        }
        printf("  - %s %s [%s]\n", $closedString, $this->name, $this->dateCreated->format('Y-m-d'));
        if ($isSelected) {
            printf("    ________________________________________________________________________________\n");
            $arr = explode(PHP_EOL, $this->desc, TrelloCard::DESC_MAX_LINES);
            array_pop($arr);
            foreach ($arr as $line) {
                printf("    %s\n", $line);
            }
            if (substr_count($this->desc, PHP_EOL) > TrelloCard::DESC_MAX_LINES) {
                printf("    ...");
            }
            printf("\n    ________________________________________________________________________________\n");
        }
    }
}