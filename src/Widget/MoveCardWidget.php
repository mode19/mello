<?php

namespace Mode19\Mello\Widget;

use Mode19\Mello\DataModel\TrelloBoard;
use Mode19\Mello\DataModel\TrelloCard;
use Mode19\Mello\DataModel\TrelloList;
use Mode19\Mello\Service;
use Mode19\Mello\Utils;

class MoveCardWidget extends BaseWidget
{
    /**
     * @var TrelloBoard[]
     */
    private array $boards;

    /**
     * @var TrelloCard
     */
    private TrelloCard $sourceCard;

    /**
     * @var int
     */
    private int $currentIndex = 0;

    /**
     * @var array
     */
    private array $flattenedList;

    /**
     * @param Service $service
     * @param TrelloBoard[] $boards
     */
    public function __construct(Service $service, array $boards, TrelloCard $sourceCard)
    {
        parent::__construct($service);
        $this->boards = $boards;
        $this->sourceCard = $sourceCard;
        $this->flattenList();
    }

    public function render()
    {
        $this->printMoveCommand();
    }

    public function click(): BaseWidget
    {
        $obj = $this->getSelectedItem();
        if ($obj instanceof TrelloList) {
            echo "moving to list=$obj->id, $obj->idBoard)";
            $this->sourceCard->idList = $obj->id;
            $this->sourceCard->idBoard = $obj->idBoard;;
            $this->service->updateCard($this->sourceCard);
            $this->fetchData();
        }
        return $this;
    }

    public function keyUp()
    {
        if ($this->currentIndex > 0) {
            $this->currentIndex -= 1;
        };
    }

    public function keyDown()
    {
        if ($this->currentIndex < count($this->flattenedList)-1) {
            $this->currentIndex += 1;
        }
    }

    public function keyLeft()
    {
        // TODO: Implement keyLeft() method.
    }

    public function keyRight()
    {
        // TODO: Implement keyRight() method.
    }

    private function getSelectedItem()
    {
        return $this->flattenedList[$this->currentIndex];
    }

    private function flattenList()
    {
        $this->flattenedList = [];
        foreach ($this->boards as $board) {
            $this->flattenedList[] = $board;
            foreach ($board->lists as $list) {
                $this->flattenedList[] = $list;
            }
        }
    }

    private function printMoveCommand()
    {
        $pageSize = Utils::getTerminalRows()-10;
        $currentPage = floor($this->currentIndex / $pageSize);
        $count = $currentPage * $pageSize;
        printf("    - [m]move card\n");
        printf("page %s of %s\n", $currentPage, count($this->flattenedList)/$pageSize);
        $count = 0;
        $count = $currentPage * $pageSize;
        foreach (array_slice($this->flattenedList, $currentPage*$pageSize, $pageSize) as $item) {
            if ($count == $this->currentIndex) {
                printf(">>>");
            }
            if ($item instanceof TrelloBoard) {
                printf("Board[%s] ", $item->name);
            } elseif ($item instanceof TrelloList) {
                printf("  %s ", $item->name);
            }
            $count += 1;
            printf("\n");
        }
    }

    public function fetchData()
    {
        // TODO: Implement fetchData() method.
    }
}