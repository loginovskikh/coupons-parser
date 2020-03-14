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

    /**
     * @return mixed
     */
    public function getCategoryName()
    {
        return $this->categoryName;
    }

    /**
     * @param mixed $categoryName
     */
    public function setCategoryName($categoryName): void
    {
        $this->categoryName = $categoryName;
    }

    /**
     * @return mixed
     */
    public function getCategoryLink()
    {
        return $this->categoryLink;
    }

    /**
     * @param mixed $categoryLink
     */
    public function setCategoryLink($categoryLink): void
    {
        $this->categoryLink = $categoryLink;
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
}
