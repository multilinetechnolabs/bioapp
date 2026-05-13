<div class="row row-1">
    <ul class="navbar-nav ml-auto" style="padding-right: 10px;">
        <!-- Authentication Links -->
        @guest
            <li><a class="nav-link" href="{{ route('login') }}" >{{ __('Login') }}</a></li>
        @else
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    @if(Auth::user()->isAdmin())
                        {{ __('Welcome Administrator, ') }} 
                    @else
                        {{ __('Welcome, ') }}
                    @endif
                    {{ Auth::user()->name }} 
                    <span class="caret"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="/home">
                        {{ __('Home') }}
                    </a>
                    <a class="dropdown-item" href="/dashboard">
                        {{ __('Dashboard') }}
                    </a>
                    <a class="dropdown-item" href="/bioconnect/profile">
                        {{ __('Profile Account') }}
                    </a>
                    <a class="dropdown-item" href="/media">
                        {{ __('Media Files') }}
                    </a>
                    @if(Auth::user()->isAdmin() || Auth::user()->isPractitioner())
                        <a class="dropdown-item" href="/playlist">
                            {{ __('Playlists') }}
                        </a>
                    @endif
                    <a class="dropdown-item" href="{{ route('app.pricing') }}">
                        {{ __('Plans and Pricing') }}
                    </a>
                    <a class="dropdown-item" href="{{ route('app.user.orders') }}">
                        {{ __('My Orders') }}
                    </a>
                    <a class="dropdown-item" href="{{ route('app.user.payments') }}">
                        {{ __('My Payments') }}
                    </a>
                    <a class="dropdown-item" href="{{ route('app.user.subscriptions') }}">
                        {{ __('My Subscriptions') }}
                    </a>
                    @if(Auth::user()->isBlogger())
                    <a class="dropdown-item" href="{{ route('app.posts.index') }}">
                        {{ __('Manage blog posts') }}
                    </a>
                    @endif
                    @if(Auth::user()->isAdmin())
                        <a class="dropdown-item" href="/admin" target="_blank">
                            {{ __('Go to Admin Page') }}
                        </a>
                    @endif
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault();document.getElementById('logout-form').submit();">
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