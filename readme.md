# Lumen API Route List

provee el comando api:routes de laravel en lumen

## Instalation

How to add this comand to your lumen project

### Add Dependency

Add the dependency as dev for deployment optimization.

``` bash
composer require CbxTechCorp/lumen-api-routes-list --dev
```

### Enable The Command

Add this lines to bootstrap/app.php to load the command.

``` php
//list api routes
if (class_exists(CbxTechCorp\LumenApiRoutesList\ApiRoutesCommandServiceProvider::class)) {
    $app->register(CbxTechCorp\LumenApiRoutesList\ApiRoutesCommandServiceProvider::class);
};
```

### Check the Result

```bash
php artisan serve
```