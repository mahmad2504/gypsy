<?php
require "mongodb/vendor/autoload.php";
class MongoCollection
{
	private $col = null;
	private $mongoclient = null;
	function __construct($mongoclient,$colname)
	{
		$this->col = $mongoclient->SelectCollection($colname);
		$this->mongoclient = $mongoclient;
		
	}
	function GetHandle()
	{
		return $this->col;	
	}
	function ChangeCol($colname)
	{
		$this->col = $this->mongoclient->SelectCollection($colname);
	}
}
class MongoClient
{
	private $client;
	private $db;
	function __construct($server=null,$dbname=null)
	{
		global $settings;
		
		if($server == null)
			$server = $settings->mongo->server;
		if($dbname == null)
			$dbname = $settings->mongo->db;
		
		$this->client = new MongoDB\Client($server);
		$this->db = $this->client->$dbname;
	}
	function SelectCollection($colname)
	{
		return $this->db->$colname;
	}
	

}
function StringDateToMongoDate($datestring) // Resets hours/minutes/seconds to zero
{
	$date = new DateTime($datestring);
	$date->setTime(0,0,0);
	$ts = $date->getTimestamp();
	return new MongoDB\BSON\UTCDateTime($ts*1000);
}
function ConvertId($id)
{
	return new MongoDB\BSON\ObjectId($id);
}	
$db = new MongoClient();
/*
$test =  new MongoClient($server,'test');
$usercol = new MongoCollection($test,'user');
$usercol->Drop();
$data = [ ["name"=>"mumtaz","age"=>45],["name"=>"fouzia","age"=>43] ];

$usercol->Insert($data);
$usercol->CreateTextIndex(['name','age']);

$records = $usercol->SearchText('mumtaz',new Projection(['name']));
var_dump($records);
$records = $usercol->Find(['name'=>'mumtasz','age'=>43],new Projection(['name']),'or');
var_dump($records);
$records = $usercol->FindIn('name',['mumtaz','fouzia'],new Projection(['name']));
var_dump($records);*/
?>