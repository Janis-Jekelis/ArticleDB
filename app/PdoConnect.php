<?php
declare(strict_types=1);

namespace App;
use PDO;
class PdoConnect
{

    public static function pdoConnect():PDO
    {
        $dsn=rawurldecode(http_build_query(
            require(__DIR__."/../pdoConfig.php"),"",";"
        ));
        return new PDO($dsn);
    }

}
