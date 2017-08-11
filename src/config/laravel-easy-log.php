<?php

return [
	/*
	  |--------------------------------------------------------------------------
	  | DB-Connection
	  | if you wanna use a seperate connection set 'use_default_connection' to false & fill out the below variables
	  |--------------------------------------------------------------------------
	 */
	'db' => [
		'logging_enabled' => FALSE,
		'bubble' => TRUE,
		'use_default_connection' => FALSE,
		'driver' => 'mysql',
		'host' => 'localhost',
		'database' => 'DBNAME',
		'username' => 'DBUSER',
		'password' => 'DBPASSWORD',
		'port' => '3306',
		/*
		  |--------------------------------------------------------------------------
		  | Table
		  |--------------------------------------------------------------------------
		  | Define here the name of the table in which should be logged. Also you can specify here some extra columns.
		  | These are the same like you state in the Monolog-context, e.g. you can name here your app.
		  | Hint: When you change the columns, you have to restart the server.
		  | i.e.:
		  |
		  | Log::debug("CONTEXT log in DEBUG", array('col1' => 'Hi, I am a context log.'));
		  | Log::debug("CONTEXT log in DEBUG", array('col2' => 'Hi, I am a context log.'));
		  |
		  | Log::debug("CONTEXT log in DEBUG", array(
		  |     'col1' => 'Whatever, I am a context log in col1.',
		  |     'col2' => 'Whatever, I am a context log in col2.'
		  | ));
		 */
		'app' => env('APP_NAME', 'Laravel'),
		'table' => 'lel',
		'columns' => ['col1', 'col2'],
		'view' => [
			'big' => TRUE,
			'message_column_width' => '10%',
			'message_max_length' => '250',
			'result-number' => 50
		]
	],
	/*
	  |--------------------------------------------------------------------------
	  | FILES
	  |--------------------------------------------------------------------------
	  | log_levels:
	  |   for each log-level inside the array you name, an extra file is used
	  |   levels: debug, info, notice, warning, error, critical, alert, emergency
	  |
	  | output_format:
	  |   context - you can add the subcontext to the context variable, e.g.:
	  |
	  |   "[%datetime%] %channel%.%level_name%: %message% \t%context.message% \t[%extra.server%]\n"
	  |   Log::debug("CONTEXT log in DEBUG", array('message' => 'Hi, I am a context log.'));
	 */
	'file' => [
		'logging_enabled' => TRUE,
		'bubble' => TRUE,
		'log_levels' => ['debug', 'info', 'error'],
		'date_format' => 'd.m.Y, G:i:s',
		'output_format' => "[%datetime%] %channel%.%level_name%: %message% \t%context% \t[%extra.server%]\n",
		'log_dir' => storage_path() . '/logs/',
		// when app.log is set to 'daily'
		'daily' => [
			'log_max_files' => env('LOG_MAX_FILES', 5),
		],
		// when app.log is set to 'syslog'
		'syslog' => [
			'ident' => env('APP_NAME', 'Laravel'),
			'facility' => 'local6'
		]
	],
	/*
	  |--------------------------------------------------------------------------
	  | STD
	  |--------------------------------------------------------------------------
	  | if you like you can log to STDOUT & STDERR
	 */
	'std' => [
		'out' => [
			'logging_enabled' => FALSE,
			'bubble' => true,
			'log_level' => 'error',
			'date_format' => 'd.m.Y, G:i:s',
			'output_format' => "[%datetime%] %channel%.%level_name%: %message% \t%context% \t[%extra.server%]\n",
		],
		'err' => [
			'logging_enabled' => FALSE,
			'bubble' => true,
			'log_level' => 'debug',
			'date_format' => 'd.m.Y, G:i:s',
			'output_format' => "[%datetime%] %channel%.%level_name%: %message% \t%context% \t[%extra.server%]\n",
		],
	],
	/*
	  |--------------------------------------------------------------------------
	  | MAIL
	  |--------------------------------------------------------------------------
	  | send an email when an error-level is reached. Don't forget to fill out 'to', 'from' & the 'subject'
	 */
	'mail' => [
		'logging_enabled' => FALSE,
		'bubble' => true,
		'log_level' => 'error',
		'from' => 'who@ever.com',
		'to' => 'whom@ever.com',
		'subject' => 'whatever',
		'maxColumnWidth' => 70, // The maximum column width that the message lines will have
		'date_format' => 'd.m.Y, G:i:s',
		'output_format' => "[%datetime%] %channel%.%level_name%: %message% \t%context% \t[%extra.server%]\n",
	]
];
