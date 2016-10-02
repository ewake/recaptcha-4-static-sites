<!doctype html>
<html class="no-js" lang="{{lang}}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>{{meta_title}}</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="apple-touch-icon" href="apple-touch-icon.png">
        <!-- Place favicon.ico in the root directory -->

        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="//cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
    </head>
    <body>
		<div class="container">
			<!--[if lte IE 9]>
				<div class="alert alert-warning" role="alert">
					<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
				</div>
			<![endif]-->
			
			<nav class="navbar navbar-default">
				<div class="container-fluid">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a class="navbar-brand" href="index.html">Brand</a>
					</div>

					<div class="collapse navbar-collapse" id="navbar-collapse">
						<ul class="nav navbar-nav">
							<li><a href="index.html">Home</a></li>
							<li class="active"><a href="contact.html">Contact</a></li>
						</ul>
					</div>
				</div>
			</nav>

			<h3>{{title}} <small>{{sub_title}}</small></h3>

			<?php if(sizeof($errors) > 0) { ?>
				<div class="alert alert-danger alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<ul class="list-unstyled">
					<?php
						foreach($errors as $error) {
							echo '<li>'.$error.'</li>'.PHP_EOL;
						}
					?>
					</ul>
				</div>
			<?php } ?>
			
			{{content}}
			
			<br>
			
			<a class="btn btn-default btn-xs" href="contact.html">&laquo; back</a>
							
			<hr>
			
			<footer>
				<p>&copy; 2016 <a href="#">Brand</a></p>
			</footer>
        </div>

        <script src="//code.jquery.com/jquery-3.1.1.slim.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </body>
</html>
