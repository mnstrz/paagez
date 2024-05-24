## Installation


`composer require monsterz/paagez`

add to `config\app.php`

```
'providers' => [
    ...
    Biscolab\ReCaptcha\ReCaptchaServiceProvider::class,
    Monsterz\Paagez\Providers\PaagezServiceProvider::class
];

'aliases' => [
    ...
    'ReCaptcha' => Biscolab\ReCaptcha\Facades\ReCaptcha::class,
];
```

run `php artisan paagez:publish`
run `php artisan paagez:install`
go to `/pz-admin/` to continue intallation