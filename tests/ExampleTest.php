<?php

namespace Piccard\LEL\Test;

use Monolog\Logger;
use Piccard\LEL\LEL;


class ExampleTest extends TestCase
{
    protected $logger;   
    protected $app;
    protected $config;


    protected function getEnvironmentSetUp($app) 
    { 
        $app['config']->set('laravel-easy-log.db.table', 'aNewTableName');

        $this->app = $app;

    }

    
    /** @test **/
    public function testBasicTest()
    {
    	$this->createLogger(); 

        //var_dump($this->app['config']->get('laravel-easy-log'));

        $this->assertEquals('aNewTableName', $this->app['config']->get('laravel-easy-log.db.table'));
        $this->assertEquals('whom@ever.com', $this->app['config']->get('laravel-easy-log.mail.to'));
    }


    public function createLogger($channel='lel') {
        $this->logger = new Logger($channel);
		LEL::configureMonolog($this->logger);
    }  
 
}
