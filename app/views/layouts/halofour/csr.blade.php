@extends('layouts.master')

@section('content')
<div class="container">
	<div class="row">
		<br /><br /><br />
		<div class="col-md-3">
			@yield('sidebar')
		</div>
		<div class="col-md-9">
			@yield('main')
		</div>
	</div>
</div>
@stop
