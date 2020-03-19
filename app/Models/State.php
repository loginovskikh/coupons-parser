<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;


class State extends Model
{
    private $parsingMode;

    private $parsedLink;

    protected $primaryKey = 'stateId';

    /**
     * @param string $value
     * @throws Exception
     */
    public function setParsingModeAttribute($value)
    {
        if(in_array($value, config('parser.PARSING_MODE'))) {
            $this->attributes['parsingMode'] = $value;
        }
        else {
            throw new Exception('Invalid parsing mode');
        }
    }

    /**
     * @return string
     */
    public function getParsingMode()
    {
        return $this->getAttribute('parsingMode');
    }

    /**
     * @param string $parsedLink
     */
    public function setParsedLink($parsedLink): void
    {
        $this->attributes['parsedLink'] = $parsedLink;
    }

    /**
     * @return string
     */
    public function getParsedLink()
    {
        return $this->getAttribute('parsedLink');
    }


}
