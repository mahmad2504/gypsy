<?php
	session_start();
	
	if(isset($_SESSION['noheaders']))
		$params->noheaders = $_SESSION['noheaders'];
	else
		$params->noheaders = 0;
	
	if(isset($_SESSION['filter']))
		$params->filter = $_SESSION['filter'];
	else
		$params->filter = 0;
	
	if(isset($_SESSION['paging']))
		$params->paging = $_SESSION['paging'];
	else
		$params->paging = 1;
	
?>