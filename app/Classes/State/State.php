<?php


namespace App\Classes\State;


class State
{
    public static function saveState($parsingMode, $parsedLink)
    {
        try {
            $state = new \App\Models\State();
            $state->parsedLink = $parsedLink;
            $state->setParsingModeAttribute($parsingMode);
            $state->save();
            $stateId = $state->stateId;
        } catch (\Exception $e) {
            $stateId = null;
            echo $e->getMessage();
        }

        return $stateId;
    }

    public static function deleteState($stateId)
    {
        \App\Models\State::destroy($stateId);
    }
}
