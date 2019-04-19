<?php
namespace CbxTechCorp\LumenApiRoutesList;
use Illuminate\Support\ServiceProvider;
class ApiRoutesCommandServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(){
        $this->commands('CbxTechCorp\LumenApiRoutesList\ApiRoutesCommand');
    }
}