<?php
namespace App\InScan;

/**
 * Class ResponseUtil
 * @package FLA\Core\Util
 *
 * @author Congky, 2018-05-10
 */
class ResponseUtil
{
    public static function resultObject($object) {

        $data = $object->getData();

        return $data->response;
    }

    public static function isOk($response) {

        return response()->json([
            'status' => GeneralConstant::_OK,
            'response' => $response
        ]);

    }

    public static function isFail($ex) {

        return response()->json([
            'status' => GeneralConstant::_FAIL,
            'message' => $ex->getMessage()
        ]);

    }

    public static function isUnauthorized($ex) {

        return response()->json([
            'status' => GeneralConstant::_UNAUTHORIZED,
            'message' => $ex->getMessage()
        ]);

    }
}