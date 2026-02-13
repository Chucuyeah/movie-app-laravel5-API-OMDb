<?php

namespace App\Http\Controllers;

use App\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /**
     * Display list of favorite movies
     */
    public function index(Request $request)
    {
        $user = session('user');

        if (!$user) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu!');
        }

        $favorites = Favorite::where('user', $user)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('favorites.index', compact('favorites'));
    }

    /**
     * Add movie to favorites
     */
    public function add(Request $request)
    {
        $user = session('user');

        if (!$user) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu!');
        }

        $imdbID = $request->input('imdbID');
        $title = $request->input('title');
        $year = $request->input('year');
        $poster = $request->input('poster');

        // Check if already favorited
        if (Favorite::isFavorited($user, $imdbID)) {
            return redirect()->back()->with('info', 'Film sudah ada di favorite!');
        }

        // Add to favorites
        Favorite::create([
            'user' => $user,
            'imdbID' => $imdbID,
            'title' => $title,
            'year' => $year,
            'poster' => $poster,
        ]);

        return redirect()->back()->with('success', 'Film berhasil ditambahkan ke favorite!');
    }

    /**
     * Remove movie from favorites
     */
    public function remove(Request $request, $id)
    {
        $user = session('user');

        if (!$user) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu!');
        }

        $favorite = Favorite::where('user', $user)
            ->where('id', $id)
            ->first();

        if (!$favorite) {
            return redirect()->back()->with('error', 'Favorite tidak ditemukan!');
        }

        $favorite->delete();

        return redirect()->back()->with('success', 'Film berhasil dihapus dari favorite!');
    }

    /**
     * Toggle favorite (add or remove)
     */
    public function toggle(Request $request)
    {
        $user = session('user');

        if (!$user) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu!');
        }

        $imdbID = $request->input('imdbID');
        $title = $request->input('title');
        $year = $request->input('year');
        $poster = $request->input('poster');

        $existing = Favorite::getFavorite($user, $imdbID);

        if ($existing) {
            // Remove
            $existing->delete();
            return redirect()->back()->with('success', 'Film berhasil dihapus dari favorite!');
        } else {
            // Add
            Favorite::create([
                'user' => $user,
                'imdbID' => $imdbID,
                'title' => $title,
                'year' => $year,
                'poster' => $poster,
            ]);
            return redirect()->back()->with('success', 'Film berhasil ditambahkan ke favorite!');
        }
    }
}
