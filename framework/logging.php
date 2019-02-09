<?php

$logfile = fopen($logfile,"a");
$time = date("Y-m-d h:i:sa");
function LogInfo($msg)
{
	global $logfile;
	global $time;
	
	$bt = debug_backtrace();
    $caller = array_shift($bt);
    $filename = $caller['file'];
    $line = $caller['line'];
	 
	fwrite($logfile,$time.":[INFO]".$msg."[".$filename.":".$line."]\r\n");
}
function LogError($msg)
{
	global $logfile;
	global $time;
	
	$bt = debug_backtrace();
    $caller = array_shift($bt);
    $filename = $caller['file'];
    $line = $caller['line'];
	 
	fwrite($logfile,$time.":[EROR]".$msg."[".$filename.":".$line."]\r\n");
}
function LogWarning($msg)
{
	global $logfile;
	global $time;
	$bt = debug_backtrace();
    $caller = array_shift($bt);
    $filename = $caller['file'];
    $line = $caller['line'];
	 
	fwrite($logfile,$time.":[WARN]".$msg."[".$filename.":".$line."]\r\n");
}
?>