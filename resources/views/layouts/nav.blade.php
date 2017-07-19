<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
            
        <div class="navbar-header">
                
            <!-- Hamburger für Mobiles -->
            <button class="nav navbar-toggle" data-toggle="collapse" data-target="#mwd-right-menu">
                    <span class="sr-only">Menü aufklappen</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
            </button>
            
            <!-- Logo -->
            <a class="navbar-brand" href="{{ route('/') }}">
                <img class="light" src="{{ asset('storage/img/kundenportal/Nemetz-Logo-final-homepage.png') }}">
            </a>
            
            <!-- Breadcrumb für Desktop -->
            <h3 class="navbar-text hidden-xs hidden-sm">@isset($pageTitle){{ $pageTitle }}@endisset</h3>
            
        </div>
        
        <!-- Rechtes Menü -->
        <div id="mwd-right-menu" class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                @if (Auth::check())
                    <li>
                        <a href="{{ route('/') }}">Meine Einsatzstellen</a>
                    </li>
                    @if (Auth::user()->isAdmin())
                        <li>
                            <a href="{{ URL::to('/') }}/import">Import</a>
                        </li>
                        <li>
                            <a href="{{ URL::to('/') }}/benutzer">Benutzerverwaltung</a>
                        </li>
                    @endif
                    <li>
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                        Logout</a>
                    </li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                @else
                    <li><a href="{{ url('/login') }}">Login</a></li>
                    <li><a href="{{ url('/register') }}">Register</a></li>
                @endif
            </ul>
        </div>
        
        
    </div>
</nav>
                @if (Route::has('login'))
                    <ul class="nav navbar-nav">
                    </ul>
                @endif