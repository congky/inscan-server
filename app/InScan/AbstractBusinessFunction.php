<?php
namespace App\InScan;

use Exception;
use Illuminate\Support\Facades\DB;


/**
 * Class AbstractBusinessFunction
 * @package FLA\Core
 *
 * @author Congky, 2018-05-10
 */
abstract class AbstractBusinessFunction implements BusinessObject
{

    abstract protected function process($input, $oriInput);

    public function execute($input) {
        $oriInput = $input;

        try {
            DB::beginTransaction();

            $result = $this->process($input, $oriInput);

            DB::rollBack();

            return $result;
        } catch (Exception $e) {
            DB::rollBack();
            throw new CoreException($e->getMessage());
        }
    }

}