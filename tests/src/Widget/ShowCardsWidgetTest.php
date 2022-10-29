<?php

namespace Mode19\Mello\Tests;

use Mode19\Mello\Tests\TestData\GenerateTestData;
use Mode19\Mello\DataModel\TrelloBoard;
use Mode19\Mello\DataModel\TrelloList;
use Mode19\Mello\Service;
use Mode19\Mello\Widget\ShowListsWidget;
use PHPUnit\Framework\TestCase;

class ShowCardsWidgetTest extends TestCase
{
    public function testInteractiveRender()
    {
        system("stty -icanon");
        $boardId = '624666d046e8de25a2c7b006';
        $listOfBoards = GenerateTestData::getBoardList();
        $listOfLists = GenerateTestData::getListList();
        $mockService = $this->getMockBuilder(Service::class)->disableOriginalConstructor()->getMock();
        $mockService->method('getListsOnBoard')->willReturn($listOfLists);
        $widget = new ShowListsWidget($mockService, $boardId, $listOfBoards);


        $widget->fetchData();
        $widget->render();
        sleep(4);
        while(true) {
            printf("In loop\n");
            readline("PROMPT");
        }
    }


}