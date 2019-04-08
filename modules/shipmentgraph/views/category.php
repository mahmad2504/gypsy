<!DOCTYPE html>
<html>
<head>
    <title>Shipment Graphs</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge;chrome=1" >
	<link rel="stylesheet" type="text/css" href="<?php echo $modulebase.'/assets/css/bootstrap.min.css';?>" rel="stylesheet" />
	<link rel="stylesheet" type="text/css" href="<?php echo $modulebase.'/assets/css/font.awesome.min.css';?>" rel="stylesheet" />
	<style>
		.center {
		  margin: auto;
		  width: 90%;
		  border: 3px solid green;
		  padding: 10px;
		}
	</style>
	<?php
	ModuleJsCode();
	?>
</head>
<body>
<br>
<div class="center">
	<!--<select id="chart" name='select1' onchange="change()">
		<option selected value="Monthly">Monthly</option> 
		<option selected value="Quarterly">Quarterly</option> 
		<option selected value="c152">C152</option>
		<option value="c172">C172</option>
	</select>-->
	<div id="shipment_chart_div"></div>
	<div id="radios" style="text-align: center;display:none;font-size:8x;">
		<div>
		<!-- Material inline 1 -->
			<div class="form-check form-check-inline">
			<input checked="checked" type="radio" class="form-check-input" id="cbproperty" name="inlineMaterialRadiosExample1">
			<label class="form-check-label" for="cbproperty">Property</label>
			</div>

		<!-- Material inline 2 -->
			<div class="form-check form-check-inline">
			<input type="radio" class="form-check-input" id="cbcountry" name="inlineMaterialRadiosExample1">
			<label class="form-check-label" for="cbcountry">Country</label>
			</div>

		<!-- Material inline 3 -->
			<div class="form-check form-check-inline">
			<input type="radio" class="form-check-input" id="cbteam" name="inlineMaterialRadiosExample1">
			<label class="form-check-label" for="cbteam">Team</label>
			</div>
		</div>
	</div>
	<img  style="display:block;margin:auto;" id="spinner"  src="<?php echo $modulebase?>/assets/images/spinner.gif"></img>

<?php include 'modules/assets/jscripts.php';?>
<script type="text/javascript" src="<?php echo $modulepath.'/assets/js/category.js';?>"></script>
</body>