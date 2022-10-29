<?php
namespace Mode19\Mello\Tests\src\Widget;
use Mode19\Mello\Tests\fakes\ServiceFake;
use Mode19\Mello\Tests\TestData\GenerateTestData;
use Mode19\Mello\Widget\ShowListsWidget;

require_once __DIR__ . '/../../../vendor/autoload.php';

system("stty -icanon");


$boardId = '624666d046e8de25a2c7b006';


$widget = new ShowListsWidget(new ServiceFake(), $boardId, GenerateTestData::getBoardList());

$widget->fetchData();
$widget->render();
while(true) {
    printf("In loop\n");
    $widget->click();
}

