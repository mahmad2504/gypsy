<?php
$googlechart = GetModulePath()."/../assets/js/loader.js";
$jquery = GetModulePath()."/../assets/js/jquery-3.3.1.min.js";
$gscript = GetModulePath()."/../assets/js/gscript.js";
$modulepath = GetModulePath();
$params = GetParams();
$requestdata=GetRequestData();

function PopulateParams($params)
{
	$del = '';
	foreach($params as $key=>$value)
	{
		if($key == 'data')
			continue;
		if($key == 'view')
			continue;
		if($key == 'test')
			continue;
		
		echo $del.'"'.$key.'":"'.$value.'"';
		$del = ',';
	}
}
?>