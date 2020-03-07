<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use SoftDeletes;

    private $couponTitle;

    private $couponDescription;

    private $couponImage;

    private $couponLifetime;

    private $couponLink;

    protected $primaryKey = 'couponId';

    public function store()
    {
        return $this->belongsTo('Store');
    }





}
