<?php

namespace App\Console\Commands;

use App\Classes\StoresParser;
use Exception;
use Illuminate\Console\Command;

require_once ('/var/www/coupons-parser/app/modules/Parser/conf/conf.php');

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
                            {--l= : Letter of alphabet category }';

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

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws Exception
     */
    public function handle()
    {
        if($this->hasOption('link')) $link = $this->option('link');
        if($this->hasOption('l')) $link = STORE_URL . $this->option('l');
        echo $link . PHP_EOL;
        try {
            $parse = $this->parser->parseStores($link) ? true : false;
            $message = $parse ? 'Stores was parsed' : 'Empty parsed page';
            echo $message . PHP_EOL;
            echo 'Success' . PHP_EOL;
            return true;
        } catch (Exception $e) {
            echo $e->getMessage() . PHP_EOL;
            return false;
        }
    }
}
