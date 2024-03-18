<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LarasutSetting extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'larasut_setting';

    protected $fillable = ['id', 'access_token', 'default_customer_category_id', 'expires_at', 'expires_in', 'created_at', 'updated_at', 'deleted_at'];

}
