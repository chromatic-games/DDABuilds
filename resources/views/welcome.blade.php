<!DOCTYPE HTML>
<html lang="{{str_replace('_', '-', app()->getLocale())}}">
<head>
	<title>{{env('APP_NAME')}}</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="shortcut icon" href=data:image/x-icon; type=image/x-icon>
	<link href="{{url('/') . mix('assets/css/style.css')}}" rel="stylesheet">
</head>
<body>
	<div id="app"></div>
	<script src="{{url('/') . mix('assets/js/main.js')}}"></script>
</body>
</html>