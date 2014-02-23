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
					@if (isset($navigation))
						@include('includes.navigation.' . $navigation)
					@else
						@include('includes.navigation.halofour_nav')
					@endif
				</ul>
			</nav>
			<ul class="nav navbar-nav navbar-right">
				<li><a href="#">test</a></li>
			</ul>
		</div>
	</div>
</nav>