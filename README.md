# Paagez Modular

Modular for laravel ^9 ^10
-  spatie/laravel-permission
-  silviolleite/laravelpwa
-  biscolab/laravel-recaptcha
-  Boostrap 5.3
-  JQuery

### Install

`composer require monsterz/paagez`

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