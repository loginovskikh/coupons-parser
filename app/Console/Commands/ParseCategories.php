<?php

namespace App\Console\Commands;

use App\Classes\CategoriesParser;
use Exception;
use Illuminate\Console\Command;

require_once ('/var/www/coupons-parser/app/modules/Parser/conf/conf.php');

class ParseCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:categories {--link='.CATEGORY_URL.'}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse categories from website';

    protected $parser;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->parser = new CategoriesParser();
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws Exception
     */
    public function handle()
    {
        try {
            $parse = $this->parser->parseCategories($this->option('link')) ? true : false;
            $message = $parse ? 'Categories was parsed' : 'Empty parsed page';
            echo $message . PHP_EOL;
            echo 'Success' . PHP_EOL;
            return true;
        } catch (Exception $e) {
            echo $e->getMessage() . PHP_EOL;
            return false;
        }


    }
}
