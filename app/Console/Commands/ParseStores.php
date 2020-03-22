<?php

namespace App\Console\Commands;

use App\Classes\Parser\StoresParser;
use App\Classes\State\State;
use App\Exceptions\ParsingException;
use Exception;
use Illuminate\Console\Command;


class ParseStores extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:stores
                            {--link= : URL to stores page (alphabet categories page)}
                            {--all : Parse all stores }
                            {--l= : Letter of alphabet category }
                            {--force : Run command ignoring previous state }
                            {--la= : array of STORES_ALPHABET_CATEGORIES }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse stores from website';
    /**
     * @var StoresParser
     */
    private $parser;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->parser = new StoresParser();
    }

    private function parseStore($link)
    {
        if ($link) {
            echo 'URL: ' . $link . PHP_EOL;
            $parse = $this->parser->parse($link);
            echo $parse . ' new stores was added' . PHP_EOL;
            echo 'Success' . PHP_EOL;
        } else {
            throw new Exception('Set --link or -l option');
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws Exception
     */
    public function handle()
    {
        if(($this->option('link'))) {
            $link = $this->option('link');
        }
        elseif($this->option('l')) {
            $link = config('parser.STORE_URL') . $this->option('l');
        }
        else {
            $link = null;
        }

        $letterArray = ($this->option('la')) ? : config('parser.STORES_ALPHABET_CATEGORIES');
        $all         = $this->option('all');
        $force       = $this->option('force');

        $state = State::checkState();

        try {
            if($force || !$state)
            {
                if($all) {
                    if($letterArray) {
                        $state = State::saveState('all-stores', $link);
                        foreach ($letterArray as $letter) {
                            $link = config('parser.STORE_URL') . $letter;
                            State::updateState($state, $link);

                            $this->parseStore($link);
                        }
                        State::deleteState($state->stateId);
                    }
                }
                else {
                    $state = State::saveState('stores-by-letter', $link);
                    $this->parseStore($link);
                    State::deleteState($state->stateId);
                }
            }
            else {
                State::RestorePreviousCommandByState($state);
                State::deleteState($state->stateId);
            }
        } catch (Exception $exception) {
            throw new ParsingException('An error occurred while parsing data', 0, $exception);
        }

    }
}
