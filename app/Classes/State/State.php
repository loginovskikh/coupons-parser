<?php


namespace App\Classes\State;

use Exception;
use Illuminate\Support\Facades\Artisan;

class State
{
    public static function saveState($parsingMode, $parsedLink)
    {
        try {
            $state = new \App\Models\State();
            $state->setParsedLink($parsedLink);
            $state->setParsingModeAttribute($parsingMode);
            $state->save();

            return $state;

        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public static function updateState(\App\Models\State $state, $parsedLink)
    {
        $state->setParsedLink($parsedLink);
        $state->save();
    }

    public static function deleteState($stateId)
    {
        \App\Models\State::destroy($stateId);
    }

    public static function checkState()
    {
        $currentState = \App\Models\State::all()->last();

        return ($currentState) ? : null;
    }

    public static function RestorePreviousCommandByState(\App\Models\State $state)
    {
        $modes = config('parser.PARSING_MODE');
        if(in_array($state->getParsingMode(), $modes)) {
            echo 'Find previous parsing state: ' . $state->getParsingMode() . PHP_EOL;

            if($state->getParsingMode() === 'stores-by-letter') {
                Artisan::call('parse:stores', ['--link' => $state->getParsedLink(), '--force' => true]);
            }

            if($state->getParsingMode() === 'all-stores') {
                $slicedAlphabetArray = self::splitLetterArray($state->getParsedLink());
                Artisan::call('parse:stores', ['--all' => true, '--force' => true, '--la' => $slicedAlphabetArray]);
            }

        }

    }

    public static function getLastLetter(string $link)
    {
        $linkArray = explode('/', $link);

        return $linkArray[count($linkArray) -1];
    }

    private static function splitLetterArray($link)
    {
        $letter = self::getLastLetter($link);
        $position = array_search($letter, config('parser.STORES_ALPHABET_CATEGORIES'));
        if($position) {
            $fullLetterArray = config('parser.STORES_ALPHABET_CATEGORIES');
            $result = array_splice($fullLetterArray, $position);
        }
        else {
            $result = [];
        }

        return $result;
    }
}
