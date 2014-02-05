@extends('layouts.halofour.csr')

@section('extra_css')
	{{ HTML::style('css/csr-flairs.css') }}
@stop

@section('sidebar')
<br />
 	<ul class="nav nav-pills nav-stacked nav-sidebar">
	    @foreach($playlists as $playlist)
	        <li class="{{ Request::is('csr_leaderboards/' . $playlist['SeoName']) ? 'active' : '' }}"><a href="{{ URL::to('csr_leaderboards/' . $playlist['SeoName']) }}">{{ $playlist['Name'] }}</a></li>
	    @endforeach
 	</ul>
@stop

@section('main')
	<legend><span class="label {{ $active['TypeStyle'] }} label-static-size">{{ $active['Type'] }}</span>{{ $active['Name'] }}</legend>
	@if (is_array($results) && count($results) > 0)
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>Place</th>
					<th>Gamertag</th>
					<th><abbr title="Competitive Skill Rank">CSR</abbr></th>
					<th><abbr title="Kill / Death">KD</abbr> Ratio</th>
				</tr>
			</thead>
			<tbody>
				@foreach($results as $user)
					<tr>
						<td>{{ $user['Place'] }}</td>
						<td><a href="{{ URL::to('h4/record/' . $user['SeoGamertag']) }}">{{ $user['Gamertag'] }}</a></td>
						<td>{{ $user['CsrFlair'] }}</td>
						<td>{{ $user['KdRatio'] }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	@else
		<div class="alert alert-info">
			<strong>Uh oh</strong>
			<p>We don't have any records for this playlist. It must of just launched. Wait a few minutes :)</p>
		</div>
	@endif
@stop

