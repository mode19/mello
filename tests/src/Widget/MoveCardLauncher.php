<?php
namespace Mode19\Mello\Tests\src\Widget;
use Mode19\Mello\DataModel\TrelloCard;
use Mode19\Mello\Screen;
use Mode19\Mello\Tests\fakes\ServiceFake;
use Mode19\Mello\Tests\TestData\GenerateTestData;
use Mode19\Mello\Widget\MoveCardWidget;
use Mode19\Mello\Widget\ShowListsWidget;

require_once __DIR__ . '/../../../vendor/autoload.php';

system("stty -icanon");


$boardId = '624666d046e8de25a2c7b006';

$trelloCard = new TrelloCard($boardId, 'Test Card 1');
$screen = new Screen(new ServiceFake());
$widget = new MoveCardWidget(new ServiceFake(), GenerateTestData::getBoardList(), $trelloCard);

$screen->applicationLoop($widget);



