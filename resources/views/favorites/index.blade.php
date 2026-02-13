<!DOCTYPE html>
<html lang="{{ session('locale', 'id') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Favorites - Movie App</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.3rem;
        }

        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 20px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
        }

        .page-header h1 {
            margin: 0;
            font-size: 2.5rem;
            font-weight: bold;
        }

        .page-header p {
            margin-top: 10px;
            opacity: 0.9;
        }

        .movie-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: 0.3s;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            background: white;
            height: 100%;
        }

        .movie-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }

        .movie-poster {
            height: 380px;
            background: #f0f0f0;
            overflow: hidden;
        }

        .movie-poster img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: 0.3s;
        }

        .movie-card:hover .movie-poster img {
            transform: scale(1.05);
        }

        .movie-body {
            padding: 20px;
        }

        .movie-title {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 10px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .movie-info {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        .btn-custom {
            border-radius: 8px;
            font-weight: 500;
            transition: 0.3s;
        }

        .btn-detail {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
        }

        .btn-detail:hover {
            transform: translateY(-2px);
        }

        .btn-remove {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            border: none;
            color: white;
        }

        .btn-remove:hover {
            transform: translateY(-2px);
        }

        .empty-state {
            text-align: center;
            padding: 80px 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }

        .empty-icon {
            font-size: 5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn-language {
            background: rgba(255,255,255,0.2);
            border: 2px solid rgba(255,255,255,0.5);
            color: white;
            padding: 8px 16px;
            font-size: 0.85rem;
            font-weight: 500;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-language:hover {
            background: rgba(255,255,255,0.3);
            border-color: rgba(255,255,255,0.8);
            color: white;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand text-white" href="/movies">
            <i class="bi bi-film me-2"></i>{{ msg('movie_app') }}
        </a>

        <div class="d-flex align-items-center">
            <a href="/favorites" class="btn btn-outline-light me-3 active">
                <i class="bi bi-heart-fill me-1"></i>{{ msg('my_favorites') }}
            </a>

            @if(session('locale', 'id') == 'id')
                <a href="/language/en" class="btn-language me-3">English</a>
            @else
                <a href="/language/id" class="btn-language me-3">Indonesia</a>
            @endif

            <div class="dropdown">
                <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle me-1"></i>{{ session('user') }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="/logout">
                            <i class="bi bi-box-arrow-right me-2"></i>{{ msg('logout') }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<div class="container py-4">

    <!-- HEADER -->
    <div class="page-header">
        <h1><i class="bi bi-heart-fill me-2"></i>{{ msg('my_favorite_movies') }}</h1>

        @if($favorites->count() > 0)
            <p>{{ msg('favorite_count', ['count' => $favorites->count()]) }}</p>
        @endif
    </div>

    <!-- ALERT -->
    @foreach (['success','info','error'] as $msg)
        @if(session($msg))
            <div class="alert alert-{{ $msg == 'error' ? 'danger' : $msg }} alert-dismissible fade show">
                {{ session($msg) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    @endforeach

    <!-- LIST -->
    @if($favorites->count() > 0)

        <div class="row g-4">
            @foreach($favorites as $favorite)
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="card movie-card">

                        <div class="movie-poster">
                            @if($favorite->poster && $favorite->poster != "N/A")
                                <img src="{{ $favorite->poster }}" alt="{{ $favorite->title }}">
                            @else
                                <div class="d-flex align-items-center justify-content-center h-100">
                                    <i class="bi bi-image text-muted" style="font-size:3rem;"></i>
                                </div>
                            @endif
                        </div>

                        <div class="movie-body">
                            <h5 class="movie-title">{{ $favorite->title }}</h5>

                            <p class="movie-info">
                                <i class="bi bi-calendar3 me-1"></i>{{ $favorite->year }}
                            </p>

                            <p class="movie-info">
                                <i class="bi bi-link-45deg me-1"></i>{{ $favorite->imdbID }}
                            </p>

                            <div class="d-flex gap-2 mt-3">

                                <a href="/movies/{{ $favorite->imdbID }}"
                                   class="btn btn-detail btn-custom flex-fill">
                                    <i class="bi bi-eye me-1"></i>{{ msg('detail_btn') }}
                                </a>

                                <form method="POST"
                                      action="/favorites/remove/{{ $favorite->id }}"
                                      class="flex-fill">
                                    @csrf
                                    <button type="submit"
                                            class="btn btn-remove btn-custom w-100"
                                            onclick="return confirm(@json(__('delete_confirm')))">
                                        <i class="bi bi-trash me-1"></i>{{ msg('delete_btn') }}
                                    </button>
                                </form>

                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

    @else

        <!-- EMPTY -->
        <div class="empty-state">
            <div class="empty-icon">
                <i class="bi bi-heart-break"></i>
            </div>
            <h2 class="mt-3">{{ msg('no_favorites_yet') }}</h2>
            <p class="text-muted">{{ msg('no_favorites_desc') }}</p>
            <a href="/movies" class="btn btn-primary mt-4">
                <i class="bi bi-search me-2"></i>{{ msg('search_movies_now') }}
            </a>
        </div>

    @endif

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
