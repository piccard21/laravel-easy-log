<?php

namespace Orchestra\Testbench\Tests\Databases;

use Monolog\Logger;
use Orchestra\Testbench\TestCase;
use Piccard\LEL\LEL;

class MigrateDatabaseTest extends TestCase {

	protected $logger;

	/**
	 * Setup the test environment.
	 */
	public function setUp() {
		parent::setUp();

		$this->artisan('vendor:publish', ['--tag' => 'lel']);
	}

	/**
	 * Define environment setup.
	 *
	 * @param  \Illuminate\Foundation\Application  $app
	 *
	 * @return void
	 */
	protected function getEnvironmentSetUp($app) {
		$app['config']->set('laravel-easy-log.db.logging_enabled', true);
		$app['config']->set('laravel-easy-log.mail.logging_enabled', true);
		$app['config']->set('laravel-easy-log.db.driver', 'mysql');
		$app['config']->set('laravel-easy-log.db.database', 'testinger');
		$app['config']->set('laravel-easy-log.db.username', 'root');
		$app['config']->set('laravel-easy-log.db.password', 'Startrek,21');
		$app['config']->set('laravel-easy-log.db.table', 'lel-test');

		$this->getLogger();
	}

	/**
	 * Get package provider
	 *
	 * @param  \Illuminate\Foundation\Application  $app
	 *
	 * @return array
	 */
	protected function getPackageProviders($app) {
		return [
			\Piccard\LEL\LELServiceProvider::class,
		];
	}

	/** @test */
	public function vendor_publishing_was_merged_into_config() {
		$this->assertEquals('mysql', config('laravel-easy-log.db.driver'));
		$this->assertEquals('testinger', config('laravel-easy-log.db.database'));
		$this->assertEquals('lel-test', config('laravel-easy-log.db.table'));
	}

	/** @test */
	public function a_logger_has_the_right_number_of_handlers() {
		$this->assertCount(3, $this->logger->getHandlers());
	}

	/** @test */
	public function a_logger_has_the_mysql_handler_when_enabled() {
		$found = (bool) array_filter($this->logger->getHandlers(), function ($v) {
			return get_class($v) === 'Piccard\LEL\MySQLHandler';
		});
		$this->assertTrue($found);
	}

	/**
	 * get a Monolog-instance and configure it by LEL
	 *
	 * @test
	 **/
	public function getLogger() {
		$this->logger = new Logger("lel-test");
		LEL::configureMonolog($this->logger);

		$this->assertInstanceOf('Monolog\Logger', $this->logger);

		return $this->logger;
	}

}
