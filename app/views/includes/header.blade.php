<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-leaf">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="{{ URL::to('') }}">Leaf</a>
		</div>
		<div class="collapse navbar-collapse navbar-leaf">
			<nav>
				<ul class="nav navbar-nav">
					<li class="{{ Request::is('news') ? 'active' : '' }}"><a href="{{ URL::to('news') }}">News</a></li>
					<li class="{{ Request::is('csr_leaderboards') ? 'active' : '' }}"><a href="{{ URL::to('csr_leaderboards') }}">CSR Leaderboards</a></li>
					<li class="{{ Request::is('top_ten') ? 'active' : '' }}"><a href="{{ URL::to('top_ten') }}">Top Ten</a></li>
					<li class="{{ Request::is('compare') ? 'active' : '' }}"><a href="{{ URL::to('compare') }}">Compare</a></li>
					<li class="{{ Request::is('about') ? 'active' : '' }}"><a href="{{ URL::to('about') }}">About</a></li>
				</ul>
			</nav>
			<ul class="nav navbar-nav navbar-right">
				<li><a href="#">test</a></li>
			</ul>
		</div>
	</div>
</nav>