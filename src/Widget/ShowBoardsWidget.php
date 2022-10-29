<?php

namespace Mode19\Mello\Widget;

use Mode19\Mello\Service;
use Mode19\Mello\Utils;

class ShowBoardsWidget extends BaseWidget
{
    /**
     * @var TrelloBoard[]
     */
    private array $boards;
    /**
     * @var int
     */
    private int $currentIndex = 0;

    public function fetchData()
    {
        $this->currentIndex = 0;
        $this->boards = array_values(array_filter($this->service->getMembersBoards(), static function ($element) {
            return !$element->isClosed;
        }));
        Utils::log(print_r($this->boards, true));
    }

    public function render()
    {
        $count = 0;
        printf( "Boards\n");
        foreach ($this->boards as $board) {
            $isSelected = ($count == $this->currentIndex);
            $board->renderRow($isSelected);
            $count += 1;
        }
    }

    public function click() : BaseWidget
    {
        return new ShowListsWidget($this->service, $this->getCurrentBoardId(), $this->boards);
    }

    public function keyUp()
    {
        if ($this->currentIndex > 0) {
            $this->currentIndex -= 1;
        };
    }

    public function keyDown()
    {
        if ($this->currentIndex < count($this->boards)-1) {
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

    /**
     * @return string
     */
    private function getCurrentBoardId() : string {
        return $this->boards[$this->currentIndex]->id;
    }


}