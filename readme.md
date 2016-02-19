# Laravel > 5.2 Route Filter

## 1) Register the FilterServiceProvider

Add the following to your `config/app.php`.

```PHP

return [

    . . .

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [

        . . .

        /*
         * The FilterServiceProvider needs to be inserted BEFORE App\Providers\RouteServiceProvider::class,
         */
        Onvard\Filter\FilterServiceProvider::class,

        App\Providers\RouteServiceProvider::class,
    ],

    . . .

];

```

## 2) Adding the Middleware

Add the `filter` Middleware to `middlewareGroups` and `routeMiddleware` in `app/Http/Kernel.php`.
```PHP
. . .

protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
        ],

        'api' => [
            'throttle:60,1',
        ],

        'filter' => [
            \Onvard\Filter\Middleware\ApplyFilters::class,
        ],

. . .

    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'filter' => \Onvard\Filter\Middleware\ApplyFilters::class,
    ];

. . .
```

## 3) Publish the Provider

Execute:

```
php artisan vendor:publish --provider="Onvard\Filter\FilterServiceProvider" --tag=filters
```

If the Provider doesn't get recognized, you maybe need to run `php artisan clear:config` and run it again.

## 4) Using the Middleware

This is an Example of 'How To Use' the Middleware.

The first Example shows the Usage of the `minifyHTML` Filter for a `Route::group` to minify the HTML Output after it is generated.

The second Example shows the Usage of the `functionName` Filter for a single `Route` before and after it is requested.

```PHP
. . .

Route::group(['middleware' => ['web','filter'], 'filter' => [ 'after' => [ 'minifyHTML' ] ] ],function () {
    Route::get( '/',
        [
            'middleware' => ['filter'],
            'filter' => [
                'before' => [
                    'functionName'
                ],
                'after' => [
                    'functionName'
                ]
            ],
            function () {
                return view('welcome');
            }
        ]
    );
});

. . .
```

## 5) Adding more Filters

You can find Filters in `app/Filters/Filter.php` and add what you need.

If a Filter doesn't get recognized, you maybe need to run `php artisan clear-compiled` and `php artisan optimize`.

# LICENSE

[MIT LICENSE](LICENSE)