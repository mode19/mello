<?php

namespace Mode19\Mello\tests\fakes;

use Mode19\Mello\Client;
use Mode19\Mello\Service;
use Mode19\Mello\Tests\TestData\GenerateTestData;

class ServiceFake extends Service
{
    public function __construct()
    {
    }

    function getMembersBoards(): array
    {
        return GenerateTestData::getBoardList();
    }

    function getListsOnBoard($boardId): array
    {
        return GenerateTestData::getListList();
    }

    function getCardsInList($listId): array
    {
        return GenerateTestData::getCardList();
    }


}