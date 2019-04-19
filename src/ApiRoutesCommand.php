<?php
namespace CbxTechCorp\LumenApiRoutesList;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Symfony\Component\Console\Input\InputOption;

class ApiRoutesCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'api:routes';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display all registered API routes.';
    /**
     * The table headers for the command.
     *
     * @var array
     */
    protected $headers = array('Host', 'Method', 'URI', 'Name', 'Action', 'Protected', "Version");
    /**
     * The columns to display when using the "compact" flag.
     *
     * @var array
     */
    protected $compactColumns = ['host', 'method', 'action'];
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->displayRoutes($this->getRoutes());
    }
    /**
     * Compile the routes into a displayable format.
     *
     * @return array
     */
    protected function getRoutes()
    {   
        $rows = [];
        $api = app('Dingo\Api\Routing\Router');
		//dump($api);
		$routes = $api->getRoutes();
		//dump($routes);

		foreach ($routes as $version => $routeCollection) {
			$versionRoutes = collect($routeCollection->getRoutes());
			foreach ($versionRoutes as $ruta) {
                $data = $ruta->getAction();
                $rows[] = [
                    "host"      => "",
                    "method"    => implode("|",$data["methods"]),
                    "uri"       => $data["uri"],
                    "name"      => (empty($data["as"]))? "":$data["as"],
                    "action"    => $data["uses"],
                    "protected" => "",
                    "version"   => $version
                ];
			}
		}
        return $this->pluckColumns($rows);
    }
    /**
     * Remove unnecessary columns from the routes.
     *
     * @param  array  $routes
     * @return array
     */
    protected function pluckColumns(array $routes)
    {
        return array_map(function ($route) {
            return Arr::only($route, $this->getColumns());
        }, $routes);
    }
    /**
     * Display the route information on the console.
     *
     * @param  array  $routes
     * @return void
     */
    protected function displayRoutes(array $routes)
    {
        if (empty($routes)) {
            return $this->error("Your application doesn't have any routes.");
        }
        $this->table($this->getHeaders(), $routes);
    }
    /**
     * Get the table headers for the visible columns.
     *
     * @return array
     */
    protected function getHeaders()
    {
        return Arr::only($this->headers, array_keys($this->getColumns()));
    }
    /**
     * Get the column names to show (lowercase table headers).
     *
     * @return array
     */
    protected function getColumns()
    {
        $availableColumns = array_map('lcfirst', $this->headers);
        if ($this->option('compact')) {
            return array_intersect($availableColumns, $this->compactColumns);
        }
        return $availableColumns;
    }
    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            [
                'compact',
                'c',
                InputOption::VALUE_NONE,
                'Show reduced version'
            ]
        ];
    }
}