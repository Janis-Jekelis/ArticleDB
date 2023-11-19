<?php
declare(strict_types=1);

namespace App;
use PDO;
class PdoConnect
{

    public static function pdoConnect():PDO
    {
        $dsn=http_build_query(require_once(__DIR__."/../pdoConfig.php"),"",";");
        return new PDO($dsn);
    }

}
