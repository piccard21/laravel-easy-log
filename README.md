# laravel-easy-log

## Installation

```
composer require cafe-serendipity/laravel-easy-log
``` 


### AddProvider 

- add  to **config/app.php** 

```
Piccard\LEL\LELServiceProvider::class
``` 
### Publish files

- having the configuration-file, views & controller available in your application, you have to publish them

```
php artisan vendor:publish --tag=lel --force
```

### Decide how to log

#### Application-Level

- if you wanna log on application-level, means also laravel is logging through Laravel-Easy-Log, add the following to **bootstrap/app.php** just before  **return  $app;** 

```
$app->configureMonologUsing(function ($logger) {
  \Piccard\LEL\LEL::configureMonolog($logger);
});
```

```
Log::debug("CONTEXT log in DEBUG", array('col1' => 'Hi, I am a context log.'));    
Log::info("CONTEXT log in INFO", array(
        'col1' => 'Hi, I am a context log.',
        'col2' => 'Hi, I am a context log.'
    ));
```


#### Custom-Level

- if you don't wanna have Laravel logging through Laravel-Easy-Log, but you wanna do it on your own, you have to create an instance of Laravel-Easy-Log and use this one

```
$logger = \Piccard\LEL\LEL::configureMonolog("channel-name"); 
$logger->info("Whatever you want to log");
```

```
$logger->debug("CONTEXT log in DEBUG", array('col1' => 'Hi, I am a context log.'));    
$logger->info("CONTEXT log in INFO", array(
        'col1' => 'Hi, I am a context log.',
        'col2' => 'Hi, I am a context log.'
    ));
```


### Configure Laravel-Easy-Log
Open up **config/laravel-easy-log** and enable the handlers you want to use. Basically you can use the same options like in [Monolog](https://github.com/Seldaek/monolog).

#### DB
- **use_default_connection** You can use your default DB-connection or define a custom one. 
- **app** is just another column, if you use different Laravel-applications and the same logging-server, so you can filter them better out. 
- **table** the table which will be created for logging
- **columns** define here some extra columns, which you can use when you log, which is similar to Monolog's context logging

```
Log::info("CONTEXT log in DEBUG", array('col1' => 'Hi, I am a context log.'));
``` 

##### Log-View
To get to the view of your DB-Logs goto the route **/lel**

#### Files
- **log_levels** define here which files you want foreach log-level

#### StdOut & StdErr
- NOT TESTED YET

#### Mail
- NOT TESTED YET

