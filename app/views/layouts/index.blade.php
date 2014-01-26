@extends('layouts.master')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-7">
				@yield('main')
			</div>
			<div class="col-md-5">
				@yield('sidebar')
			</div>
		</div>
	</div>
@stop
