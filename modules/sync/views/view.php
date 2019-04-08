<!DOCTYPE html>
<html>
<head>
    <title>Product Page</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge;chrome=1">
	<?php include 'modules/sync/views/stylesheets.php';
	ModuleJsCode();
	?>
</head>
<body>
	<p>
		<!-- <button id="clear">Clear log</button>
		<button id="close">Disconnect</button> -->
		<button id='sync'>Sync</button>
		<span id="connection"><div></div></span>
	</p>
	<div class="border" id="log"></div>
	<?php include 'modules/sync/views/scripts.php';?>
</body>
</html>