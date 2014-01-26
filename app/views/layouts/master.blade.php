<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title></title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	{{ HTML::style('css/bootstrap.min.css') }}
</head>
<body>
	<!--[if lt IE 8]>
	<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
	<![endif]-->

	<div class="container">
		@yield('content')
	</div>

	{{ HTML::script('js/jquery-1.11.0.min.js') }}
	{{ HTML::script('js/bootstrap.min.js') }}
</body>
</html>