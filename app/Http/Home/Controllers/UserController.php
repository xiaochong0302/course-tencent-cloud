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
     * @Get("/{id:[0-9]+}/following", name="home.user.following")
     */
    public function followingAction($id)
    {
        
    }

    /**
     * @Get("/{id:[0-9]+}/followers", name="home.user.followers")
     */
    public function followersAction($id)
    {
        
    }

    /**
     * @Post("/{id:[0-9]+}/follow", name="home.user.follow")
     */
    public function followAction($id)
    {
        
    }

    /**
     * @Post("/{id:[0-9]+}/unfollow", name="home.user.unfollow")
     */
    public function unfollowAction($id)
    {
        
    }

    /**
     * @Post("/{id:[0-9]+}/message", name="home.user.message")
     */
    public function messageAction($id)
    {
        
    }

}
