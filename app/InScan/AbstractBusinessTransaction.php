<?php
namespace App\InScan;

use Exception;
use Illuminate\Support\Facades\DB;

/**
 * Class AbstractBusinessTransaction
 * @package FLA\Core
 *
 * @author Congky, 2018-05-10
 */
abstract class AbstractBusinessTransaction implements BusinessObject
{

    abstract protected function prepare(&$input, $oriInput);
    abstract protected function process(&$input, $oriInput);

    public function execute($input) {

        $oriInput = $input;

        try {
            DB::beginTransaction();

            $this->prepare($input, $oriInput);
            $result = $this->process($input, $oriInput);

            DB::commit();
            return $result;

        } catch (Exception $e) {
            DB::rollBack();
            throw new CoreException($e->getMessage());
        }
    }

    protected function activated(&$input) {
        $input['active'] = GeneralConstant::_YES;
        $input['activeDatetime'] = DateUtil::currentDatetime();
        $input['nonActiveDatetime'] = GeneralConstant::_EMPTY_VALUE;
    }

    protected function deActivated(&$input) {
        $input['active'] = GeneralConstant::_NO;
        $input['nonActiveDatetime'] = DateUtil::currentDatetime();
        $input['activeDatetime'] = GeneralConstant::_EMPTY_VALUE;
    }

}