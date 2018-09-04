<?php
namespace App\InScan\BusinessObject\BusinessFunction;

use App\InScan\AbstractBusinessFunction;
use App\InScan\CoreException;
use App\InScan\Model\Users;
use App\InScan\ValidationUtil;

class FindUserById extends AbstractBusinessFunction
{

    protected function process($input, $oriInput)
    {
        ValidationUtil::valBlankOrNull($input, "id");

        $user = Users::where([
            ['user_id', $input["id"]]
        ])->first();

        if ($user == null) {
            throw new CoreException("User tidak ditemukan");
        }

        return $user;
    }

    function getDescription()
    {
        return "Untuk mendapatkan informasi user by id yang dikirim";
    }
}