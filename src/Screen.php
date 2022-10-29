<?php
namespace Mode19\Mello;

use Mode19\Mello\Service;
use Flow\JSONPath\JSONPath;
use Mode19\Mello\Widget\BaseWidget;
use Mode19\Mello\Widget\ShowBoardsWidget;
use Mode19\Mello\Widget\ShowListsWidget;

class Screen
{
    public Service $service;
    private string $lastInput;
    private Stack $stack;
    private BaseWidget $currentWidget;

    /**
     * @param $service
     */
    public function __construct(Service $service)
    {
        $this->service = $service;
        $this->stack = new Stack();
    }

    public function getKey()
    {
        $this->lastInput = fread(STDIN, 1);
        return $this->lastInput;
    }

    public function getInput()
    {
        $this->lastInput = readline();
        return $this->lastInput;
    }

    function clear()
    {
        print("\033[2J\033[;H");
    }

    function applicationLoop($initialWidget) {
        $this->currentWidget = $initialWidget;
        $this->currentWidget->fetchData();
        while (true) {
            $this->clear();
            $this->currentWidget->render();
            $key = $this->getKey();
            if(!$this->processGlobalCommands($key)) {
                switch (strtolower($key)) {
                    case 'k':
                        $this->currentWidget->keyUp();
                        break;
                    case 'j':
                        $this->currentWidget->keyDown();
                        break;
                    case PHP_EOL:
                        $newWidget = $this->currentWidget->click();
                        if($newWidget !== $this->currentWidget) {
                            $this->stack->push($this->currentWidget);
                            $newWidget->fetchData();
                            $this->currentWidget = $newWidget;
                        }
                        break;
                    default:
                        $this->processDefaultCommands($key);
                }
            }
        }
    }

    private function processGlobalCommands($key) : bool {
        switch (strtolower($key)) {
            case 'q':
                exit();
            default:
                return false;
        }
        return true;
    }

    private function processDefaultCommands($key) : bool {
        switch (strtolower($key)) {
            case 'h':
                if(!$this->stack->isEmpty()) {
                    $this->currentWidget = $this->stack->pop();
                }
                break;
            default:
                return false;
        }
        return true;
    }
}

?>
