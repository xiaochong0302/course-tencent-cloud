<?php

namespace App\Http\Home\Controllers;

/**
 * @RoutePrefix("/user")
 */
class UserController extends Controller
{

    /**
     * @Get("/{id}", name="home.user.show")
     */
    public function showAction($id)
    {
        
    }

    /**
     * @Get("/{id}/courses", name="home.user.courses")
     */
    public function coursesAction($id)
    {
        
    }

    /**
     * @Get("/{id}/following", name="home.user.following")
     */
    public function followingAction($id)
    {
        
    }

    /**
     * @Get("/{id}/followers", name="home.user.followers")
     */
    public function followersAction($id)
    {
        
    }

    /**
     * @Post("/{id}/follow", name="home.user.follow")
     */
    public function followAction($id)
    {
        
    }

    /**
     * @Post("/{id}/unfollow", name="home.user.unfollow")
     */
    public function unfollowAction($id)
    {
        
    }

    /**
     * @Post("/{id}/message", name="home.user.message")
     */
    public function messageAction($id)
    {
        
    }

}
