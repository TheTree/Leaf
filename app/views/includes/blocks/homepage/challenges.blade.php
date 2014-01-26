<h3>Challenges</h3>
<div class="well-sm well">
	@foreach ($challenges as $challenge)
		<strong>{{ $challenge->Name }}</strong>
		<span class="label label-{{ $challenge->Badge }}">{{ $challenge->CategoryName }}</span>
		<span class="label label-success">XP: {{ $challenge->XpReward }}</span><br />
		<i>{{ $challenge->Description }}</i><br />
		<small>Time left: {{ $challenge->EndDate }} </small>
		<br /><br />
	@endforeach
</div>