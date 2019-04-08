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
			
			<div class="col-4">
				<iframe scrolling="no" style="border: outset; background-color:#ffffff" overflow:hidden;" src="graph/invoices/countries" height="530" width="100%"></iframe>
			</div>
			<div class="col-4">			
				<iframe scrolling="no" style="border: outset; background-color:#ffffff" overflow:hidden;" src="graph/invoices/owners" height="530" width="100%"></iframe>
			</div>
			<div class="col-4">			
				<iframe scrolling="no" style="border: outset; background-color:#ffffff" overflow:hidden;" src="graph/invoices/teams" height="530" width="100%"></iframe>
			</div>
			
		</div>
	</div>
	<?php include 'modules/assets/footer.php'; ?>
	<?php include 'modules/assets/jscripts.php';?>
	<script type="text/javascript" src="<?php echo $modulepath.'/assets/js/app.js';?>"></script>
</body>