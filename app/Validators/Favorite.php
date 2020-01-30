<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Repos\Course as CourseRepo;
use App\Repos\CourseFavorite as FavoriteRepo;
use App\Repos\User as UserRepo;

class Favorite extends Validator
{

    /**
     * @param int $courseId
     * @param int $userId
     * @return \App\Models\CourseFavorite
     * @throws BadRequestException
     */
    public function checkFavorite($courseId, $userId)
    {
        $favoriteRepo = new FavoriteRepo();

        $favorite = $favoriteRepo->findFavorite($courseId, $userId);

        if (!$favorite) {
            throw new BadRequestException('favorite.not_found');
        }

        return $favorite;
    }

    public function checkCourseId($courseId)
    {
        $value = $this->filter->sanitize($courseId, ['trim', 'int']);

        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($value);

        if (!$course) {
            throw new BadRequestException('favorite.course_not_found');
        }

        return $course->id;
    }

    public function checkUserId($userId)
    {
        $value = $this->filter->sanitize($userId, ['trim', 'int']);

        $userRepo = new UserRepo();

        $user = $userRepo->findById($value);

        if (!$user) {
            throw new BadRequestException('favorite.user_not_found');
        }

        return $user->id;
    }

    public function checkIfFavorited($courseId, $userId)
    {
        $favoriteRepo = new FavoriteRepo();

        $favorite = $favoriteRepo->findFavorite($courseId, $userId);

        if ($favorite) {
            throw new BadRequestException('favorite.has_favorited');
        }
    }

}
