<?php
namespace App\InScan\BusinessObject\BusinessFunction;

use App\InScan\AbstractBusinessFunction;
use App\InScan\GeneralConstant;
use App\InScan\Model\Sifat;
use App\InScan\Model\UserSifat;
use App\InScan\ValidationUtil;
use Illuminate\Support\Facades\DB;

class GetSifatForGenerateUserSifat extends AbstractBusinessFunction
{

    protected function process($input, $oriInput)
    {
        ValidationUtil::valBlankOrNull($input, "userLoggedId");
        ValidationUtil::valBlankOrNull($input, "limit");
        ValidationUtil::valBlankOrNull($input, "excludeExisting");

        $queryExcludeExisting = "";
        if($input["excludeExisting"] == GeneralConstant::_YES) {
            $queryExcludeExisting = " AND NOT EXISTS (SELECT 1 FROM ".UserSifat::getTableName()." Z 
                                                        WHERE A.sifat_id = Z.sifat_id 
                                                        AND Z.user_id = ".$input["userLoggedId"]."
                                                        ) ";
        }

        $sifat = DB::select("
            SELECT sifat_id, sifat, create_datetime, update_datetime, create_user_id, 
                   update_user_id, version, active, active_datetime, non_active_datetime
              FROM ".Sifat::getTableName()." A
              WHERE active = :yes 
              ".$queryExcludeExisting."
              ORDER BY random() LIMIT :limit
        ", [
            "yes"=> GeneralConstant::_YES,
            "limit"=> $input["limit"]
        ]);

        return [
            "sifatList"=>$sifat
        ];
    }

    function getDescription()
    {
        return "Get sifat for generate user sifat, dengan limit sesuai dengan inputan, dan akan di order by random";
    }
}