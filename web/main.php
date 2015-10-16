<!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <title>My Reddit Favorites</title>
        <link rel="stylesheet" href="css/normalize.css">
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<link rel="stylesheet" href="css/style.css">
    </head>
    <body>
		<div>
			<h1 class="center">My Reddit Favorites</h1>
			<a href="index.php"><button>Logout</button></a>
			<div id="tabs">
				<ul>
				  <li><a href="#hot-list">Hot</a></li>
				  <li><a href="#top-list">Top</a></li>
				  <li><a href="#favorite-list">Favorites</a></li>
				</ul>
				<div id="hot-list">
					<ul></ul>
				</div>
				<div id="top-list">
					<ul></ul>
				</div>
				<div id="favorite-list">
					<ul></ul>
				</div>
			</div>
		</div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<script>
		  $(function() {
			$( "#tabs" ).tabs();
		  });
        </script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.2.min.js"><\/script>')</script>
		<script src="js/main.js"></script>
    </body>
</html>
