<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/dist/jquery.js') }}"></script>
    <script src="{{ asset('js/external.js') }}"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-blue shadow-sm">
            <div class="container block">
                <div class="block_div-title">
                    <span>
                        <a class="navbar-brand" href="{{ url('/') }}">
                            {{ config('app.name', 'Laravel') }}
                        </a>
                    </span>
                    <span class="author navbar-list">By {{ config('app.author', '') }}</span>
                </div>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link navbar-list" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link navbar-list" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                           <li class="nav-item">
                                <a class="nav-link navbar-list" href="{{ route('home') }}">{{ __('Dashboard') }}</a>
                            </li>
                            @if(auth()->user()->can('view_category') || auth()->user()->can('create_category'))
                                <li class="nav-item">
                                    <a class="nav-link navbar-list" href="{{ route('categories') }}">{{ __('Categories') }}</a>
                                </li>
                            @endif
                            @if(auth()->user()->can('view_expenses') || auth()->user()->can('create_expenses'))
                            <li class="nav-item">
                                <a class="nav-link navbar-list" href="{{ route('expenses') }}">{{ __('Expenses') }}</a>
                            </li>
                            @endif
                            @if(auth()->user()->can('view_users') || auth()->user()->can('edit_password'))
                            <li class="nav-item">
                                <a class="nav-link navbar-list" href="{{ route('users') }}">{{ __('Users') }}</a>
                            </li>
                            @endif
                            @if(auth()->user()->can('view_roles') || auth()->user()->can('create_roles'))
                            <li class="nav-item">
                                <a class="nav-link navbar-list" href="{{ route('roles') }}">{{ __('Roles') }}</a>
                            </li>
                            @endif
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle navbar-list" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ ucwords(Auth::user()->name) }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="#" id="logout-button">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <script>
        @auth
            window.AuthId = {!! json_encode(Auth::user()->id) !!};
            window.Auth = {!! json_encode(Auth::user()->roles->pluck('name')) !!};
            window.Permissions = {!! json_encode(Auth::user()->allPermissions, true) !!};
        @else
            window.Permissions = [];
        @endauth
    </script>
    <script src="{{ asset('js/dist/bootstrap.js') }}"></script>
    <script src="{{ asset('js/dist/select2.min.js') }}"></script>
</body>
</html>
