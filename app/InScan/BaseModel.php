<?php
namespace App\InScan;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected $dateFormat = 'YmdHis';
    const CREATED_AT = 'create_datetime';
    const UPDATED_AT = 'update_datetime';

    public static function getTableName(){
        return with(new static)->getTable();
    }
}