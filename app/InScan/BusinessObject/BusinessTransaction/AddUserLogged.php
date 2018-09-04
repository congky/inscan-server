<?php
namespace App\InScan\BusinessObject\BusinessTransaction;

use App\InScan\AbstractBusinessTransaction;
use App\InScan\DateUtil;
use App\InScan\GeneralConstant;
use App\InScan\Model\UserLogged;
use App\InScan\ValidationUtil;

class AddUserLogged extends AbstractBusinessTransaction
{

    protected function prepare(&$input, $oriInput)
    {
        ValidationUtil::valBlankOrNull($input, "userLoggedId");
        ValidationUtil::valBlankOrNull($input, "token");
        ValidationUtil::valBlankOrNull($input, "flgTokenAge");
        ValidationUtil::valBlankOrNull($input, "tokenAge");
    }

    protected function process(&$input, $oriInput)
    {

        $userLogged = new UserLogged();
        $userLogged->user_id = $input["userLoggedId"];
        $userLogged->token = $input["token"];
        $userLogged->flg_token_age = $input["flgTokenAge"];
        $userLogged->token_age = $input["tokenAge"];
        $userLogged->create_user_id = $input["userLoggedId"];
        $userLogged->update_user_id = $input["userLoggedId"];
        $userLogged->version = 0;
        $userLogged->active = GeneralConstant::_YES;
        $userLogged->active_datetime = DateUtil::currentDatetime();
        $userLogged->non_active_datetime = GeneralConstant::_SPACE_VALUE;

        $userLogged->save();

        return $userLogged;

    }

    function getDescription()
    {
        return "untuk menyimpan token user login";
    }
}