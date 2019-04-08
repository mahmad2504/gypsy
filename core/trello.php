<?php
class Trello
{
	private $data = [];
	private $owners = [];
	private $countries = [];
	private $teams = [];
	function __construct()
	{
		global $settings;
		if(file_exists($settings->data_folder.'/data.serialized'))
		{
			$content  = file_get_contents($settings->data_folder.'/data.serialized');
			$data = unserialize($content);
			$this->data = $this->ParseTicketsData($data);
	
		}
	}
	function GetLastUpdatedOn()
	{
		global $settings;
		if(file_exists($settings->data_folder.'/data.serialized'))
		{
			return date ("F d Y H:i:s.", filemtime($settings->data_folder.'/data.serialized'));
		}
	}
	function date_biannual($date)
	{
		$month =  date("n", strtotime($date));
		if ($month <= 6) return 1;
		return 2;
	}
	function date_quarter($date)
	{
		$month =  date("n", strtotime($date));
		if ($month <= 3) return 1;
		if ($month <= 6) return 2;
		if ($month <= 9) return 3;
		return 4;
	}
	//////////////////////////////////////////////////////////////////
	function GetPropertyCountriesList()
	{
		return array_values($this->countries);
	}
	function GetPropertyTeamsList()
	{
		return array_values($this->teams);
	}
	function GetPropertyOwnersList()
	{
		return  array_values($this->owners);
	}
	//////////////////////////////////////////////////////////////////////
	function GetTeamShipmentCount()
	{
		$countarray = [];
		foreach($this->data as $d)
		{
			$key = $d->team;
			if(!isset($countarray[$key]))
				$countarray[$key] = 0;
			$countarray[$key]++;
		}
		ksort($countarray);
		return $countarray;
	}
	function GetCountryShipmentCount()
	{
		$countarray = [];
		foreach($this->data as $d)
		{
			$key = $d->origincountry;
			if(!isset($countarray[$key]))
				$countarray[$key] = 0;
			$countarray[$key]++;
		}
		ksort($countarray);
		return $countarray;
	}
	function GetPropertyShipmentCount()
	{
		$countarray = [];
		foreach($this->data as $d)
		{
			$key = $d->property;
			if(!isset($countarray[$key]))
				$countarray[$key] = 0;
			$countarray[$key]++;
		}
		ksort($countarray);
		return $countarray;
	}
	function GetYearlyShipmentCount()
	{
		$countarray = [];
		foreach($this->data as $d)
		{
			$key = date("Y", strtotime($d->received_date));
			if(!isset($countarray[$key]))
				$countarray[$key] = 0;
			$countarray[$key]++;
		}
		ksort($countarray);
		return $countarray;
	}
	function GetBiAnnuallyShipmentCount()
	{
		$countarray = [];
		foreach($this->data as $d)
		{	
			$biannual=$this->date_biannual($d->received_date);
			$key = date("y", strtotime($d->received_date))."-".$biannual."H";
			if(!isset($countarray[$key]))
				$countarray[$key] = 0;
			$countarray[$key]++;
		}
		ksort($countarray);
		return $countarray;
	}
	function GetQuartelyShipmentCount()
	{
		$countarray = [];
		foreach($this->data as $d)
		{
			$quarter=$this->date_quarter($d->received_date);
			$key = date("y", strtotime($d->received_date))."-Q".$quarter;
			if(!isset($countarray[$key]))
				$countarray[$key] = 0;
			$countarray[$key]++;
		}
		ksort($countarray);
		return $countarray;
	}
	function GetMonthlyShipmentCount()
	{
		$countarray = [];
		foreach($this->data as $d)
		{
			$key = date("Y-m", strtotime($d->received_date));
			if(!isset($countarray[$key]))
				$countarray[$key] = 0;
			$countarray[$key]++;
		}
		ksort($countarray);
		$newarray = [];
		foreach($countarray as $date=>$value)
		{
			$key = date("My", strtotime($date));
			$newarray[$key] = $value;
		}
		return $newarray;
	}
	///////// Invoices /////////////////////////
	function GetYearlyInvoiceAmount($owner=null,$country=null,$team=null)
	{
		$countarray = [];
		foreach($this->data as $d)
		{
			if($owner != null)
			{
				if($owner != $d->property)
					continue;
			}
			if($country !=null)
			{
				if($country != $d->origincountry)
					continue;
			}
			if($team !=null)
			{
				if($team != $d->team)
					continue;
			}
			$key = date("Y", strtotime($d->received_date));
			if(!isset($countarray[$key]))
				$countarray[$key] = 0;
			if($d->converted_invoice > 0)
				$countarray[$key] += $d->converted_invoice;
			else
				$countarray[$key] += $d->invoice;
		}
		ksort($countarray);
		$newarray = [];
		foreach($countarray as $key=>$value)
		{
			$newarray[$key] = $value;
		}
		return $newarray;
	}
	
	function GetBiAnnuallyInvoiceAmount($owner=null,$country=null,$team=null)
	{
		$countarray = [];
		foreach($this->data as $d)
		{
			if($owner != null)
			{
				if($owner != $d->property)
					continue;
			}
			if($country !=null)
			{
				if($country != $d->origincountry)
					continue;
			}
			if($team !=null)
			{
				if($team != $d->team)
					continue;
			}
			$biannual=$this->date_biannual($d->received_date);
			$key = date("y", strtotime($d->received_date))."-".$biannual."H";
			if(!isset($countarray[$key]))
				$countarray[$key] = 0;
			if($d->converted_invoice > 0)
				$countarray[$key] += $d->converted_invoice;
			else
				$countarray[$key] += $d->invoice;
		}
		ksort($countarray);
		$newarray = [];
		foreach($countarray as $key=>$value)
		{
			$newarray[$key] = $value;
		}
		return $newarray;
	}
	
	
	function GetQuartelyInvoiceAmount($owner=null,$country=null,$team=null)
	{
		$countarray = [];
		foreach($this->data as $d)
		{
			if($owner != null)
			{
				if($owner != $d->property)
					continue;
			}
			if($country !=null)
			{
				if($country != $d->origincountry)
					continue;
			}
			if($team !=null)
			{
				if($team != $d->team)
					continue;
			}
			$quarter=$this->date_quarter($d->received_date);
			$key = date("y", strtotime($d->received_date))."-Q".$quarter;
			if(!isset($countarray[$key]))
				$countarray[$key] = 0;
			if($d->converted_invoice > 0)
				$countarray[$key] += $d->converted_invoice;
			else
				$countarray[$key] += $d->invoice;
		}
		ksort($countarray);
		$newarray = [];
		foreach($countarray as $key=>$value)
		{
			$newarray[$key] = $value;
		}
		return $newarray;
	}
	
	
	
	function GetMonthlyInvoiceAmount($owner=null,$country=null,$team=null)
	{
		$countarray = [];
		foreach($this->data as $d)
		{
			if($owner != null)
			{
				if($owner != $d->property)
					continue;
			}
			if($country !=null)
			{
				if($country != $d->origincountry)
					continue;
			}
			if($team !=null)
			{
				if($team != $d->team)
					continue;
			}
			$key = date("Y-m", strtotime($d->received_date));
			if(!isset($countarray[$key]))
				$countarray[$key] = 0;
			if($d->converted_invoice > 0)
				$countarray[$key] += $d->converted_invoice;
			else
				$countarray[$key] += $d->invoice;
			//echo $d->invoice."<br>";
			//echo $key." ".$countarray[$key]."<br>";
		}
		ksort($countarray);
		$newarray = [];
		foreach($countarray as $date=>$value)
		{
			$key = date("My", strtotime($date));
			$newarray[$key] = $value;
		}
		//var_dump($newarray);
		return $newarray;
	}
	////////////////////////////////////////////////////////////
	function GetTicketsData()
	{
		return $this->data;
	}
	////////////////////////////////////////////////////////////
	private function ParseTicketsData($data)
	{
		global $params;
		$rowdata = [];
		foreach($data as $d)
		{
			$obj = new \StdClass();
			$row = array();
			$ignore = false;
			$team = 'Unknown';
			$d->export = 0;
			foreach($d->labels as $label)
			{
				if($label->name == 'Export')
				{
					//echo "Dddd";
					$d->export = 1;
					$ignore = true;
					continue;
				}		
				$team  = $label->name;
			}
			/*if($ignore)
			{
				if(isset($params->exporttickets))
				{
					if($params->exporttickets == 1)
					{
					}
					else 
						continue;
				}
				else		
					continue;
			}*/
			//echo $d->name."<br>";
			$fields = explode('-',$d->name);
			if(count($fields)!=4)
			{
				//echo $d->name."<br>";
				SendConsole(time(),"Ticket Name '".$d->name."' has parsing error");
				$obj->name = $d->name;
				$obj->url = $d->url;
				$obj->origincountry = 'Parse Error';
				$obj->origincity = '';
				$obj->owner = '';
				$obj->team = '';
				$obj->property = 'NONE';
				$obj->hscode = 'NONE';
				$obj->shipment_date = '';
				$obj->received_date = '';
				$obj->delay =  0;
				$obj->qos = $obj->delay;
				$obj->invoice = 0;
				$obj->converted_invoice = 0;
				$obj->currency = '';
				$obj->list = $d->list;
				$obj->export = $d->export;
				$obj->error = 1;
				//$row['name'] = '<a href="'.$d->url.'">'.substr($d->name,0,50).'</a>';
				//$row['origincountry'] = 'Parse Error';
				//$row['origincity'] = '';
				//$row['owner'] = '';
				//$row['team'] = '';
				//$row['property'] = 'NONE';
				//$row['hscode'] = 'NONE';
				//$row['shipment_date'] = '';
				//$row['received_date'] = '';
				//$row['delay'] =  0;
				//$row['invoice'] = 0;
				//$row['currency'] = '';
				//$row['list'] = $d->list;
				//$row['export'] = $d->export;
				//$row['error'] = 1;
				
				$this->data[] = $obj;//$row;
				continue;
			}
			$shipment =  new StdClass();
			$shipment->error = 0;
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
			if(strpos($shipment->invoice,'SEK')!= False)
			{
				$shipment->invoice = trim(str_replace('SEK','',$shipment->invoice));
				$shipment->currency = 'SEK';
				//echo $shipment->currency;
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
			else if(strpos($shipment->invoice,'CNY')!= False)
			{
				$shipment->invoice = trim(str_replace('CNY','',$shipment->invoice));
				$shipment->currency = 'CNY';
			}
			else if(strpos($shipment->invoice,'US$')!= False)
			{
				$shipment->invoice = trim(str_replace('US$','',$shipment->invoice));
				$shipment->currency = 'USD';
			}
			else if(strpos($shipment->invoice,'USD')!= False)
			{
				$shipment->invoice = trim(str_replace('USD','',$shipment->invoice));
				$shipment->currency = 'USD';
			}
			else
			{
				SendConsole(time(),"Ticket Name has currency error");
				$shipment->error = 1;
			}
			$shipment->invoice = str_replace(',','',$shipment->invoice);
			
			//echo $shipment->invoice."<br>";
			
			
			$parts = explode('(',$shipment->invoice);
			$shipment->converted_invoice = -1;
			if(count($parts)>1)
			{
				//var_dump($parts);
				$shipment->invoice=trim($parts[0]);
				$shipment->converted_invoice = explode(")",$parts[1])[0];
				$shipment->converted_invoice = trim(str_replace('USD','',$shipment->converted_invoice));
			}
			
			//$shipment->invoice = trim($shipment->invoice);
			
			//echo "-->".$shipment->invoice."--<br>";
			//echo $shipment->error."<br>";
			//echo $shipment->currency."<br>";
			//echo $shipment->converted_invoice."<br>";
			
			if(!ctype_digit(explode(".",$shipment->invoice)[0]))
			{
				$shipment->error = 1;
				$shipment->invoice = 0;
			}
			
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
			
			$obj->name = $shipment->name;
			$obj->url = $d->url;
			//$obj->name = $shipment->name;
			//$obj->url = $d->url;
			//$row['name'] = '<a href="'.$d->url.'">'.substr($shipment->name,0,50).'</a>';
			if(isset($shipment->origincountry))
				//$row['origincountry'] = $shipment->origincountry;
				$obj->origincountry = $shipment->origincountry;
			else
				//$row['origincountry'] = '';
				$obj->origincountry = 'Unknown';
				
			$obj->origincity = $shipment->origincity;
			$obj->owner = $shipment->owner;
			$obj->team = $shipment->team;
			$obj->property = $shipment->property;
			$obj->hscode = $shipment->hscode;
			$obj->shipment_date = $shipment->shippedon;
			$obj->received_date = $shipment->receivedon;
			$obj->delay =  $shipment->delay;
			$obj->invoice = $shipment->invoice;
			$obj->converted_invoice = $shipment->converted_invoice;
			$obj->currency = $shipment->currency;
			$obj->list = $d->list;
			$obj->export = $d->export;
			$obj->error = $shipment->error;
			$obj->qos = $obj->delay;
			$this->owners[$shipment->property] = $shipment->property;
			//echo "--->".$shipment->origincountry."<br>";
			$this->countries[$obj->origincountry] = $obj->origincountry;
			$this->teams[$shipment->team] = $shipment->team;
			$this->data[] = $obj;//$row;
		}
		return $this->data;
	}
	function Sync()
	{
		global $params;
		global $settings;
		$trello_settings = $settings->app->trello;
		
		$lists = array();
		$lists[] = '5a851a762654fc6a36e11f48';
		$lists[] = '5a78b08c6f85c304e464aa07';
		$i=0;
		$fulldata = [];
		foreach($lists as $listid)
		{
			$i++;
			$url = $trello_settings->url.'/lists/'.$listid.'/cards?key='.$trello_settings->key.'&token='.$trello_settings->token;
			//echo $url;
			SendConsole(time(),"Accessing api.trello.com for list #".$i); 

			$content = file_get_contents($url);
			$data = json_Decode($content);
			$content = '';
			$total = count($data);
			SendConsole(time(),count($data)." Cards Found "); 
			$j = 0;
			foreach($data as $d)
			{
				$url= $trello_settings->url.'/card/'.$d->id.'/actions?key='.$trello_settings->key.'&token='.$trello_settings->token.'&filter=updateCheckItemStateOnCard';
				$j++;
				//SendConsole(time(),$d->id); 
				//if($j < 67)
				//	continue;
				
				//if($j > 67)
				//	die();
			
				//echo $d->name."\r\n<br>";
				SendConsole(time(),"Reading card #".$j."/".$total); 	
				
				$content = file_get_contents($url);
				$content = json_Decode($content);
				$debug=array();
				foreach($content as $action)
				{
					$debug[] = $action;
					$action->data->checklist->name = strtolower(trim($action->data->checklist->name));
					$action->data->checkItem->name = strtolower(trim($action->data->checkItem->name));
					
					if($action->data->checklist->name == 'export')
					{
						//var_dump($action->data);
						//var_dump($action->data->checkItem->name);
						if($action->data->checkItem->name == 'delivered')
							if($action->data->checkItem->state == 'complete')
									$d->deliveredon = $action->date;
						
						if($action->data->checkItem->name == 'dispatched')
						{
							if($action->data->checkItem->state == 'complete')
									$d->invoicedon = $action->date;	
						}
						if (strpos($action->data->checkItem->name, 'dispatched') !== false) 
						{
							if(!isset($d->invoicedon))
							{
								if($action->data->checkItem->state == 'complete')
									$d->invoicedon = $action->date;
							}								
						}
						if (strpos($action->data->checkItem->name, 'delivered') !== false) 
						{
							if(!isset($d->deliveredon))
							{
								if($action->data->checkItem->state == 'complete')
									$d->deliveredon = $action->date;
							}								
						}
					}
					else if($action->data->checklist->name == 'shipment')
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
				}
				$d->list = $i;
				//echo $d->desc;
				$fulldata[] = $d;
			}
		}
		
		$data = serialize($fulldata);
		file_put_contents($settings->data_folder."/data.serialized",$data);
		if(isset($params->whatsapp))
			echo file_get_contents('http://ilmcentercom.ipage.com/mumtaz/twilio/sendwhatsappmsg.php?to=923008465671&message=info:trello-dashboard-updated');
		SendConsole(time(),"Done");
	}
}

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