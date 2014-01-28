<div class="well well-lg">
	{{ Form::open(['action' => 'HomeController@addGamertag', 'role' => 'form', 'class' => 'form-inline']) }}
	<div class="input-group {{{ $errors->has('gamertag') ? 'has-error' : ''}}}">
		{{ Form::text('gamertag', '', ['placeholder' => 'Enter your gamertag', 'class' => 'form-control']) }}
		<span class="input-group-btn btn-group">
			{{ Form::submit('Load Stats', ['class' => 'btn btn-primary']) }}
		</span>
	</div>
	@if ($errors->has('gamertag'))
	    <span class="help-block">{{{ $errors->first('gamertag') }}}</span>
	@endif
	{{ Form::close() }}
</div>