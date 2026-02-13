<?php

namespace App\Http\Controllers;

use App\Favorite;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        $apiKey = "205100";

        $search = $request->get('search', '');
        $year = $request->get('year', '');
        $type = $request->get('type', '');
        $genre = $request->get('genre', '');
        $page = $request->get('page', 1);

        // Jika tidak ada search, tampilkan film terbaru tahun ini
        if (empty($search)) {
            $search = 'movie';
            if (empty($year)) {
                $year = date('Y'); // Tahun berjalan
            }
        }

        $searchEncoded = urlencode($search);

        // Build URL with supported OMDB parameters
        $url = "http://www.omdbapi.com/?apikey={$apiKey}&s={$searchEncoded}&page={$page}";

        if ($year) {
            $url .= "&y=" . urlencode($year);
        }

        if ($type) {
            $url .= "&type=" . urlencode($type);
        }

        $response = file_get_contents($url);
        $movies = json_decode($response, true);

        // Filter by genre client-side (OMDB doesn't support genre in search)
        if ($genre && isset($movies['Search'])) {
            $genre = strtolower($genre);
            $movies['Search'] = array_filter($movies['Search'], function ($movie) use ($genre) {
                // Note: Search results don't include genre, so we need to fetch each movie detail
                // For performance, we'll skip genre filtering in search results
                // or fetch details for each movie (slower)
                return true; // Placeholder - genre filtering limited in search
            });
        }

        // Calculate pagination info
        $totalResults = isset($movies['totalResults']) ? (int)$movies['totalResults'] : 0;
        $totalPages = $totalResults > 0 ? ceil($totalResults / 10) : 1; // OMDb returns 10 per page

        // Check favorite status for each movie
        $user = session('user');
        $favoritedMovies = [];
        if ($user && isset($movies['Search'])) {
            foreach ($movies['Search'] as $movie) {
                $favoritedMovies[$movie['imdbID']] = Favorite::isFavorited($user, $movie['imdbID']);
            }
        }

        return view('movies.index', compact('movies', 'search', 'year', 'type', 'genre', 'page', 'totalPages', 'totalResults', 'favoritedMovies'));
    }

    /**
     * Load more movies for infinite scroll (AJAX)
     */
    public function loadMore(Request $request)
    {
        $apiKey = "205100";

        $search = $request->get('search', '');
        $year = $request->get('year', '');
        $type = $request->get('type', '');
        $page = $request->get('page', 1);

        if (!$search) {
            return response()->json([
                'movies' => [],
                'favoritedMovies' => [],
                'hasMore' => false
            ]);
        }

        $url = "http://www.omdbapi.com/?apikey={$apiKey}&s=" . urlencode($search) . "&page={$page}";

        if ($year) {
            $url .= "&y=" . urlencode($year);
        }

        if ($type) {
            $url .= "&type=" . urlencode($type);
        }

        $response = @file_get_contents($url);

        if ($response === false) {
            return response()->json([
                'movies' => [],
                'favoritedMovies' => [],
                'hasMore' => false
            ]);
        }

        $movies = json_decode($response, true);

        // 🔥 Kalau OMDb kirim error
        if (
            !$movies ||
            !isset($movies['Response']) ||
            $movies['Response'] === "False" ||
            !isset($movies['Search'])
        ) {
            return response()->json([
                'movies' => [],
                'favoritedMovies' => [],
                'hasMore' => false
            ]);
        }

        $user = session('user');
        $favoritedMovies = [];

        foreach ($movies['Search'] as $movie) {
            $favoritedMovies[$movie['imdbID']] =
                $user ? Favorite::isFavorited($user, $movie['imdbID']) : false;
        }

        return response()->json([
            'movies' => $movies['Search'],
            'favoritedMovies' => $favoritedMovies,
            'hasMore' => count($movies['Search']) === 10
        ]);
    }

    public function detail(Request $request, $id)
    {
        $apiKey = "205100";

        $url = "http://www.omdbapi.com/?apikey={$apiKey}&i={$id}";

        $response = @file_get_contents($url);

        // Capture search params from query string for back button
        $searchParams = [
            'search' => $request->get('search', ''),
            'year' => $request->get('year', ''),
            'type' => $request->get('type', ''),
            'genre' => $request->get('genre', ''),
            'page' => $request->get('page', 1)
        ];

        if ($response === false) {
            return view('movies.detail', [
                'movie' => null,
                'error' => 'Failed to fetch movie detail',
                'searchParams' => $searchParams
            ]);
        }

        $movie = json_decode($response, true);

        if (!$movie || (isset($movie['Response']) && $movie['Response'] === "False")) {
            return view('movies.detail', [
                'movie' => null,
                'error' => $movie['Error'] ?? 'Movie not found',
                'searchParams' => $searchParams
            ]);
        }

        $user = session('user');
        $isFavorited = false;

        if ($user && isset($movie['imdbID'])) {
            $isFavorited = Favorite::isFavorited($user, $movie['imdbID']);
        }

        return view('movies.detail', compact('movie', 'isFavorited', 'searchParams'));
    }
}
