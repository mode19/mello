<?php
namespace Mode19\Mello;

use DateTime;

class Utils
{

    public static $terminalRows = 0;

    public static function log($message)
    {
        $logMsg = sprintf("%s - $%s\n", date('Y-m-d H:m:s'), $message);
        file_put_contents('./logfile.log', $logMsg, FILE_APPEND);
    }

    public static function getDateFromId($id): DateTime
    {
        $date = new DateTime();
        $date->setTimestamp(hexdec(substr($id, 0, 8)));
        return $date;
    }

    public static function clear()
    {
        print("\033[2J\033[;H");
    }

    public static function getKey() {
        return fread(STDIN, 1);
    }

    public static function getTerminalRows() {
        if(Utils::$terminalRows == 0 ) {
            Utils::$terminalRows = exec('tput lines');
        }
        return Utils::$terminalRows;
    }
}

?>
