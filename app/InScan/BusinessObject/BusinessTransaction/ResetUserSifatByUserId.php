<?php
namespace App\InScan\BusinessObject\BusinessTransaction;

use App\InScan\AbstractBusinessTransaction;
use App\InScan\DateUtil;
use App\InScan\GeneralConstant;
use App\InScan\Model\UserSifat;
use App\InScan\ValidationUtil;
use Illuminate\Support\Facades\DB;

class ResetUserSifatByUserId extends AbstractBusinessTransaction
{

    protected function prepare(&$input, $oriInput)
    {
        ValidationUtil::valBlankOrNull($input, "userId");
    }

    protected function process(&$input, $oriInput)
    {
        DB::select("
            UPDATE ".UserSifat::getTableName()." A SET
                active = :no,
                active_datetime = :empty,
                non_active_datetime = :currentDatetime,
                version = A.version+1
            WHERE A.user_id = :userId
        ", [
            "no"=> GeneralConstant::_NO,
            "empty"=> GeneralConstant::_EMPTY_VALUE,
            "currentDatetime"=> DateUtil::currentDatetime(),
            "userId"=> $input["userId"]
        ]);

        return [
            "userId" => $input["userId"]
        ];

    }

    function getDescription()
    {
        return "Remove user sifat";
    }
}