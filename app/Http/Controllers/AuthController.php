<?php

namespace App\Http\Controllers;

use App\InScan\BusinessObject\BusinessFunction\FindUserByCode;
use App\InScan\BusinessObject\BusinessFunction\FindUserById;
use App\InScan\BusinessObject\BusinessFunction\FindUserLoggedByToken;
use App\InScan\BusinessObject\BusinessFunction\GetUserSifatByUserId;
use App\InScan\BusinessObject\BusinessTransaction\AddUserLogged;
use App\InScan\BusinessObject\BusinessTransaction\DestroyUserLoggedByToken;
use App\InScan\CoreException;
use App\InScan\DateUtil;
use App\InScan\GeneralConstant;
use App\InScan\ResponseUtil;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request){

        try {
            $findUserByCode = new FindUserByCode();
            $result = $findUserByCode->execute([
                "codeOrPhone" => $request->codeOrPhone
            ]);

            if(!is_null($result) && $result!=GeneralConstant::_EMPTY_VALUE) {

                $getUserSifatByUserId = new GetUserSifatByUserId();
                $resultUserSifat = $getUserSifatByUserId->execute([
                        "userId" => $result->user_id
                ]);

                $addUserLogged = new AddUserLogged();
                $resultAdd = $addUserLogged->execute([
                    "userLoggedId" => $result->user_id,
                    "token" => md5(uniqid($result->user_id."_".rand()."_".DateUtil::currentDatetime(), true)),
                    "flgTokenAge" => GeneralConstant::_NO,
                    "tokenAge" => GeneralConstant::_EMPTY_ID
                ]);

                return ResponseUtil::isOk([
                    "token" => $resultAdd->token,
                    "fullName" => $result->full_name,
                    "sifatList"=> $resultUserSifat["sifatList"]
                ]);
            }

        } catch (CoreException $ex) {
            return ResponseUtil::isFail($ex);
        }
    }

    public function logout(Request $request){


        try {


            $destroyUserLoggedByToken = new DestroyUserLoggedByToken();
            $destroyUserLoggedByToken->execute([
                "userLoggedId" => $request->userLoggedId,
                "token" => $request->header("token")
            ]);

            return ResponseUtil::isOk([]);

        } catch (CoreException $ex) {
            return ResponseUtil::isFail($ex);
        }
    }

    public function checkToken(Request $request){

        try {

            $findUserLoggedByToken = new FindUserLoggedByToken();
            $result = $findUserLoggedByToken->execute([
                "token"=>$request->header("token")
            ]);

            $findUserById = new FindUserById();
            $resultUserById = $findUserById->execute([
                "id"=>$result->user_id
            ]);

            if($result->active==GeneralConstant::_YES) {
                return ResponseUtil::isOk([
                    "token" => $result->token,
                    "fullName" => $resultUserById->full_name
                ]);
            } else {
                return response()->json([
                    'status' => GeneralConstant::_FAIL,
                    'message' => "Token tidak valid"
                ]);
            }

        } catch (CoreException $ex) {
            return ResponseUtil::isFail($ex);
        }
    }
}
