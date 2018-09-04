<?php
namespace App\InScan\BusinessObject\BusinessTransaction;

use App\InScan\AbstractBusinessTransaction;
use App\InScan\BusinessObject\BusinessFunction\FindUserLoggedByToken;
use App\InScan\DateUtil;
use App\InScan\GeneralConstant;
use App\InScan\Model\UserLogged;
use App\InScan\ValidationUtil;

class DestroyUserLoggedByToken extends AbstractBusinessTransaction
{

    protected function prepare(&$input, $oriInput)
    {
        ValidationUtil::valBlankOrNull($input, "userLoggedId");
        ValidationUtil::valBlankOrNull($input, "token");

        $findUserLoggedByToken = new FindUserLoggedByToken();
        $result = $findUserLoggedByToken->execute([
            "token"=>$input["token"]
        ]);

        $input["userLogged"] = $result;
    }

    protected function process(&$input, $oriInput)
    {
        $userLogged = $input["userLogged"];

        $userLogged = UserLogged::find($userLogged->user_logged_id);
        $userLogged->update_user_id = $input["userLoggedId"];
        $userLogged->version = $userLogged->version+1;
        $userLogged->active = GeneralConstant::_NO;
        $userLogged->non_active_datetime = DateUtil::currentDatetime();
        $userLogged->active_datetime = GeneralConstant::_SPACE_VALUE;

        $userLogged->save();

        return $userLogged;

    }

    function getDescription()
    {
        return "untuk menonactivekan token user login";
    }
}