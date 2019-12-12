<?php

namespace App\Http\Api\Controllers;

/**
 * @RoutePrefix("/api")
 */
class IndexController extends Controller
{

    /**
     * @Get("/", name="api.index")
     */
    public function indexAction()
    {

    }

    /**
     * @Get("/routes", name="api.routes")
     */
    public function routesAction()
    {
        $definitions = [];

        $routes = $this->router->getRoutes();

        foreach ($routes as $route) {
            if (strpos($route->getPattern(), '/api') !== false) {
                $definitions[] = [
                    'pattern' => $route->getPattern(),
                    'methods' => $route->getHttpMethods(),
                ];
            }
        }

        dd($definitions);
    }

}
