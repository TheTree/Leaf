@extends('layouts.index')

@section('main')
	<br />
	@include('includes.blocks.homepage.topblock')
	@include('includes.blocks.homepage.news_blip')
	@include('includes.blocks.homepage.add_gamertag')
	@include('includes.blocks.homepage.last_comparison')
	@include('includes.blocks.homepage.new_accounts')
@stop

@section('sidebar')
	@include('includes.blocks.homepage.challenges')
@stop