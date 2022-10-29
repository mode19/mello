<?php
namespace Mode19\Mello;

use Mode19\Mello\Service;
use Flow\JSONPath\JSONPath;
use Mode19\Mello\Widget\BaseWidget;
use Mode19\Mello\Widget\ShowBoardsWidget;
use Mode19\Mello\Widget\ShowListsWidget;

class OutputBuffer
{
    private string $content;

    /**
     * @param $service
     */
    public function __construct()
    {
    }

    public function print($msg) {
        $this->content += $msg;
    }
}

?>
