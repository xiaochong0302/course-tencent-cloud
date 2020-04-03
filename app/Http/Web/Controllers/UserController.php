<?php

namespace App\Http\Web\Controllers;

/**
 * @RoutePrefix("/user")
 */
class UserController extends Controller
{

    /**
     * @Get("/{id:[0-9]+}", name="web.user.show")
     */
    public function showAction($id)
    {
        
    }

    /**
     * @Get("/{id:[0-9]+}/courses", name="web.user.courses")
     */
    public function coursesAction($id)
    {
        
    }

    /**
     * @Post("/{id:[0-9]+}/message", name="web.user.message")
     */
    public function messageAction($id)
    {
        
    }

}
