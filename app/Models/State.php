<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class State extends Model
{
    const MODES = ['all-stores', 'coupons', 'stores-by-letter'];

    private $parsingMode;

    private $parsedLink;

    protected $primaryKey = 'stateId';

    public function setParsingModeAttribute($value)
    {
        if(in_array($value, self::MODES)) {
            $this->attributes['parsingMode'] = $value;
        }
        else {
            throw new \Exception('Invalid parsing mode');
        }
    }


    public function stores()
    {
        return $this->hasMany('Store');
    }



}
