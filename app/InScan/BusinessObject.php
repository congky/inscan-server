<?php
namespace App\InScan;

/**
 * Interface BusinessObject
 * @package FLA\Core
 *
 * @author Congky, 2018-05-10
 */
interface BusinessObject
{
    function getDescription();
    public function execute($input);
}