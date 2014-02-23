@extends('layouts.backstage.index')

@section('main')
<legend>Sekrit Area</legend>
<div class="well well-lg">
	{{ Form::open(['action' => 'BackstageController@postIndex', 'role' => 'form', 'class' => 'form-horizontal']) }}
	<div class="form-group {{{ $errors->has('email') ? 'has-error' : ''}}}">
		<label for="email" class="col-sm-2 control-label">Email</label>
		<div class="col-sm-10">
			{{ Form::text('email', '', ['placeholder' => 'Email', 'class' => 'form-control', 'type' => 'email']) }}
		</div>
	</div>
	<div class="form-group {{{ $errors->has('password') ? 'has-error' : ''}}}">
		<label for="email" class="col-sm-2 control-label">Password</label>
		<div class="col-sm-10">
			{{ Form::password('password', ['class' => 'form-control', 'type' => 'password']) }}
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			{{ Form::submit('Log In', ['class' => 'btn btn-default']) }}
		</div>
	</div>
	{{ Form::close() }}
</div>
@stop