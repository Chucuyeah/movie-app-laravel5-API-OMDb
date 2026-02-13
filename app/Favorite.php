<?php

namespace App;

class Favorite
{
    /**
     * Path to favorites JSON storage
     */
    public static function getStoragePath()
    {
        $path = storage_path('app/favorites');
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
        return $path;
    }

    /**
     * Get favorites file path for a user
     */
    public static function getFilePath($user)
    {
        return self::getStoragePath() . '/' . md5($user) . '.json';
    }

    /**
     * Read favorites from JSON file
     */
    public static function readFavorites($user)
    {
        $file = self::getFilePath($user);
        if (!file_exists($file)) {
            return [];
        }
        $content = file_get_contents($file);
        return json_decode($content, true) ?? [];
    }

    /**
     * Write favorites to JSON file
     */
    public static function writeFavorites($user, $favorites)
    {
        $file = self::getFilePath($user);
        file_put_contents($file, json_encode($favorites, JSON_PRETTY_PRINT));
    }

    /**
     * Get all favorites for a user
     */
    public static function where($column, $value)
    {
        // Only support 'user' column for now
        if ($column !== 'user') {
            return new static();
        }

        return new FavoriteCollection($value);
    }

    /**
     * Create a new favorite
     */
    public static function create(array $data)
    {
        $user = $data['user'];
        $favorites = self::readFavorites($user);

        $newFavorite = [
            'id' => uniqid(),
            'user' => $user,
            'imdbID' => $data['imdbID'],
            'title' => $data['title'],
            'year' => $data['year'],
            'poster' => $data['poster'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $favorites[] = $newFavorite;
        self::writeFavorites($user, $favorites);

        return new FavoriteItem($newFavorite);
    }

    /**
     * Check if a movie is favorited by user
     */
    public static function isFavorited($user, $imdbID)
    {
        $favorites = self::readFavorites($user);
        foreach ($favorites as $fav) {
            if ($fav['imdbID'] === $imdbID) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get favorite by user and imdbID
     */
    public static function getFavorite($user, $imdbID)
    {
        $favorites = self::readFavorites($user);
        foreach ($favorites as $fav) {
            if ($fav['imdbID'] === $imdbID) {
                return new FavoriteItem($fav);
            }
        }
        return null;
    }
}

/**
 * Collection class for handling query builder
 */
class FavoriteCollection
{
    private $user;
    private $favorites;
    private $orderBy = null;
    private $orderDir = 'asc';

    public function __construct($user)
    {
        $this->user = $user;
        $this->favorites = Favorite::readFavorites($user);
    }

    public function orderBy($column, $direction = 'asc')
    {
        $this->orderBy = $column;
        $this->orderDir = $direction;
        return $this;
    }

    public function get()
    {
        $favorites = $this->favorites;

        // Apply ordering
        if ($this->orderBy) {
            usort($favorites, function ($a, $b) {
                $dir = $this->orderDir === 'desc' ? -1 : 1;
                $valA = $a[$this->orderBy] ?? '';
                $valB = $b[$this->orderBy] ?? '';
                if ($this->orderBy === 'created_at') {
                    return (strtotime($valA) <=> strtotime($valB)) * $dir;
                }
                return strcmp($valA, $valB) * $dir;
            });
        }

        $items = array_map(function ($fav) {
            return new FavoriteItem($fav);
        }, $favorites);

        return new FavoriteResult($items);
    }

    public function first()
    {
        if (empty($this->favorites)) {
            return null;
        }
        return new FavoriteItem(array_values($this->favorites)[0]);
    }

    public function where($column, $value)
    {
        $this->favorites = array_filter($this->favorites, function ($fav) use ($column, $value) {
            // Handle both numeric and string comparisons
            $favValue = $fav[$column] ?? null;
            return $favValue == $value;
        });
        $this->favorites = array_values($this->favorites); // Re-index
        return $this;
    }

    public function count()
    {
        return count($this->favorites);
    }
}

/**
 * Single favorite item class
 */
class FavoriteItem
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function __get($key)
    {
        return $this->data[$key] ?? null;
    }

    public function __isset($key)
    {
        return isset($this->data[$key]);
    }

    public function delete()
    {
        $user = $this->data['user'];
        $imdbID = $this->data['imdbID'];

        $favorites = Favorite::readFavorites($user);
        $favorites = array_filter($favorites, function ($fav) use ($imdbID) {
            return $fav['imdbID'] !== $imdbID;
        });
        $favorites = array_values($favorites); // Re-index array
        Favorite::writeFavorites($user, $favorites);
    }

    public function toArray()
    {
        return $this->data;
    }
}

/**
 * Result collection that can be iterated and counted
 */
class FavoriteResult implements \IteratorAggregate, \Countable
{
    private $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }

    public function count()
    {
        return count($this->items);
    }
}
