<?php
namespace App\Http\Controllers;

use App\InScan\BusinessObject\BusinessFunction\GetUserSifatByUserId;
use App\InScan\BusinessObject\BusinessTransaction\GenerateUserSifat;
use App\InScan\BusinessObject\BusinessTransaction\ResetUserSifatByUserId;
use App\InScan\CoreException;
use App\InScan\ResponseUtil;
use Illuminate\Http\Request;

class GenerateController extends Controller
{

    public function generate(Request $request){

        try {
            $generateUserSifat = new GenerateUserSifat();
            $generateUserSifat->execute([
                "userId" => $request->userLoggedId
            ]);

            $getUserSifatByUserId = new GetUserSifatByUserId();
            $result = $getUserSifatByUserId->execute([
                "userId" => $request->userLoggedId
            ]);


            return ResponseUtil::isOk($result);

        } catch (CoreException $ex) {
            return ResponseUtil::isFail($ex);
        }
    }

    public function reset(Request $request){

        try {
            $resetUserSifatByUserId = new ResetUserSifatByUserId();
            $result = $resetUserSifatByUserId->execute([
                "userId" => $request->userLoggedId
            ]);

            return ResponseUtil::isOk($result);

        } catch (CoreException $ex) {
            return ResponseUtil::isFail($ex);
        }
    }
}