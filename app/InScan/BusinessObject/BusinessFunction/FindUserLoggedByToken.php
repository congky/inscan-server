<?php
namespace App\InScan\BusinessObject\BusinessFunction;

use App\InScan\AbstractBusinessFunction;
use App\InScan\CoreException;
use App\InScan\Model\UserLogged;
use App\InScan\Model\Users;
use App\InScan\ValidationUtil;

class FindUserLoggedByToken extends AbstractBusinessFunction
{

    protected function process($input, $oriInput)
    {
        ValidationUtil::valBlankOrNull($input, "token");

        $userLogged = UserLogged::where([
            ['token', $input["token"]]
        ])->first();

        if ($userLogged == null) {
            throw new CoreException("Token tidak valid");
        }

        return $userLogged;
    }

    function getDescription()
    {
        return "Untuk mendapatkan informasi user logged by token yang dikirim";
    }
}