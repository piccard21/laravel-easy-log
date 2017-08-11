# laravel-easy-log

[Markdown-Cheatsheet](https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet)

## Provider

- add  to **config/app.php**

```
Piccard\LEL\LELServiceProvider::class
``` 


## Configure Monologe

add in **bootstrap/app.php** just before **return $app;**

```
$app->configureMonologUsing(function ($logger) {
  \Piccard\LEL\LEL::configureMonolog($logger);
});
```

## Prepare everything
```
cd PACKAGE-INSTALLATION
```

### Install packages
```
composer install
npm install
```


### Trigger webpack
```
npm run watch
```


### Publish vendor
```
cd LARAVEL-INSTALLATION
php artisan vendor:publish --tag=lel --force
```

## configure LEL

- in **config/lara-mysql-config.php**
```
    'db' => [
        'logging_enabled' => TRUE,
        'bubble' => TRUE,
        'use_default_connection' => FALSE,
        'host' => 'localhost',
        'database' => 'testme',
        'username' => 'root',
        'password' => '****',
        'port' => '3306',
```



## Test it
add to **routes/web.php**
```
Route::get('/', function () {

    Log::debug("debug");
    Log::info("info");
    Log::notice("notice");
    Log::warning("warning");
    Log::error("error");
    Log::critical("critical");
    Log::alert("alert");
    Log::emergency("emergency");

    Log::debug("CONTEXT log in DEBUG", array('col1' => 'Hi, I am a context log.'));
    Log::debug("CONTEXT log in DEBUG", array('col2' => 'Hi, I am a context log.'));

    Log::debug("CONTEXT log in DEBUG", array(
        'col1' => 'Hi, I am a context log.',
        'col2' => 'Hi, I am a context log.'
    ));

    return view('welcome');
```
});






