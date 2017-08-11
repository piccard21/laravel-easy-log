<?php

namespace Piccard\LEL\Test;

use Piccard\LEL\LELServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase {
	/**
	 * Load package service provider
	 * @param  \Illuminate\Foundation\Application $app
	 * @return Piccard\LEL\LELServiceProvider
	 */
	protected function getPackageProviders($app) {
		return [LELServiceProvider::class];
	}

	/**
	 * Setup the test environment.
	 */
	public function setUp()
	{
	    parent::setUp(); 

	    $this->artisan('vendor:publish', [
	        '--tag' => 'lel' 
	    ]);

	}
} 