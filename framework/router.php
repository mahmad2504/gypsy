<?php
namespace gypsy;
$currentroute=null;
class Router
{
	private $routes = array();
	private $default = null;
	function __construct()
	{
		
	}
	function SetRoute($method,$endpoint,$resource)
	{
		$parts = explode("/",$endpoint);
		if($parts[0]=='')
			array_shift($parts);
		if(end($parts) == '')
			unset($parts[key($parts)]);
		
		$obj = new \StdClass();
		$obj->count = count($parts);
		$obj->basedir = '';
		$obj->method = $method;
		$obj->parts = $parts;
		for($i=1;$i<$obj->count;$i++)
			$obj->basedir = $obj->basedir."../";
		$obj->resource = $resource;
		//var_dump($obj);
		$this->routes[]=$obj;
	}
	function SetDefaultRoute($func)
	{
		$this->default = $func;
	}
	function Execute($method,$endpoint,$params,$data)
	{
		global $currentroute;
		global $params;
	
		$parts = explode("/",$endpoint);
		
		if($parts[0]=='')
			array_shift($parts);
		if(end($parts) == '')
			unset($parts[key($parts)]);
		
		$count = count($parts);
		
		foreach($this->routes as $route)
		{
			$rparts = explode('?',$route->resource);
			$route->resource = $rparts[0];
			if(count($rparts)>1)
			{
				$keyvalues = explode("&",$rparts[1]);
				foreach($keyvalues as $keyvalue)
				{
					$keyvaluepair = explode("=",$keyvalue);
					$key = $keyvaluepair[0];
					$params->$key = $keyvaluepair[1];
					//var_dump($keyvaluepair);
				}
				//var_dump($keyvalue);
			}
			//var_dump($route);
			//echo $route->method."<br>";
			if($route->method != 'ANY')
				if($route->method != $method)
					continue;

			//echo $route->count." ".$count."<br>";
			if($route->count == $count)
			{
				$i=0;
				$prop = new \StdClass();
				foreach($route->parts as $part)
				{
					if($part[0] == ':')
					{
						$part = str_replace(":",'',$part);
						$prop->$part = $parts[$i++];
						continue;
					}
					//echo $part." ".$parts[$i]."<br>";
					if($part != $parts[$i++])
					{
						$i=0;
						break;
					}
				}
				if($i == $route->count)
				{
					//$parts = explode("/",explode($route->uri,$endpoint)[1]);
					$route->properties = $prop;
					$currentroute=$route;
					
					foreach($currentroute->parts as &$part)
					{
						if($part[0] ==':')
						{
							$spart = substr($part,1,strlen($part));
							$part = $route->properties->$spart;
						}
					}
					//var_dump($currentroute->parts);
					//var_dump($currentroute);
					//echo "Selected Route is "."<BR>";
					
					$func  = $route->resource;
					if(function_exists($func))
					{
						$func($params,$data);
						return null;
					}
					else
					{
						$route->modulepath =  $route->basedir.pathinfo($route->resource)['dirname'];
						return $route->resource;
					}
				}
			}
		}
		$funcname =  end($parts);
		
		if(function_exists($funcname))
		{
			$funcname($params,$data);
			return null;
		}
		$func = $this->default;	
		if($this->default !=  null)
			$func($method,$endpoint,$params,$data);
	}
}
$router = new Router();
foreach($settings->routes as $route)
{
	SetRoute($route->method,$route->uri,$route->endpoint);
}
SetDefaultRoute('\gypsy\DefaultRoute');

function DefaultRoute($method,$endpoint,$params,$data)
{
	$msg = $method.":".$endpoint." Not Found";
	LogInfo($msg);
}
?>