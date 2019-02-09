

<!doctype html>
<html lang="en-au">
    <head>
        <title>Shipment Details</title> 
		<meta name="viewport" content="width=device-width, initial-scale=1"> 
		<link rel="icon" href="<?php echo $modulepath;?>/assets/css/images/icon.png">
		<link rel="stylesheet" href="<?php echo $modulepath;?>/assets/css/app.css" />
		
    </head>
    <body>
		<img id="wait" src="<?php echo $modulepath;?>/assets/images/please_wait.gif" style="display:none;margin-left:auto;margin-right:auto;"></img>
		<div id="chart_div"></div>
	<script>
		var params={
		<?php
			PopulateParams($params);
		?>
		};
	</script>
	<script src="<?php echo $jquery;?>"></script>
	<script src="<?php echo $googlechart;?>"></script>
	<script src="<?php echo $gscript;?>"></script>
	<script src="<?php echo $modulepath;?>/assets/js/app.js"></script>
    </body>
</html>