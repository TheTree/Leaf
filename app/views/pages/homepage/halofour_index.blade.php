@extends('layouts.index')

@section('main')
	<p>This is my body content.</p>
@stop

@section('sidebar')
	@include('includes.blocks.homepage.challenges')
@stop