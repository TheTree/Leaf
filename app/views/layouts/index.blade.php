@extends('layouts.master')

@section('content')
	<div class="container">
		<div class="row">
			<br /><br />
			<div class="col-md-{{{ $main_size or '7' }}}">
				@yield('main')
			</div>
			<div class="col-md-{{{ $sidebar_size or '5' }}}">
				@yield('sidebar')
			</div>
		</div>
	</div>
@stop
