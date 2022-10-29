<?php
namespace Mode19\Mello;
require_once __DIR__ . '/../vendor/autoload.php';

use Flow\JSONPath\JSONPath;
use Mode19\Mello\Widget\ShowBoardsWidget;

system("stty -icanon");

class Main
{

    private Screen $screen;

    /**
     * @param Client $client
     */
    public function __construct(Screen $screen)
    {
        $this->screen = $screen;
    }

    function startApp()
    {
        $this->screen->clear();
        $initialWidget = new ShowBoardsWidget($this->screen->service);
        $this->screen->applicationLoop($initialWidget);
    }
}

$app = new Main(new Screen(new Service(new Client())));
$app->startApp();
?>
