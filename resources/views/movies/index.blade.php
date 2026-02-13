<!DOCTYPE html>
<html lang="{{ session('locale', 'id') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie List - Movie App</title>
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
        .search-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            background: white;
        }
        .movie-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            background: white;
            height: 100%;
        }
        .movie-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        .movie-poster {
            position: relative;
            overflow: hidden;
            height: 380px;
            background: #f0f0f0;
        }
        .movie-poster img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        .movie-card:hover .movie-poster img {
            transform: scale(1.05);
        }
        .movie-body {
            padding: 15px;
        }
        .movie-title {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 10px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .movie-year {
            color: #6c757d;
            font-size: 0.9rem;
        }
        .btn-custom {
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-detail {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
        }
        .btn-detail:hover {
            background: linear-gradient(135deg, #5a6fd6 0%, #6a4190 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }
        .btn-favorite {
            background-color: #28a745;
            border: none;
            color: white;
        }
        .btn-favorite:hover {
            background-color: #218838;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }
        .btn-favorited {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            border: none;
            color: white;
        }
        .btn-favorited:hover {
            background: linear-gradient(135deg, #c82333 0%, #bd1d2b 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
        }
        .pagination-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            background: white;
        }
        .page-link {
            color: #667eea;
            border: none;
            margin: 0 3px;
            border-radius: 8px;
        }
        .page-link:hover {
            background-color: #667eea;
            color: white;
        }
        .page-item.active .page-link {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .form-control, .form-select {
            border-radius: 8px;
            padding: 10px 15px;
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

        /* Loading Indicator */
        .loading-overlay {
            display: none;
            justify-content: center;
            align-items: center;
            padding: 30px 20px;
            width: 100%;
            clear: both;
        }

        .loading-overlay.active {
            display: flex;
        }

        .loading-spinner {
            border: 4px solid rgba(102, 126, 234, 0.2);
            border-top: 4px solid #667eea;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            animation: spin 1s linear infinite;
        }

        .loading-text {
            margin-left: 15px;
            color: #667eea;
            font-weight: 500;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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

        <!-- SEARCH FORM -->
        <div class="card search-card mb-4">
            <div class="card-body">
                <h4 class="card-title mb-3"><i class="bi bi-search me-2"></i>{{ msg('search_title') }}</h4>
                <form method="GET" action="/movies">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label fw-bold">{{ msg('movie_title_label') }}</label>
                            <input type="text" class="form-control" name="search" placeholder="{{ msg('movie_title_placeholder') }}" value="{{ $search }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold">{{ msg('year_label') }}</label>
                            <input type="text" class="form-control" name="year" placeholder="{{ msg('year_placeholder') }}" value="{{ request('year') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">{{ msg('type_label') }}</label>
                            <select class="form-select" name="type">
                                <option value="">{{ msg('all_types') }}</option>
                                <option value="movie" {{ request('type') == 'movie' ? 'selected' : '' }}>Movie</option>
                                <option value="series" {{ request('type') == 'series' ? 'selected' : '' }}>Series</option>
                                <option value="episode" {{ request('type') == 'episode' ? 'selected' : '' }}>Episode</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary btn-custom w-100">
                                <i class="bi bi-search me-1"></i>{{ msg('search_btn') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- INFO MESSAGE -->
        @if(!request('search'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="bi bi-info-circle me-2"></i>
                {{ msg('showing_latest_movies', ['year' => request('year', date('Y'))]) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- MOVIE RESULTS -->
        @if(isset($movies['Response']) && $movies['Response'] == "False")
            <div class="alert alert-warning text-center">
                <i class="bi bi-exclamation-circle me-2"></i>{{ msg('movie_not_found') }}
            </div>
        @endif

        @if(isset($movies['Search']))
            @php
                $displayMovies = array_slice($movies['Search'], 0, 10);
            @endphp
            <div class="row g-4">
                @foreach($displayMovies as $movie)
                    @php
                        $queryParams = [];
                        if($search) $queryParams[] = "search=" . urlencode($search);
                        if($year) $queryParams[] = "year=" . urlencode($year);
                        if($type) $queryParams[] = "type=" . urlencode($type);
                        if($genre) $queryParams[] = "genre=" . urlencode($genre);
                        if(isset($page) && $page > 1) $queryParams[] = "page=" . $page;
                        $queryString = !empty($queryParams) ? '?' . implode('&', $queryParams) : '';

                        $isFav = $favoritedMovies[$movie['imdbID']] ?? false;
                    @endphp
                    <div class="col-md-4 col-lg-3 col-xl-2">
                        <div class="card movie-card">
                            <div class="movie-poster">
                                @if($movie['Poster'] != "N/A")
                                    <img src="{{ $movie['Poster'] }}" alt="{{ $movie['Title'] }}">
                                @else
                                    <div class="d-flex align-items-center justify-content-center h-100">
                                        <span class="text-muted"><i class="bi bi-image" style="font-size: 3rem;"></i></span>
                                    </div>
                                @endif
                            </div>
                            <div class="movie-body">
                                <h5 class="movie-title">{{ $movie['Title'] }}</h5>
                                <p class="movie-year mb-3"><i class="bi bi-calendar3 me-1"></i>{{ $movie['Year'] }}</p>
                                <div class="d-flex gap-2">
                                    <a href="/movies/{{ $movie['imdbID'] }}{{ $queryString }}" class="btn btn-detail btn-custom flex-fill">
                                        <i class="bi bi-eye me-1"></i>{{ msg('detail_btn') }}
                                    </a>
                                    @if($isFav)
                                        <form method="POST" action="/favorites/toggle" class="flex-fill">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="imdbID" value="{{ $movie['imdbID'] }}">
                                            <input type="hidden" name="title" value="{{ $movie['Title'] }}">
                                            <input type="hidden" name="year" value="{{ $movie['Year'] }}">
                                            <input type="hidden" name="poster" value="{{ $movie['Poster'] }}">
                                            <button type="submit" class="btn btn-favorited btn-custom w-100" onclick='return confirm("{{ msg('remove_favorite_confirm') }}")'>
                                                <i class="bi bi-heart-fill me-1"></i>{{ msg('favorited_btn') }}
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="/favorites/toggle" class="flex-fill">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="imdbID" value="{{ $movie['imdbID'] }}">
                                            <input type="hidden" name="title" value="{{ $movie['Title'] }}">
                                            <input type="hidden" name="year" value="{{ $movie['Year'] }}">
                                            <input type="hidden" name="poster" value="{{ $movie['Poster'] }}">
                                            <button type="submit" class="btn btn-favorite btn-custom w-100">
                                                <i class="bi bi-heart me-1"></i>{{ msg('favorite_btn') }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- LOADING INDICATOR -->
            <div id="loadingOverlay" class="loading-overlay">
                <div class="loading-spinner"></div>
                <span class="loading-text">{{ msg('loading_movies') }}</span>
            </div>

            <!-- END MESSAGE -->
            <div id="endMessage" class="text-center py-4" style="display: none;">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>{{ msg('movie_not_found') }}
                </div>
            </div>
        @endif
    </div>

    <script>
        // Infinite Scroll Implementation
        let currentPage = {{ $page }};
        let isLoading = false;
        let hasMore = true;
        let currentSearchParams = {
            search: "{{ $search }}",
            year: "{{ request('year') }}",
            type: "{{ request('type') }}",
            genre: "{{ request('genre') }}"
        };
        // CSRF token for AJAX requests
        window.csrfToken = "{{ csrf_token() }}";

        // Translations for JavaScript
        window.messages = {
            remove_favorite_confirm: @json(msg('remove_favorite_confirm')),
            detail_btn: @json(msg('detail_btn')),
            favorite_btn: @json(msg('favorite_btn')),
            favorited_btn: @json(msg('favorited_btn'))
        };

        // Show loading indicator
        function showLoading() {
            const overlay = document.getElementById('loadingOverlay');
            if (overlay) {
                overlay.style.display = 'flex';
            }
        }

        // Hide loading indicator
        function hideLoading() {
            const overlay = document.getElementById('loadingOverlay');
            if (overlay) {
                overlay.style.display = 'none';
            }
        }

        // Check scroll position and load more
        window.addEventListener('scroll', function() {
            if (isLoading || !hasMore) {
                console.log('Skip scroll: isLoading=' + isLoading + ', hasMore=' + hasMore);
                return;
            }

            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            const windowHeight = window.innerHeight;
            const documentHeight = document.documentElement.scrollHeight;

            // Load more when user scrolls to 300px from bottom
            if (scrollTop + windowHeight >= documentHeight - 300) {
                console.log('Scroll detected, loading more movies...');
                loadMoreMovies();
            }
        });

        function loadMoreMovies() {
            if (isLoading || !hasMore) {
                console.log('Already loading or no more movies');
                return;
            }

            isLoading = true;
            showLoading();

            currentPage++;

            // Build query parameters
            let url = '/movies/load-more?page=' + currentPage;
            if (currentSearchParams.search) url += '&search=' + encodeURIComponent(currentSearchParams.search);
            if (currentSearchParams.year) url += '&year=' + encodeURIComponent(currentSearchParams.year);
            if (currentSearchParams.type) url += '&type=' + encodeURIComponent(currentSearchParams.type);
            if (currentSearchParams.genre) url += '&genre=' + encodeURIComponent(currentSearchParams.genre);

            console.log('Loading page:', currentPage, 'URL:', url);

            fetch(url)
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        throw new Error('HTTP error! status: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Data loaded:', data);

                    // data.movies sudah berupa array dari controller
                    if (!data.movies || data.movies.length === 0) {
                        console.log('No movies data received');
                        hasMore = false;
                        hideLoading();
                        isLoading = false;
                        showEndMessage();
                        return;
                    }

                    const moviesGrid = document.querySelector('div.row.g-4');

                    if (!moviesGrid) {
                        console.error('Movies grid not found!');
                        hideLoading();
                        isLoading = false;
                        return;
                    }

                    // data.movies adalah array langsung, bukan movies.Search
                    data.movies.forEach(movie => {
                        const movieId = movie.imdbID;
                        const exists = document.querySelector('a[href="/movies/' + movieId + '"]');

                        if (!exists) {
                            console.log('Adding movie:', movie.Title);
                            try {
                                const movieCard = createMovieCard(movie, data.favoritedMovies[movieId] || false);
                                if (movieCard) {
                                    moviesGrid.appendChild(movieCard);
                                }
                            } catch (e) {
                                console.error('Error creating movie card:', e);
                            }
                        } else {
                            console.log('Movie already exists:', movie.Title);
                        }
                    });

                    // Jika kurang dari 10, berarti sudah tidak ada lagi
                    if (data.movies.length < 10 || !data.hasMore) {
                        hasMore = false;
                        showEndMessage();
                    }

                    hideLoading();
                    isLoading = false;
                })
                .catch(error => {
                    console.error('Error loading movies:', error);
                    hideLoading();
                    isLoading = false;
                });
        }

        function showEndMessage() {
            const endMsg = document.getElementById('endMessage');
            if (endMsg) {
                endMsg.style.display = 'block';
            }
        }

        function createMovieCard(movie, isFavorited) {
            const div = document.createElement('div');
            div.className = 'col-md-4 col-lg-3 col-xl-2';

            const queryParams = new URLSearchParams();
            Object.keys(currentSearchParams).forEach(key => {
                if (currentSearchParams[key]) {
                    queryParams.set(key, currentSearchParams[key]);
                }
            });
            queryParams.set('page', currentPage);
            const queryString = '?' + queryParams.toString();

            const favoriteBtn = isFavorited ?
                `<form method="POST" action="/favorites/toggle" class="flex-fill">
                    <input type="hidden" name="_token" value="${window.csrfToken}">
                    <input type="hidden" name="imdbID" value="${movie.imdbID}">
                    <input type="hidden" name="title" value="${movie.Title}">
                    <input type="hidden" name="year" value="${movie.Year}">
                    <input type="hidden" name="poster" value="${movie.Poster}">
                    <button type="submit" class="btn btn-favorited btn-custom w-100" onclick='return confirm("${window.messages.remove_favorite_confirm}")'>
                        <i class="bi bi-heart-fill me-1"></i>${window.messages.favorited_btn}
                    </button>
                </form>` :
                `<form method="POST" action="/favorites/toggle" class="flex-fill">
                    <input type="hidden" name="_token" value="${window.csrfToken}">
                    <input type="hidden" name="imdbID" value="${movie.imdbID}">
                    <input type="hidden" name="title" value="${movie.Title}">
                    <input type="hidden" name="year" value="${movie.Year}">
                    <input type="hidden" name="poster" value="${movie.Poster}">
                    <button type="submit" class="btn btn-favorite btn-custom w-100">
                        <i class="bi bi-heart me-1"></i>${window.messages.favorite_btn}
                    </button>
                </form>`;

            const posterHtml = movie.Poster && movie.Poster !== "N/A" ?
                `<img src="${movie.Poster}" alt="${movie.Title}">` :
                `<div class="d-flex align-items-center justify-content-center h-100">
                    <span class="text-muted"><i class="bi bi-image" style="font-size: 3rem;"></i></span>
                </div>`;

            div.innerHTML = `
                <div class="card movie-card">
                    <div class="movie-poster">
                        ${posterHtml}
                    </div>
                    <div class="movie-body">
                        <h5 class="movie-title">${movie.Title}</h5>
                        <p class="movie-year mb-3"><i class="bi bi-calendar3 me-1"></i>${movie.Year}</p>
                        <div class="d-flex gap-2">
                            <a href="/movies/${movie.imdbID}${queryString}" class="btn btn-detail btn-custom flex-fill">
                                <i class="bi bi-eye me-1"></i>${window.messages.detail_btn}
                            </a>
                            ${favoriteBtn}
                        </div>
                    </div>
                </div>
            `;

            return div;
        }

        // Initialize favorited movies from server
        window.favoritedMovies = {
            @foreach($favoritedMovies as $imdbID => $isFav)
                '{{ $imdbID }}': {{ $isFav ? 'true' : 'false' }},
            @endforeach
        };
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
