<?php
namespace Mode19\Mello\Tests\TestData;

use Mode19\Mello\DataModel\TrelloBoard;
use Mode19\Mello\DataModel\TrelloCard;
use Mode19\Mello\DataModel\TrelloList;

class GenerateTestData
{
    const ids = [
        "61905462ee2ad442c417e4ac",
        "61905462ee2ad442c417e4ab",
        "61afc0a770eb892f1fa66097",
        "624666d046e8de25a2c7b006",
    ];

    const moreIds = [
        "6190797819ac223787492e3e",
        "61905f1509bfb13fab13fc06",
        "619060b304080246207c148b",
        "6195663241a61a2f99d3d8fb",
        "61afc0b6231e6857125c68fb",
        "61afc0bbb361650a943b06c0",
        "61cd21fb61a44c5da520f575",
        "62085e9d726ed907e89fc94e",
        "62086c88452ce67757ee9e41",
        "624666d73ffe156e5c85d572",
        "624667183d40964cab1b4550",
        "624667e3ae12210de96020c8",
        "624669c02e361208b8c51d83",
        "6250ff9ce108d262011fbfb4",
        "62533f12748e87111ebc1313",
        "62588a0cee4a4a0cb54c12bc",
        "625f7f027f204f4f786b1413",
        "626802f38478c0692671300d",
        "628059d8ac69e27203d39d00",
        "6292dfe8f764170baf8ec635",
        "62a25dd6b5cdf18aa83a2f5b",
        "62b07f523f88a77473202bb4",
        "63435534a9c8140102d73caf",
        "6343561a95df3800e7f40de6",
        "6344caf965e8ea00aae3194f",
        "6344cb14fc853e01f3f1d373",
        "6344cb1b97f8d70499ee6476",
        "6344cb3c3e7a2400a0ed7ee3",
        "6344cd8003470903302d8542",
    ];

    /**
     * @return TrelloBoard[]
     */
    public static function getBoardList() : array {
        $boardId = '624666d046e8de25a2c7b006';
        $listOfBoards = [];
        $count = 0;
        foreach(GenerateTestData::ids as $id) {
            $trelloBoard = new TrelloBoard($boardId, "Board Name $count");
            $trelloBoard->lists = GenerateTestData::getListList();
            $listOfBoards[] = $trelloBoard;
            $count++;
        }
        return $listOfBoards;
    }

    /**
     * @return TrelloList[]
     */
    public static function getListList() : array {
        $ret = [];
        $count = 0;
        foreach(GenerateTestData::ids as $id) {
            $trelloList = new TrelloList($id, "List Name $count");
            $trelloList->idBoard = 'abc';
            $trelloList->cards = GenerateTestData::getCardList();
            $ret[] = $trelloList;
            $count +=1;
        }

        return $ret;
    }

    /**
     * @return TrelloCard[]
     */
    public static function getCardList() : array {
        $ret = [];
        $count = 0;
        foreach(GenerateTestData::ids as $id) {
            $trelloCard = new TrelloCard($id, "Card Name $count");
            $trelloCard->isClosed = false;
            $ret[] = $trelloCard;
            $count += 1;
        }

        return $ret;
    }

}