<!DOCTYPE html>
<html>
<head>
	<title>Explorer</title>
	<link rel="stylesheet" type="text/css" href="css/prism.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span12">
				<h1>Web File Explorer</h1>
			</div>
		</div>
		<div class="row-fluid" id="error">
			<div class="span12">
				<div class="alert alert-error">
					<strong>Errors : </strong><span></span>
				</div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12">
				<ul class="breadcrumb"></ul>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12">
				<!-- file list -->
				<p>File List :</p>
				<ul class="nav nav-list file-list"></ul>
				<!-- code -->
				<pre class="content-file"></pre>
			</div>
		</div>
	</div>
</body>
	<script type="text/javascript" src="js/prism.js"></script>
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/main.js"></script>
</html>