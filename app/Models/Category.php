<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    private $categoryName;

    private $categoryLink;

    protected $primaryKey = 'categoryId';

    public function stores()
    {
        return $this->hasMany('Store');
    }

    public function coupons()
    {
        return $this->hasManyThrough('Coupon', 'Store');
    }
}
