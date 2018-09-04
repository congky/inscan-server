<?php
namespace App\InScan\BusinessObject\BusinessFunction;

use App\InScan\AbstractBusinessFunction;
use App\InScan\Model\Sifat;
use App\InScan\Model\UserSifat;
use App\InScan\ValidationUtil;
use Illuminate\Support\Facades\DB;

class GetUserSifatForGenerateUserSifat extends AbstractBusinessFunction
{

    protected function process($input, $oriInput)
    {
        ValidationUtil::valBlankOrNull($input, "userLoggedId");
        ValidationUtil::valBlankOrNull($input, "limit");

        $sifat = DB::select("
            SELECT A.user_sifat_id, A.user_id, A.sifat_id, B.sifat, A.version, A.active
              FROM ".UserSifat::getTableName()." A
              INNER JOIN ".Sifat::getTableName()." B ON A.sifat_id = B.sifat_id
              WHERE A.user_id = :userId
              ORDER BY random() LIMIT :limit
        ", [
            "userId"=> $input["userLoggedId"],
            "limit"=> $input["limit"]
        ]);

        return [
            "sifatList"=>$sifat
        ];
    }

    function getDescription()
    {
        return "Get user sifat for generate user sifat, dengan limit sesuai dengan inputan, dan akan di order by random";
    }
}