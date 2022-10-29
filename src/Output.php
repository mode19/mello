<?php
namespace Mode19\Mello;

class Output
{

    function println(string $str)
    {
        println($str);
    }

    function getKey() {
    }

}

$app = new Main(new Screen(new Service(new Client())));
$app->startApp();

