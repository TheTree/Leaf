@if (isset($latest) && is_array($latest) && count($latest) > 0)
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">New Accounts</h3>
		</div>
		<div class="pane-body">
			<table class="table table-responsive table-hover">
				<thead>
					<tr>
						<th>Gamertag</th>
						<th>Rank</th>
						<th><abbr="KD">Kill / Death Ratio</abbr></th>
					</tr>
				</thead>
				<tbody>
					@foreach($latest as $player)
						<tr>
							<td><a href="{{ URL::to('h4/record/' . $player['SeoGamertag']) }}">{{ $player['Gamertag'] }}</a></td>
							<td>SR-{{ $player['Rank'] }}</td>
							<td>{{ $player['KDRatio'] }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
@else
	<div class="alert alert-info">
		<strong>uh oh</strong>
		<p>We don't have any accounts.</p>
	</div>
@endif