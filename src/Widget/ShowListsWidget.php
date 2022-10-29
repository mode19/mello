<?php

namespace Mode19\Mello\Widget;

use Mode19\Mello\DataModel\TrelloCard;
use Mode19\Mello\DataModel\TrelloList;
use Mode19\Mello\Service;
use Mode19\Mello\Utils;
use Mode19\Mello\Widget\interfaces\WidgetListInterface;

class ShowListsWidget extends BaseWidget implements WidgetListInterface
{
    /**
     * @var TrelloBoard[]
     */
    private array $boards;

    /**
     * @var TrelloList[]
     */
    private array $lists;

    /**
     * @var string
     */
    private string $boardId = '';

    /**
     * @var int
     */
    private int $currentIndex = 0;

    /**
     * @var array (TrelloList|TrelloCard)[]
     */
    private array $flattenedList;

    /**
     * @var array (TrelloCard)[]
     */
    private array $archivedList;

    /**
     * @param Service $service
     * @param String $boardId
     * @param TrelloBoard[] $boards
     */
    public function __construct(Service $service, string $boardId, array $boards)
    {
        parent::__construct($service);
        $this->boardId = $boardId;
        $this->boards = $boards;
    }

    public function fetchData()
    {
        //$this->currentIndex = 0;
        $this->lists = $this->service->getListsOnBoard($this->boardId);
        $this->flattenList();
    }

    public function render()
    {
        $this->renderListsAndStories(false);
    }

    public function click() : BaseWidget
    {
        Utils::clear();
        $this->renderListsAndStories(true);
        $key = Utils::getKey();
        switch(strtolower($key)) {
            case 'j' :
                echo "move down not yet supported";
                break;
            case 'k' :
                echo "move up not yet supported";
                break;
            case 'c' :
                $obj = $this->getSelectedItem();
                if($obj instanceof TrelloCard) {
                    $obj->isClosed = true;
                    $this->service->updateCard($obj);
                    $this->fetchData();
                }
                break;
            case 'e' :
                $obj = $this->getSelectedItem();
                if($obj instanceof TrelloCard) {
                    $this->editCardExternally($obj);
                    $this->service->updateCard($obj);
                    $this->fetchData();
                } elseif($obj instanceof TrelloList) {
                    $this->editListExternally($obj);
                    $this->service->updateList($obj);
                    $this->fetchData();
                }
                break;
            case 'm' :
                $obj = $this->getSelectedItem();
                if($obj instanceof TrelloCard) {
                    return new MoveCardWidget($this->service, $this->boards, $obj);
                }
                $this->fetchData();
                break;
            case 'a' :
                $obj = $this->getSelectedItem();
                $cardTitle = readline("Enter Title:");
                if($obj instanceof TrelloCard) {
                    $this->service->createCard($cardTitle, $obj->idList);
                } elseif( $obj instanceof TrelloList) {
                    $this->service->createCard($cardTitle, $obj->id);
                }
                $this->fetchData();
                break;
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

    private function renderArchivedList() {
        printf("Archived\n");
        foreach ($this->archivedList as $obj) {
            $obj->renderRow(false);
        }
    }

    private function getSelectedItem() {
        return $this->flattenedList[$this->currentIndex];
    }

    private function flattenList()
    {
        $this->flattenedList = [];
        $this->archivedList = [];
        foreach ($this->lists as $list) {
            if($list->isClosed) {
                foreach ($list->cards as $card) {
                    $this->archivedList[] = $card;
                }
            } else {
                $this->flattenedList[] = $list;
            }
            foreach ($list->cards as $card) {
                if($card->isClosed) {
                    $this->archivedList[] = $card;
                } else {
                    $this->flattenedList[] = $card;
                }
            }
        }
    }

    private function renderListsAndStories($showMenu)
    {
        $pageSize = Utils::getTerminalRows()-10;
        $currentPage = floor($this->currentIndex / $pageSize);
        printf("page %s of %s\n", $currentPage, count($this->flattenedList)/$pageSize);
        $count = $currentPage * $pageSize;
        foreach (array_slice($this->flattenedList, $currentPage*$pageSize, $pageSize) as $obj) {
            $isSelected = ($count == $this->currentIndex);
            $obj->renderRow($isSelected);
            if($showMenu && $isSelected) {
                $this->printCommands();
            }
            $count += 1;
            if($count >= ($currentPage+1)*($pageSize)) {
                return;
            }
        }
        $this->renderArchivedList();
    }

    private function printCommands() {
        printf("    - [c]lose this card\n");
        printf("    - [e]dit in VIM\n");
        printf("    - move [j]own 1\n");
        printf("    - move [k]p 1\n");
        printf("    - [m]move to a differt board/list\n");
        printf("    - [a]add new card here\n");
    }

    private function editCardExternally(TrelloCard $obj)
    {
        file_put_contents('tmp_edit_buffer.txt', sprintf( "|Name|:%s\n|Desc|:%s", $obj->name, $obj->desc));
        system("vim tmp_edit_buffer.txt > `tty`");
        $newFile = file_get_contents('tmp_edit_buffer.txt');
        $arr = preg_split('/(\|Name\|:|\n\|Desc\|:)/', $newFile);
        //print_r($arr);
        $obj->name = $arr[1];
        $obj->desc = $arr[2];
    }

    private function editListExternally(TrelloList $obj)
    {
        file_put_contents('tmp_edit_buffer.txt', sprintf( "|Name|:%s", $obj->name));
        system("vim tmp_edit_buffer.txt > `tty`");
        $newFile = file_get_contents('tmp_edit_buffer.txt');
        $arr = preg_split('/\|Name\|:/', $newFile);
        //print_r($arr);
        $obj->name = $arr[1];
    }

    public function addItem(): void
    {
        // TODO: Implement addItem() method.
    }

    public function removeItem(): void
    {
        // TODO: Implement removeItem() method.
    }

    public function getTotalItems(): int
    {
        // TODO: Implement getTotalItems() method.
    }

    public function getCurrentSelectedItem(): int
    {
        // TODO: Implement getCurrentSelectedItem() method.
    }

    public function getItems(): array
    {
        // TODO: Implement getItems() method.
    }

    public function getCurrentPage()
    {
        // TODO: Implement getCurrentPage() method.
    }

    public function getTotalPages()
    {
        // TODO: Implement getTotalPages() method.
    }
}