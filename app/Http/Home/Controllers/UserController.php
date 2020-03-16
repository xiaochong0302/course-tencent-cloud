<?php

namespace App\Http\Home\Controllers;

/**
 * @RoutePrefix("/user")
 */
class UserController extends Controller
{

    /**
     * @Get("/{id:[0-9]+}", name="home.user.show")
     */
    public function showAction($id)
    {
        
    }

    /**
     * @Get("/{id:[0-9]+}/courses", name="home.user.courses")
     */
    public function coursesAction($id)
    {
        
    }

    /**
     * @Post("/{id:[0-9]+}/message", name="home.user.message")
     */
    public function messageAction($id)
    {
        
    }

}
