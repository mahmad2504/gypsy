<!DOCTYPE html>
<html>
<head>
    <title>Shipment Graphs</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge;chrome=1" >
	
	<?php include 'modules/assets/stylesheets.php';
	ModuleJsCode();
	?>
</head>
<body>
	<?php include 'modules/assets/header.php'; ?>
	<p></p>
	<div class="container-fluid">
		<div  class="row">
			<div class="col-1">
			</div>
			<div class="col-5">
				<iframe scrolling="no" style="border: outset; background-color:#ffffff" overflow:hidden;" src="graph/shipment/count" height="530" width="100%"></iframe>
			</div>
			<div class="col-5">			
				<iframe scrolling="no" style="border: outset; background-color:#ffffff" overflow:hidden;" src="graph/shipment/category" height="530" width="100%"></iframe>
			</div>
			<div class="col-1">
			</div>
		</div>
	</div>
	<?php include 'modules/assets/footer.php'; ?>
	<?php include 'modules/assets/jscripts.php';?>
	<script type="text/javascript" src="<?php echo $modulepath.'/assets/js/app.js';?>"></script>
</body>