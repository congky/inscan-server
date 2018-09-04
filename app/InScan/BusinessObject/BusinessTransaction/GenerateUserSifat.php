<?php
namespace App\InScan\BusinessObject\BusinessTransaction;

use App\InScan\AbstractBusinessTransaction;
use App\InScan\BusinessObject\BusinessFunction\GetSifatForGenerateUserSifat;
use App\InScan\BusinessObject\BusinessFunction\GetUserSifatForGenerateUserSifat;
use App\InScan\DateUtil;
use App\InScan\GeneralConstant;
use App\InScan\Model\UserSifat;
use App\InScan\ValidationUtil;
use Illuminate\Support\Facades\DB;

class GenerateUserSifat extends AbstractBusinessTransaction
{

    protected function prepare(&$input, $oriInput)
    {
        ValidationUtil::valBlankOrNull($input, "userId");

        $getUserSifatForGenerateUserSifat = new GetUserSifatForGenerateUserSifat();
        $resultExistingSifat = $getUserSifatForGenerateUserSifat->execute([
            "userLoggedId"=>$input["userId"],
            "limit"=>2
        ]);

        $limitForNewSifat = 5;
        $excludeExisting = GeneralConstant::_YES;
        $input["existingSifat"] = false;
        if(!is_null($resultExistingSifat["sifatList"]) && !empty($resultExistingSifat["sifatList"])) {
            $excludeExisting = GeneralConstant::_YES;
            $limitForNewSifat = 2;
            $input["existingSifat"] = true;
        }
        $getSifatForGenerateUserSifat = new GetSifatForGenerateUserSifat();
        $resultNewSifat = $getSifatForGenerateUserSifat->execute([
            "userLoggedId"=>$input["userId"],
            "limit"=>$limitForNewSifat,
            "excludeExisting"=>$excludeExisting
        ]);

        $input["existingSifat"] = true;
        $input["existingSifatList"] = $resultExistingSifat["sifatList"];
        $input["newSifatList"] = $resultNewSifat["sifatList"];
    }

    protected function process(&$input, $oriInput)
    {

        $existingSifat = $input["existingSifat"];
        $existingSifatList = $input["existingSifatList"];
        $newSifatList = $input["newSifatList"];

        if($existingSifat) {
            foreach ($existingSifatList as $value) {
                $userSifat = UserSifat::find($value->user_sifat_id);
                $userSifat->delete();
            }

            DB::select("
                UPDATE ".UserSifat::getTableName()." A SET
                    active = :yes,
                    active_datetime = :currentDatetime,
                    non_active_datetime = :empty,
                    version = A.version+1
                WHERE A.user_id = :userId
            ", [
                    "yes"=> GeneralConstant::_YES,
                    "empty"=> GeneralConstant::_EMPTY_VALUE,
                    "currentDatetime"=> DateUtil::currentDatetime(),
                    "userId"=> $input["userId"]
                ]);
        }

        if(!is_null($newSifatList) && $newSifatList!=GeneralConstant::_EMPTY_VALUE) {
            foreach ($newSifatList as $value) {
                $userSifat = new UserSifat();
                $userSifat->user_id = $input["userId"];
                $userSifat->sifat_id = $value->sifat_id;
                $userSifat->create_user_id = $input["userId"];
                $userSifat->update_user_id = $input["userId"];
                $userSifat->version = 0;
                $userSifat->active = GeneralConstant::_YES;
                $userSifat->active_datetime = DateUtil::currentDatetime();
                $userSifat->non_active_datetime = GeneralConstant::_SPACE_VALUE;

                $userSifat->save();
            }
        }

        return [
            "userId"=>$input["userId"]
        ];

    }

    function getDescription()
    {
        return "Generate user sifat";
    }
}