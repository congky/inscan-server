<?php
namespace App\InScan\BusinessObject\BusinessFunction;

use App\InScan\AbstractBusinessFunction;
use App\InScan\GeneralConstant;
use App\InScan\Model\Sifat;
use App\InScan\Model\UserSifat;
use App\InScan\ValidationUtil;
use Illuminate\Support\Facades\DB;

class GetUserSifatByUserId extends AbstractBusinessFunction
{

    protected function process($input, $oriInput)
    {
        ValidationUtil::valBlankOrNull($input, "userId");

        $sifat = DB::select("
            SELECT A.user_sifat_id, A.user_id, A.sifat_id, B.sifat, A.version, A.active
              FROM ".UserSifat::getTableName()." A
              INNER JOIN ".Sifat::getTableName()." B ON A.sifat_id = B.sifat_id
              WHERE A.active = :yes
              AND A.user_id = :userId
        ", [
            "yes"=> GeneralConstant::_YES,
            "userId"=> $input["userId"]
        ]);

        return [
            "sifatList"=>$sifat
        ];
    }

    function getDescription()
    {
        return "Get user sifat by user id";
    }
}