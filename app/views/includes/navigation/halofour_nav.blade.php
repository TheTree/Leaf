<li class="{{ Request::is('news') ? 'active' : '' }}"><a href="{{ URL::to('news') }}">News</a></li>
<li class="{{ Request::is('csr_leaderboards') ? 'active' : '' }}"><a href="{{ URL::to('csr_leaderboards') }}">CSR Leaderboards</a></li>
<li class="{{ Request::is('top_ten') ? 'active' : '' }}"><a href="{{ URL::to('top_ten') }}">Top Ten</a></li>
<li class="{{ Request::is('compare') ? 'active' : '' }}"><a href="{{ URL::to('compare') }}">Compare</a></li>
<li class="{{ Request::is('about') ? 'active' : '' }}"><a href="{{ URL::to('about') }}">About</a></li>