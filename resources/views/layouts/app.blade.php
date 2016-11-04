<!-- Stored in resources/views/layouts/app.blade.php --><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>FMF Company</title>
	<meta name="description" content="FMF Company">

	<!-- style -->
	<link rel="stylesheet" href="//cdn.jsdelivr.net/fontawesome/4.3.0/css/font-awesome.css">
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

	<link rel="stylesheet" href="../css/jumbotron-narrow.css">

</head>
<body>

<div class="container">
	<div class="header clearfix">
		<h3 class="text-muted">ООО "ОК "ФМФ"</h3>
	</div>

	<div class="jumbotron">
		<h2>@yield('client')</h2>
	</div>

	<div class="row marketing">
		<div class="col-lg-12">
			@yield('documents')
		</div>
	</div>

	<footer class="footer">
		<p>&copy; 2016 FMF Company</p>
	</footer>

</div>

<!-- scripts -->
<script src="//cdn.jsdelivr.net/jquery/2.1.3/jquery.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
</body>
</html>