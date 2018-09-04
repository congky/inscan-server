<?php
namespace App\InScan\BusinessObject\BusinessFunction;

use App\InScan\AbstractBusinessFunction;
use App\InScan\CoreException;
use App\InScan\Model\Users;
use App\InScan\ValidationUtil;

class FindUserByCode extends AbstractBusinessFunction
{

    protected function process($input, $oriInput)
    {
        ValidationUtil::valBlankOrNull($input, "codeOrPhone");

        $user = Users::where([
            ['code', $input["codeOrPhone"]]
        ])->orWhere([
            ['phone', $input["codeOrPhone"]]
        ])->first();

        if ($user == null) {
            throw new CoreException("Kode / Nomor Hp yang anda masukan tidak valid");
        }

        return $user;
    }

    function getDescription()
    {
        return "Untuk mendapatkan informasi user by code yang dikirim";
    }
}