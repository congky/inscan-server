<?php
namespace App\InScan\Model;

use App\InScan\BaseModel;

class Users extends BaseModel
{
    protected $table = 't_users';
    protected $primaryKey = 'user_id';
}