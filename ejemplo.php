<?php
View::composer('partials.page.scripts', function ($view) {
    $api                = app('Dingo\Api\Routing\Router');
    $apiRoutes          = $api->getRoutes();
    $apiNameKeyedRoutes = collect([]);
    foreach ($apiRoutes as $version => $routesCollection) {
        $versionRoutes = collect($routesCollection->getRoutes());
        $versionNameKeyedRoutes = $versionRoutes
            ->filter(function ($route) {
                $action = $route->getAction();
                return $action && isset($action['as']) && $action['as'];
            })
            ->mapWithKeys(function ($route) use ($version) {
                $action = $route->getAction();
                $name   = $version . '.' . $action['as'];
                $data   = collect($route)->only(['uri', 'methods'])->put('domain', $route->domain());
                return [$name => $data];
            });
        $apiNameKeyedRoutes = $apiNameKeyedRoutes->merge($versionNameKeyedRoutes);
    }
    $view->with('apiZiggyRoutes', $apiNameKeyedRoutes);
});
?>