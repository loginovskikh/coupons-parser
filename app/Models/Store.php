<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use SoftDeletes;

    private $storeName;

    private $storeLink;

    protected $primaryKey = 'storeId';

    protected $fillable = ['storeName', 'storeLink'];

    public function coupons()
    {
        return $this->hasMany('Coupon');
    }

    public function category()
    {
        return $this->belongsTo('Category');
    }

    public function state()
    {
        return $this->belongsTo('State');
    }

}
