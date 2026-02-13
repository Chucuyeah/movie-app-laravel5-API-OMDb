<!DOCTYPE html>
<html lang="{{ session('locale', 'id') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $movie['Title'] ?? 'Movie Detail' }} - Movie App</title>
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
        .detail-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            background: white;
            overflow: hidden;
        }
        .poster-section {
            position: relative;
            overflow: hidden;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }
        .poster-section img {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 15px;
        }
        .info-section {
            padding: 30px;
        }
        .movie-title {
            font-weight: 700;
            font-size: 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 20px;
        }
        .info-label {
            color: #6c757d;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-value {
            color: #212529;
            font-size: 1rem;
            margin-bottom: 15px;
        }
        .rating-badge {
            display: inline-block;
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
        }
        .genre-badge {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.85rem;
            margin: 3px;
        }
        .btn-custom {
            border-radius: 10px;
            font-weight: 600;
            padding: 12px 25px;
            transition: all 0.3s ease;
        }
        .btn-favorite {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            color: white;
        }
        .btn-favorite:hover {
            background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(40, 167, 69, 0.3);
        }
        .btn-favorited {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            border: none;
            color: white;
        }
        .btn-favorited:hover {
            background: linear-gradient(135deg, #c82333 0%, #bd1d2b 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(220, 53, 69, 0.3);
        }
        .plot-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }
        .back-link {
            text-decoration: none;
            color: #667eea;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: white;
            border: 2px solid #667eea;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1.1rem;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.15);
        }
        .back-link:hover {
            color: white;
            background: #667eea;
            transform: translateX(-5px) translateY(-2px);
            text-decoration: none;
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.25);
        }
        .back-link:hover {
            color: #764ba2;
            transform: translateX(-5px);
            text-decoration: none;
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
            transition: all 0.3s ease;
        }
        .btn-language:hover {
            background: rgba(255,255,255,0.3);
            border-color: rgba(255,255,255,0.8);
            color: white;
            transform: translateY(-1px);
            text-decoration: none;
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
                <a href="/favorites" class="btn btn-outline-light btn-custom me-3">
                    <i class="bi bi-heart-fill me-1"></i>{{ msg('my_favorites') }}
                </a>
                @if(session('locale', 'id') == 'id')
                    <a href="/language/en" class="btn-language me-3">
                        <span>🇬🇧</span> English
                    </a>
                @else
                    <a href="/language/id" class="btn-language me-3">
                        <span>🇮🇩</span> Indonesia
                    </a>
                @endif
                <div class="dropdown">
                    <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i>{{ session('user') }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="/logout"><i class="bi bi-box-arrow-right me-2"></i>{{ msg('logout') }}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <!-- ALERTS -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(isset($error))
            <div class="alert alert-warning text-center">
                <i class="bi bi-exclamation-circle me-2"></i>{{ $error }}
            </div>
        @elseif($movie)
            <!-- MOVIE DETAIL -->
            <div class="card detail-card mb-4">
                <div class="row g-0">
                    <!-- POSTER -->
                    <div class="col-lg-4">
                        <div class="p-4">
                            <div class="poster-section">
                                @if(isset($movie['Poster']) && $movie['Poster'] != "N/A")
                                    <img src="{{ $movie['Poster'] }}" alt="{{ $movie['Title'] }}">
                                @else
                                    <img src="https://via.placeholder.com/300x450?text={{ msg('no_poster') }}" alt="{{ msg('no_poster') }}">
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- INFO -->
                    <div class="col-lg-8">
                        <div class="info-section">
                            <h1 class="movie-title">{{ $movie['Title'] ?? '' }}</h1>

                            <!-- Genre Badges -->
                            @if(isset($movie['Genre']))
                                <div class="mb-3">
                                    @foreach(explode(', ', $movie['Genre']) as $genre)
                                        <span class="genre-badge">{{ trim($genre) }}</span>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Rating Badge -->
                            @if(isset($movie['imdbRating']))
                                <div class="mb-4">
                                    <span class="rating-badge">
                                        <i class="bi bi-star-fill me-1"></i>{{ $movie['imdbRating'] }}/10
                                    </span>
                                </div>
                            @endif

                            <!-- Movie Details -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <p class="info-label">{{ msg('year') }}</p>
                                    <p class="info-value"><i class="bi bi-calendar3 me-2"></i>{{ $movie['Year'] ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="info-label">{{ msg('rated') }}</p>
                                    <p class="info-value"><i class="bi bi-shield-check me-2"></i>{{ $movie['Rated'] ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="info-label">{{ msg('runtime') }}</p>
                                    <p class="info-value"><i class="bi bi-clock me-2"></i>{{ $movie['Runtime'] ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="info-label">{{ msg('released') }}</p>
                                    <p class="info-value"><i class="bi bi-calendar-event me-2"></i>{{ $movie['Released'] ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="info-label">{{ msg('director') }}</p>
                                    <p class="info-value"><i class="bi bi-person-video2 me-2"></i>{{ $movie['Director'] ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="info-label">{{ msg('language') }}</p>
                                    <p class="info-value"><i class="bi bi-translate me-2"></i>{{ $movie['Language'] ?? '-' }}</p>
                                </div>
                                <div class="col-12">
                                    <p class="info-label">{{ msg('actors') }}</p>
                                    <p class="info-value"><i class="bi bi-people me-2"></i>{{ $movie['Actors'] ?? '-' }}</p>
                                </div>
                                <div class="col-12">
                                    <p class="info-label">{{ msg('country') }}</p>
                                    <p class="info-value"><i class="bi bi-geo-alt me-2"></i>{{ $movie['Country'] ?? '-' }}</p>
                                </div>
                                <div class="col-12">
                                    <p class="info-label">{{ msg('awards') }}</p>
                                    <p class="info-value"><i class="bi bi-trophy me-2"></i>{{ $movie['Awards'] ?? '-' }}</p>
                                </div>
                                <div class="col-12">
                                    <p class="info-label">{{ msg('imdb_id') }}</p>
                                    <p class="info-value"><i class="bi bi-link-45deg me-2"></i>{{ $movie['imdbID'] ?? '-' }}</p>
                                </div>
                            </div>

                            <!-- Favorite Button -->
                            <div class="mb-3">
                                @if(isset($isFavorited) && $isFavorited)
                                    <form method="POST" action="/favorites/toggle">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="imdbID" value="{{ $movie['imdbID'] }}">
                                        <input type="hidden" name="title" value="{{ $movie['Title'] }}">
                                        <input type="hidden" name="year" value="{{ $movie['Year'] }}">
                                        <input type="hidden" name="poster" value="{{ $movie['Poster'] }}">
                                        <button type="submit" class="btn btn-favorited btn-custom" onclick='return confirm("{{ msg('remove_favorite_confirm') }}")'>
                                            <i class="bi bi-heart-fill me-2"></i>{{ msg('remove_from_favorites') }}
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="/favorites/toggle">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="imdbID" value="{{ $movie['imdbID'] }}">
                                        <input type="hidden" name="title" value="{{ $movie['Title'] }}">
                                        <input type="hidden" name="year" value="{{ $movie['Year'] }}">
                                        <input type="hidden" name="poster" value="{{ $movie['Poster'] }}">
                                        <button type="submit" class="btn btn-favorite btn-custom">
                                            <i class="bi bi-heart me-2"></i>{{ msg('add_to_favorites') }}
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <!-- Plot -->
                            @if(isset($movie['Plot']))
                                <div class="plot-section">
                                    <h5 class="mb-2"><i class="bi bi-card-text me-2"></i>{{ msg('plot') }}</h5>
                                    <p class="mb-0">{{ $movie['Plot'] }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back Buttons -->
            <div class="d-flex align-items-center gap-3 mt-4">
                <!-- Tombol Kembali ke Beranda (selalu muncul) -->
                <a href="/movies" class="back-link">
                    <i class="bi bi-arrow-left me-2"></i> {{ msg('back_to_home') }}
                </a>

                <!-- Tombol Kembali ke Pencarian (hanya jika ada search params) -->
                @if(isset($searchParams) && ($searchParams['search'] || $searchParams['year'] || $searchParams['type'] || $searchParams['genre']))
                    @php
                        $queryParams = [];
                        if($searchParams['search']) $queryParams[] = "search=" . urlencode($searchParams['search']);
                        if($searchParams['year']) $queryParams[] = "year=" . urlencode($searchParams['year']);
                        if($searchParams['type']) $queryParams[] = "type=" . urlencode($searchParams['type']);
                        if(isset($searchParams['page']) && $searchParams['page'] > 1) $queryParams[] = "page=" . $searchParams['page'];
                        $queryString = '?' . implode('&', $queryParams);
                    @endphp
                    <span class="mx-3">|</span>
                    <a href="/movies{{ $queryString }}" class="back-link">
                        <i class="bi bi-arrow-counterclockwise me-2"></i> {{ msg('back_to_search') }}
                    </a>
                @endif
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
