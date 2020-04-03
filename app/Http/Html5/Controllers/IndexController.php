<?php

namespace App\Http\Html5\Controllers;

/**
 * @RoutePrefix("/mobile")
 */
class IndexController extends Controller
{

    /**
     * @Get("/", name="mobile.index")
     */
    public function indexAction()
    {

    }

    /**
     * @Get("/routes", name="mobile.routes")
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

        return $this->jsonSuccess(['routes' => $definitions]);
    }

}
