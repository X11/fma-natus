<footer class="section footer">
    <div class="container">
        <div class="columns">
            <div class="column is-2">
                <div class="menu">
                    <p class="menu-label">Pages</p>
                    <ul class="menu-list">
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li><a href="{{ url('/serie') }}">Series</a></li>
                        <li><a href="{{ url('/calender') }}">Calender</a></li>
                        @if (Auth::user())
                        <li><a href="{{ url('/watchlist') }}">Watchlist</a></li>
                        @endif
                    </ul>
                </div>
            </div>
            @if (Auth::user())
                <div class="column is-2">
                    <div class="menu">
                        <p class="menu-label">Account</p>
                        <ul class="menu-list">
                            <li><a href="{{ url('/account/setting') }}">Settings</a></li>
                            <li><a href="{{ url('/account/episodefile') }}">Files</a></li>
                            <li><a href="{{ url('/profile', [Auth::user()->id]) }}">Profile</a></li>
                        </ul>
                    </div>
                </div>
                @if (Auth::user()->isAdmin())
                    <div class="column is-2">
                        <div class="menu">
                            <p class="menu-label">Admin</p>
                            <ul class="menu-list">
                                <li><a href="{{ url('/admin/user') }}">Users</a></li>
                                <li><a href="{{ url('/admin/update') }}">Updates</a></li>
                            </ul>
                        </div>
                    </div>
                @endif
            @endif
            <div class="column">
                <div class="content has-text-right">
                    <p><strong>FMA</strong> build to feed the addiction</p>
                    <p>Content provided by <a href="https://www.thetvdb.com">The TVDB</a></p>
                </div>
            </div>
        </div>
        <div class="content has-text-right">
            <p>&copy; {{ date('Y') }} Feeding the addiction</p>
        </div>
    </div>
</footer>
