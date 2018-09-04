<?php
namespace App\InScan;


class GeneralUtil
{
    public function generateCode(int $length){
        $string = "";
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        for($i=0;$i<$length;$i++)
            $string.=substr($chars,rand(0,strlen($chars)),1);
        return $string;
    }
}