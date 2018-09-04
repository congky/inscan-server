<?php

namespace App\Http\Middleware;

use App\InScan\BusinessObject\BusinessFunction\FindUserLoggedByToken;
use App\InScan\CoreException;
use App\InScan\GeneralConstant;
use App\InScan\ResponseUtil;
use Closure;

class LoggedMiddleware
{
    public function handle($request, Closure $next)
    {
        try {

            $findUserLoggedByToken = new FindUserLoggedByToken();
            $result = $findUserLoggedByToken->execute([
                "token"=>$request->header("token")
            ]);

            if($result->active!=GeneralConstant::_YES) {
                return response()->json([
                    'status' => GeneralConstant::_FAIL,
                    'message' => "Token tidak valid"
                ]);
            }

            $request->userLoggedId = $result->user_id;
            $request->tokenAge = $result->token_age;
            $request->flgTokenAge = $result->flg_token_age;
            $request->tokenStatusActive = $result->active;
            $request->tokenActiveDatetime = $result->active_datetime;
            $request->tokenCreateDatetime = $result->tokenCreateDatetime;

            $response = $next($request);

            return $response;
        } catch (CoreException $ex) {
            return ResponseUtil::isUnauthorized($ex);
        }
    }
}
