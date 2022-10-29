<?php
namespace Mode19\Mello;

use Flow\JSONPath\JSONPath;
use Mode19\Mello\Client;
use Mode19\Mello\DataModel\TrelloBoard;
use Mode19\Mello\DataModel\TrelloCard;
use Mode19\Mello\DataModel\TrelloList;

require_once './vendor/autoload.php';

class Service {

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param $boardId
     * @return TrelloList[]
     */
    function getListsOnBoard($boardId) : array {
        $lists = $this->client->getListsForBoard($boardId);
        $ret = [];
        foreach($lists as $key => $value) {
            $trelloList = new TrelloList($value->id, $value->name);
            $trelloList->isClosed = $value->closed;
            $trelloList->cards = $this->getCardsInList($value->id);
            $trelloList->idBoard = $boardId;
            $ret[] = $trelloList;
        }
        return $ret;
    }

    /**
     * @param $listId
     * @return TrelloCard[]
     */
    function getCardsInList($listId) : array {
        $res = $this->client->getCardsOnList($listId);
        $ret = [];
        foreach($res as $key => $value) {
            $newCard = new TrelloCard($value->id, $value->name);
            $newCard->isClosed = $value->closed;
            $newCard->desc = $value->desc;
            $newCard->idList = $value->idList;
            $newCard->idBoard = $value->idBoard;
            $ret[] = $newCard;
        }
        return $ret;
    }

    /**
     * @return TrelloBoard[]
     */
    function getMembersBoards() : array {
        $boards = $this->client->getMembersBoards();
        //print_r((new JSONPath($res))->find("$*.name")->getData());
        $ret=[];
        foreach($boards as $key => $value) {
            $newBoard = new TrelloBoard($value->id, $value->name);
            $newBoard->isClosed = $value->closed;
            $newBoard->lists = $this->getListsOnBoard($value->id);
            $ret[] = $newBoard;
        }
        return $ret;
    }

    function updateCard(TrelloCard $card) {
        $this->client->updateCard($card);
    }

    function updateList(TrelloList $list) {
        $this->client->updateList($list);
    }

    public function createCard(string $cardTitle, string $idList)
    {
        $this->client->createCard($cardTitle, $idList);
    }

}


?>
