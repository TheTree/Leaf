@extends('layouts.halofour.csr')

@section('sidebar')
<br />
 	<ul class="nav nav-pills nav-stacked nav-sidebar">
	    @foreach($playlists as $playlist)
	        <li class=""><a href="{{ URL::to('csr_leaderboards/' . $playlist['SeoName']) }}">{{ $playlist['Name'] }}</a></li>
	    @endforeach
 	</ul>
@stop

@section('main')
	<legend>{{ $active['Name'] }}</legend>
@stop

