<?php
namespace App\InScan;

use Exception;

/**
 * Class CoreException
 * @package FLA\Core
 *
 * @author Congky, 2018-05-10
 */
class CoreException extends Exception
{

    public function __construct($message, ...$args) {
        $message = $this->generateMessage($message, $args);
        parent::__construct($message);
        return $this;
    }

    public function setErrorCode(int $errorCode){
        if(!is_null($errorCode)) {
            parent::__construct(parent::getMessage(), $errorCode);
        }
    }

    private function generateMessage($msg, $paramMsgList) {
        for ($i = 0; $i < count($paramMsgList); $i++) {
            $msg = str_replace('{'.$i.'}',$paramMsgList[$i], $msg );
        }
        return $msg;
    }
}