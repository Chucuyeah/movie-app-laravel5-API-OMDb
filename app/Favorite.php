<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $fillable = [
        'user',
        'imdbID',
        'title',
        'year',
        'poster',
    ];

    /**
     * Check if a movie is favorited by user
     */
    public static function isFavorited($user, $imdbID)
    {
        return self::where('user', $user)
            ->where('imdbID', $imdbID)
            ->exists();
    }

    /**
     * Get favorite by user and imdbID
     */
    public static function getFavorite($user, $imdbID)
    {
        return self::where('user', $user)
            ->where('imdbID', $imdbID)
            ->first();
    }
}
