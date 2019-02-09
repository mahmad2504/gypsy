<?php
require "modules/vendor/Tmilos/vendor/autoload.php";

use Tmilos\GoogleCharts\DataTable\Column;
use Tmilos\GoogleCharts\DataTable\ColumnType;
use Tmilos\GoogleCharts\DataTable\DataTable;
use Tmilos\GoogleCharts\DataTable\Row;
use Tmilos\Value\AbstractEnum;

$dataTable = new DataTable([
	//Column::create(ColumnType::STRING())->setLabel('id'),
	Column::create(ColumnType::STRING())->setLabel('Shipment Title'),
	Column::create(ColumnType::STRING())->setLabel('Country'),
	Column::create(ColumnType::STRING())->setLabel('City'),
	Column::create(ColumnType::STRING())->setLabel('Owner'),
	Column::create(ColumnType::STRING())->setLabel('Team'),
	Column::create(ColumnType::STRING())->setLabel('Property of'),
	Column::create(ColumnType::STRING())->setLabel('HSCode'),
	Column::create(ColumnType::STRING())->setLabel('Shipped'),
	Column::create(ColumnType::STRING())->setLabel('Received'),
	Column::create(ColumnType::NUMBER())->setLabel('Shipment Time'),
	Column::create(ColumnType::NUMBER())->setLabel('Invoice Price'),
	Column::create(ColumnType::STRING())->setLabel('Currency'),
	Column::create(ColumnType::STRING())->setLabel('L'),
	Column::create(ColumnType::STRING())->setLabel('Export'), //13
	Column::create(ColumnType::STRING())->setLabel('Error'),   //14
	]);

$lists = array();
$lists[] = '5a851a762654fc6a36e11f48';
$lists[] = '5a78b08c6f85c304e464aa07';

$database = $modulepath."/data.serialized";

if(!file_exists($database))
	$params->refresh = 1;

if($params->refresh == 1)
{
	$fulldata = array();
	{
		$i=0;
		foreach($lists as $listid)
		{
			$i++;
			$url = 'https://api.trello.com/1/lists/'.$listid.'/cards?key=005173e331a61db3768a13e6e9d1160e&token=0e457d47dbd6eb1ed558ac42f8ba03b94738cac35a738d991cdf797d6fcfbbe9';
			$content = file_get_contents($url);
			//file_put_contents('data.json',$content);
			$data = json_Decode($content);
			$content = '';
			foreach($data as $d)
			{
				$url= 'https://api.trello.com/1/card/'.$d->id.'/actions?key=005173e331a61db3768a13e6e9d1160e&token=0e457d47dbd6eb1ed558ac42f8ba03b94738cac35a738d991cdf797d6fcfbbe9&filter=updateCheckItemStateOnCard';
				//echo $url."<br>";
				$content = file_get_contents($url);
				$content = json_Decode($content);
				$debug=array();
				foreach($content as $action)
				{
					$debug[] = $action;
					$action->data->checklist->name = strtolower(trim($action->data->checklist->name));
					$action->data->checkItem->name = strtolower(trim($action->data->checkItem->name));
					if($action->data->checklist->name == 'shipment')
					{
						if($action->data->checkItem->name == 'shipment invoice') //1st Priority invoicedon
						{
							if($action->data->checkItem->state == 'complete')
								$d->invoicedon = $action->date;
						}
						else if(($action->data->checkItem->name == 'delivered')||
						        ($action->data->checkItem->name == 'shipment received')) //2nd Priority deliveredon
						{
							if(!isset($d->deliveredon))
							{
								if($action->data->checkItem->state == 'complete')
									$d->deliveredon = $action->date;
							}
						}
						 
					}
					else if( ($action->data->checklist->name == 'customs clearance')||
							 ($action->data->checklist->name == 'custom clearance'))
					{
						if($action->data->checkItem->name == 'delivered')// 1st Priority deliveredon
						{
							if($action->data->checkItem->state == 'complete')
								$d->deliveredon = $action->date;
						}
						else if($action->data->checkItem->name == 'shipment received')//2nd Priority deliveredon
						{
							if(!isset($d->deliveredon))
							{
								if($action->data->checkItem->state == 'complete')
									$d->deliveredon = $action->date;
							}
						}
					}
					else 
					{
						if($action->data->checkItem->name == 'shipment invoice') //2nd Priority invoicedon
						{
							if(!isset($d->invoicedon))
							{
								if($action->data->checkItem->state == 'complete')
									$d->invoicedon = $action->date;
							}
						}
						else if(($action->data->checkItem->name == 'delivered')||
						        ($action->data->checkItem->name == 'shipment received')) //2nd Priority deliveredon
						{
							if(!isset($d->deliveredon))
							{
								if($action->data->checkItem->state == 'complete')
									$d->deliveredon = $action->date;
							}
						}
					}			
					/*
					if($action->data->checkItem->name == 'Delivered')
					{
						if($action->data->checkItem->state == 'complete')
							$d->deliveredon = $action->date;
					}
					if($action->data->checkItem->name == 'Shipment Invoice')
					{
						if($action->data->checkItem->state == 'complete')
							$d->invoicedon = $action->date;
					}
					if($action->data->checkItem->name == 'Shipment Dispatched')
					{
						if($action->data->checkItem->state == 'complete')
							$d->shipmentdispatchedon = $action->date;
					}
					if($action->data->checkItem->name == 'Shipment Received')
					{
						if($action->data->checkItem->state == 'complete')
							$d->shipmentreceivedon = $action->date;
					}*/
				}
				//if($d->id == '5b7f9419cfd2e71dfee4d322')
				//{
					//echo "**********************************************<br>";
					//var_dump($d);
					//foreach($debug as $action)
					//	var_dump($action);
					//echo "**********************************************<br>";
				//}
				$d->list = $i;
				$fulldata[] = $d;
			}
		}
		$data = serialize($fulldata);
		file_put_contents($database,$data);
	}
}
//return;

$content  = file_get_contents($database);
$data = unserialize($content);
$rowdata = array();

foreach($data as $d)
{
	$row = array();
	$ignore = false;
	$team = 'Unknown';
	//<Shipment Name> - <City/Country Name>-<Person Name>-<Invoice Price>
	//echo "#####".$d->name.'<br>';
	$d->export = 0;
	foreach($d->labels as $label)
	{
		if($label->name == 'Export')
		{
			$d->export = 1;
			$ignore = true;
			continue;
		}
		
		$team  = $label->name;
	}
	if($ignore)
	{
		if($params->exporttickets==1)
		{
			
		}
		else		
			continue;
	}
	
	$fields = explode('-',$d->name);
	if(count($fields)!=4)
	{
		//echo $d->name."<br>";

		$row['name'] = '<a href="'.$d->url.'">'.substr($d->name,0,50).'</a>';
		$row['origincountry'] = 'Parse Error';
		$row['origincity'] = '';
		$row['owner'] = '';
		$row['team'] = '';
		$row['property'] = 'NONE';
		$row['hscode'] = 'NONE';
		$row['shipment_date'] = '';
		$row['received_date'] = '';
		$row['delay'] =  0;
		$row['invoice'] = 0;
		$row['currency'] = '';
		$row['list'] = $d->list;
		$row['export'] = $d->export;
		$row['error'] = 1;
		$rowdata[] = $row;

		continue;
	}

	
	$shipment =  new StdClass();
	$shipment->error = '';
	
	$shipment->name = $fields[0];
	
	$shipment->origincity  = '';
	$shipment->origincoutry = $fields[1];
	$origin = explode("/",$fields[1]);
	if(count($origin)>1)
	{
		$shipment->origincity = $origin[0];
		$shipment->origincountry = $origin[1];
	}
	$shipment->owner = $fields[2];
	$shipment->invoice = $fields[3];
	//echo $shipment->name."<br>";
	//echo $shipment->invoice."<br>";
	$shipment->currency = 'UNKNOWN';
	if(strpos($shipment->invoice,'USD')!= False)
	{
		$shipment->invoice = trim(str_replace('USD','',$shipment->invoice));
		$shipment->currency = 'USD';
	}
	else if(strpos($shipment->invoice,'Euros')!= False)
	{
		$shipment->invoice = trim(str_replace('Euros','',$shipment->invoice));
		$shipment->currency = 'EUR';
	}
	else if(strpos($shipment->invoice,'EUROS')!= False)
	{
		$shipment->invoice = trim(str_replace('EUROS','',$shipment->invoice));
		$shipment->currency = 'EUR';
	}
	else if(strpos($shipment->invoice,'EUR')!= False)
	{
		$shipment->invoice = trim(str_replace('EUR','',$shipment->invoice));
		$shipment->currency = 'EUR';
	}
	else if(strpos($shipment->invoice,'PKR')!= False)
	{
		$shipment->invoice = trim(str_replace('PKR','',$shipment->invoice));
		$shipment->currency = 'PKR';
	}
	else if(strpos($shipment->invoice,'US$')!= False)
	{
		$shipment->invoice = trim(str_replace('US$','',$shipment->invoice));
		$shipment->currency = 'USD';
	}
	else
		$shipment->error = 1;

	$shipment->invoice = str_replace(',','',$shipment->invoice);
	
	//echo $shipment->invoice."<br>";
	if(!ctype_digit(explode(".",$shipment->invoice)[0]))
	{
		$shipment->error = 1;
		$shipment->invoice = 0;
	}
	
	//echo $shipment->error."<br>";
	//echo $shipment->invoice."<br>";
	//echo $shipment->currency."<br>";
	$shipment->invoice = $shipment->invoice * 1;
	
	$shipment->team = $team;
	
	//echo "---->".$d->desc.'<br>';
	$shipment->property = 'Unknown';
	
	//echo $d->desc.'<br>';
	$hscode = explode('HS Code:',$d->desc);
	if(count($hscode)>1)
	{
		$hscode = explode(' ',trim($hscode[1]));
		if(strlen($hscode[0])<9)
		{
			$shipment->hscode = 'NONE';
		}
		else
		{
			$shipment->hscode = substr ($hscode[0],0,9); 
		}
	}
	else
		$shipment->hscode = 'NONE';
	
	if(strlen($shipment->hscode)==0)
		$shipment->hscode = 'NONE';
	
	//echo $shipment->hscode."<br>";
	if(strpos(strtolower($d->desc), 'customer property')!=False)
		$shipment->property = 'Customer';
	
	if(strpos(strtolower($d->desc), 'mentor property')!=False)
		$shipment->property = 'Mentor';
	
	//$date = new DateTime($d->dateLastActivity);
	//$shipment->shippedon = $date->format('y-m-d');

	$date = new DateTime("1970-01-01");
	if(isset($d->invoicedon))
		$date = new DateTime($d->invoicedon);
	
	/*if(isset($d->shipmentdispatchedon))
		$date = new DateTime($d->shipmentdispatchedon);*/
	
	if($date->format('y-m-d') == '70-01-01')
		$shipment->shippedon = '';//$date->format('y-m-d');
	else
		$shipment->shippedon = $date->format('Y-m-d');
	

	
	$date = new DateTime("1970-01-01");
	if(isset($d->deliveredon))
		$date = new DateTime($d->deliveredon);
	
	/*if(isset($d->shipmentreceivedon))
		$date = new DateTime($d->shipmentreceivedon);*/
	
	if($date->format('y-m-d') == '70-01-01')
		$shipment->receivedon = '';//$date->format('y-m-d');
	else
	{
		$shipment->receivedon = $date->format('Y-m-d');
	}
	
	$shipment->delay = 0;
	if(($shipment->receivedon != '')&&($shipment->shippedon != ''))
	{
		$earlier = new DateTime($shipment->shippedon);
		$later = new DateTime($shipment->receivedon);
		$shipment->delay = $later->diff($earlier)->format("%a")*1;
	}
	//echo $date->format('d-m-y')."<br>";
	//echo $d->id."<br>";
	//$datetime = DateTime::createFromFormat('Y-m-d\TH:i:s+', '2013-02-13T08:35:34.195Z');
	
	//var_dump($shipment);
	//$row['id'] = $d->id;
	$row['name'] = '<a href="'.$d->url.'">'.substr($shipment->name,0,50).'</a>';
	if(isset($shipment->origincountry))
		$row['origincountry'] = $shipment->origincountry;
	else
		$row['origincountry'] = '';
	$row['origincity'] = $shipment->origincity;
	$row['owner'] = $shipment->owner;
	$row['team'] = $shipment->team;
	$row['property'] = $shipment->property;
	$row['hscode'] = $shipment->hscode;
	$row['shipment_date'] = $shipment->shippedon;
	$row['received_date'] = $shipment->receivedon;
	$row['delay'] =  $shipment->delay;
	$row['invoice'] = $shipment->invoice;
	$row['currency'] = $shipment->currency;
	$row['list'] = $d->list;
	$row['export'] = $d->export;
	$row['error'] = $shipment->error;
	//echo "--->".$shipment->currency."<br>";
	$rowdata[] = $row;
	//echo '******************************<br>';
	//var_dump($d);
}

$dataTable->addRows($rowdata);
if($params->whatsapp == 1)
	file_get_contents('http://ilmcentercom.ipage.com/mumtaz/twilio/sendwhatsappmsg.php?to=923008465671&message=info:trello-dashboard-updated');

echo json_encode($dataTable);

 //public 'name' => string 'Lauterbach Debuggers & Power Debug Modules ' (length=43)
  //public 'origin' => string ' Mobile ' (length=8)
  //public 'owner' => string ' Ahmed Shakeel ' (length=15)
  //public 'invoice' => int 8680
  //public 'team' => string 'AND' (length=3)
  //public 'property' => string 'Mentor' (length=6)
  //public 'hscode' => string '8537.1090' (length=9)
  //public 'shippedon' => st

// 5a78b043543acc40d8ba06f9
// board = 5a78b043543acc40d8ba06f9
// list = 5a78b08c6f85c304e464aa07
// 005173e331a61db3768a13e6e9d1160e
//0e457d47dbd6eb1ed558ac42f8ba03b94738cac35a738d991cdf797d6fcfbbe9
//https://api.trello.com/1/members/me/boards?
//key=005173e331a61db3768a13e6e9d1160e
//token=0e457d47dbd6eb1ed558ac42f8ba03b94738cac35a738d991cdf797d6fcfbbe9

//https://api.trello.com/1/members/me/boards?key=005173e331a61db3768a13e6e9d1160e&token=0e457d47dbd6eb1ed558ac42f8ba03b94738cac35a738d991cdf797d6fcfbbe9
//https://api.trello.com/1/boards/5a78b043543acc40d8ba06f9/lists?key=005173e331a61db3768a13e6e9d1160e&token=0e457d47dbd6eb1ed558ac42f8ba03b94738cac35a738d991cdf797d6fcfbbe9
//https://api.trello.com/1/boards/5a78b043543acc40d8ba06f9/cards?key=005173e331a61db3768a13e6e9d1160e&token=0e457d47dbd6eb1ed558ac42f8ba03b94738cac35a738d991cdf797d6fcfbbe9

//https://api.trello.com/1/lists/5a78b08c6f85c304e464aa07/cards?key=005173e331a61db3768a13e6e9d1160e&token=0e457d47dbd6eb1ed558ac42f8ba03b94738cac35a738d991cdf797d6fcfbbe9

//https://api.trello.com/1/cards/5bc022a9694c3c0bef5a8655?fields=all&?key=005173e331a61db3768a13e6e9d1160e&token=0e457d47dbd6eb1ed558ac42f8ba03b94738cac35a738d991cdf797d6fcfbbe9
//https://api.trello.com/1/cards/5bc022a9694c3c0bef5a8655?fields=all&?key=005173e331a61db3768a13e6e9d1160e&token=0e457d47dbd6eb1ed558ac42f8ba03b94738cac35a738d991cdf797d6fcfbbe9
//https://api.trello.com/1/boards/5a78b043543acc40d8ba06f9/actions?key=005173e331a61db3768a13e6e9d1160e&token=0e457d47dbd6eb1ed558ac42f8ba03b94738cac35a738d991cdf797d6fcfbbe9';
//https://api.trello.com/1/cards/5c0e342e12525b5d74f4d145/actions?key=005173e331a61db3768a13e6e9d1160e&token=0e457d47dbd6eb1ed558ac42f8ba03b94738cac35a738d991cdf797d6fcfbbe9
//https://api.trello.com/1/list/5a78b08c6f85c304e464aa07/actions?key=005173e331a61db3768a13e6e9d1160e&token=0e457d47dbd6eb1ed558ac42f8ba03b94738cac35a738d991cdf797d6fcfbbe9

//https://api.trello.com/1/checklists/5b7f943310a52e3c371ed49d?key=005173e331a61db3768a13e6e9d1160e&token=0e457d47dbd6eb1ed558ac42f8ba03b94738cac35a738d991cdf797d6fcfbbe9
?>

