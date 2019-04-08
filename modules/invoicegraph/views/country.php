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
	<select id="select" name='selectlist' onchange="selectchange()">
		<!--<option selected value="Monthly">Monthly</option> 
		<option  value="Quarterly">Quarterly</option> 
		<option  value="c152">C152</option>-->
	</select>
	<div id="chart_div"></div>
	<div id="radios" style="text-align: center;display:none;font-size:8x;">
		<div >
		<!-- Material inline 1 -->
			<div class="form-check form-check-inline">
			<input type="radio" class="form-check-input" id="cbmonthly" name="inlineMaterialRadiosExample">
			<label class="form-check-label" for="cbmonthly">Month</label>
			</div>

		<!-- Material inline 2 -->
			<div class="form-check form-check-inline">
			<input type="radio" class="form-check-input" id="cbquarterly" name="inlineMaterialRadiosExample">
			<label class="form-check-label" for="cbquarterly">Quarter</label>
			</div>

		<!-- Material inline 3 -->
			<div class="form-check form-check-inline">
			<input type="radio" class="form-check-input" id="cbbiannually" name="inlineMaterialRadiosExample">
			<label class="form-check-label" for="cbbiannually">Biannual</label>
			</div>
		
		<!-- Material inline 4 -->
			<div class="form-check form-check-inline">
			<input type="radio" class="form-check-input" id="cbannually" name="inlineMaterialRadiosExample">
			<label class="form-check-label" for="cbannually">Annual</label>
			</div>
		</div>
	</div>
	<img  style="display:block;margin:auto;" id="spinner"  src="<?php echo $modulebase?>/assets/images/spinner.gif"></img>
</div>
<?php include 'modules/assets/jscripts.php';?>
<script type="text/javascript" src="<?php echo $modulepath.'/assets/js/country.js';?>"></script>
</body>