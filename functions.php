<?php
function SaveInSession($params,$data)
{
	foreach($params as $key=>$value)
		$_SESSION[$key] = $value;
	SendResponse('Done');
}
function GetInvoiceAmountByTeam($params,$data)
{
	$result = [];
	$trello = new Trello();
	$teams  = $trello->GetPropertyTeamsList();
	$result['teams'] = $teams ;
	foreach($teams as $team)
	{
		$result[$team] = [];
		$result[$team]['monthly'] = $trello->GetMonthlyInvoiceAmount(null,null,$team);
		$result[$team]['quarterly'] = $trello->GetQuartelyInvoiceAmount(null,null,$team);
		$result[$team]['biannually'] = $trello->GetBiAnnuallyInvoiceAmount(null,null,$team);
		$result[$team]['yearly'] = $trello->GetYearlyInvoiceAmount(null,null,$team);
		
	}
	SendResponse($result);
}
function GetInvoiceAmountByCountry($params,$data)
{
	$result = [];
	$trello = new Trello();
	
	$owner = 'All Countries';
	$result[$owner] = [];
	$result[$owner]['monthly'] = $trello->GetMonthlyInvoiceAmount();
	$result[$owner]['quarterly'] = $trello->GetQuartelyInvoiceAmount();
	$result[$owner]['biannually'] = $trello->GetBiAnnuallyInvoiceAmount();
	$result[$owner]['yearly'] = $trello->GetYearlyInvoiceAmount();
	

	$countries  = $trello->GetPropertyCountriesList();
	
	foreach($countries as $country)
	{
		$result[$country] = [];
		$result[$country]['monthly'] = $trello->GetMonthlyInvoiceAmount(null,$country);
		$result[$country]['quarterly'] = $trello->GetQuartelyInvoiceAmount(null,$country);
		$result[$country]['biannually'] = $trello->GetBiAnnuallyInvoiceAmount(null,$country);
		$result[$country]['yearly'] = $trello->GetYearlyInvoiceAmount(null,$country);
		
	}
	array_unshift($countries , 'All Countries');
	$result['countries'] = $countries ;
	SendResponse($result);
}
function GetInvoiceAmountByOwner($params,$data)
{
	$result = [];
	$trello = new Trello();
	
	$owners  = $trello->GetPropertyOwnersList();
	$result['owners'] = $owners;
	foreach($owners as $owner)
	{
		$result[$owner] = [];
		$result[$owner]['monthly'] = $trello->GetMonthlyInvoiceAmount($owner);
		$result[$owner]['quarterly'] = $trello->GetQuartelyInvoiceAmount($owner);
		$result[$owner]['biannually'] = $trello->GetBiAnnuallyInvoiceAmount($owner);
		$result[$owner]['yearly'] = $trello->GetYearlyInvoiceAmount($owner);
		
	}
	SendResponse($result);
}
function GetInvoiceStatistics($params,$data)
{
	$trello = new Trello();
	$result = [];
	$result['monthly'] = $trello->GetMonthlyInvoiceAmount();
	$result['quarterly'] = $trello->GetQuartelyInvoiceAmount();
	$result['biannually'] = $trello->GetBiAnnuallyInvoiceAmount();
	$result['yearly'] = $trello->GetYearlyInvoiceAmount();
	SendResponse($result);
}
function GetShipmentStatistics($params,$data)
{
	$trello = new Trello();
	$result = [];
	$result['monthly'] = $trello->GetMonthlyShipmentCount();
	$result['quarterly'] = $trello->GetQuartelyShipmentCount();
	$result['biannually'] = $trello->GetBiAnnuallyShipmentCount();
	$result['yearly'] = $trello->GetYearlyShipmentCount();
	$result['property'] = $trello->GetPropertyShipmentCount();
	$result['country'] = $trello->GetCountryShipmentCount();
	$result['team'] = $trello->GetTeamShipmentCount();
	SendResponse($result);
}

function GetTicketData($params,$data)
{
	$trello = new Trello();
	$table = $trello->GetTicketsData();
	SendResponse($table);
}
function GetOptionsList($params,$data)
{
	$trello = new Trello();
	$result = [];
	$result['owners'] = $trello->GetPropertyOwnersList();
	$result['countries'] = $trello->GetPropertyCountriesList();
	$result['teams'] = $trello->GetPropertyTeamsList();
	SendResponse($result);
}
function SyncTrello($params,$data)
{
	$trello = new Trello();
	$trello->Sync();
}
?>

