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


#### Custom-Level

- if you don't wanna have Laravel logging through Laravel-Easy-Log, but you wanna do it on your own, you have to create an instance of Laravel-Easy-Log and use this one

```
$logger = \Piccard\LEL\LEL::configureMonolog("channel-name"); 
$logger->info("Whatever you want to log");
Log::debug("CONTEXT log in DEBUG", array('col1' => 'Hi, I am a context log.'));    
Log::debug("CONTEXT log in DEBUG", array(
        'col1' => 'Hi, I am a context log.',
        'col2' => 'Hi, I am a context log.'
    ));
```


### Configure Laravel-Easy-Log
Open up **config/laravel-easy-log** and enable the handlers you want to use

#### DB

##### Log-View

#### Files

#### StdOut & StdErr

#### Mail

