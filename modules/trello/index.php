<?php
require_once "modules/assets/module.php";

if(!isset($params->refresh))
	$params->refresh = 0;

if(!isset($params->exporttickets))
	$params->exporttickets = 0;

if(!isset($params->whatsapp))
	$params->whatsapp = 0;


//var_dump(GetRequestData());
//var_dump(GetCurrentRoute());
//var_dump(GetModulePath());

$path = GetModuleEndPoint();
require_once($path);
?>