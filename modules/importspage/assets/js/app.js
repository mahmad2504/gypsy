$(function() 
{
	"use strict";
	console.log("Starting JS");
	
	$('#download').click(function(){
		table.download("csv", "data.csv");
	});
	var visible = false;
	var error = 0;
	if(params.exporttickets ==1)
		visible = true;
	var paging = 'local';
	if(params.paging ==0)
		paging = '';
	var enablefilters = false;
	if(params.filter == 1)
		enablefilters = true;
	//console.log(params.exporttickets);
	var settings = 
	{
		tooltips:true,
		layout:"fitDataFill",
		pagination:paging, //enable local pagination.
        paginationSize:15, // this option can take any positive integer value (default = 10)
		columnVertAlign:"bottom", 
		//height:105, // set height of table (in CSS or here), this enables the Virtual DOM and improves render speed dramatically (can be any valid css height value)
		ajaxURL:'GetTicketData', //ajax URL
		//autoColumns:true,
		ajaxResponse:function(url, params, response)
		{
			//console.log(response);
			//url - the URL of the request
			//params - the parameters passed with the request
			//response - the JSON object returned in the body of the response.
			if(response.data === undefined)
				return [];//return the tableData property of a response json object
			return response.data;
		},
		columns:
		[
			{resizable: false,title:"",formatter:"rownum", align:"center", width:"3%", headerSort:false},
			{resizable: false,title:"Shipment Title (Imports)",field:"name", headerFilter:enablefilters, width:"15%",
				formatter:function(cell, formatterParams)
				{
					var value = cell.getValue();
					var row = cell.getRow();
					return '<a href="'+row._row.data.url+'">'+value+'</a>';
				}			
			}, 
			{resizable: false,title:"Country",field:"origincountry", headerFilter:enablefilters,width:"6%",
					
			},
			
			{resizable: false,title:"City", field:"origincity", headerFilter:enablefilters,align:"left", width:"6%",
				
			},
			{resizable: false,title:"Owner", field:"owner", headerFilter:enablefilters, align:"left", width:"8%",
				
			},
			{resizable: false,title:"Team", field:"team", align:"left",headerFilter:enablefilters,width:"5%",
		
			},
			{resizable: false,title:"Propert of", field:"property",headerFilter:enablefilters, align:"left", width:"7%",
		
			},
			{resizable: false,title:"HSCODE",headerFilter:enablefilters, field:"hscode", align:"left", width:"6%",
		
			},
			{resizable: false,title:"Shipped",headerFilter:enablefilters, field:"shipment_date", align:"left", width:"6%",
				formatter:"datetime", 
				formatterParams:{
					inputFormat:"YYYY-MM-DD",
					outputFormat:"DD/MM/YY",
					invalidPlaceholder:"(invalid date)"
				}
			},
			{resizable: false,title:"Received",headerFilter:enablefilters, field:"received_date", align:"left", width:"7%",
				formatter:"datetime", 
				formatterParams:{
					inputFormat:"YYYY-MM-DD",
					outputFormat:"DD/MM/YY",
					invalidPlaceholder:"(invalid date)"
				}
			},
			{resizable: false,title:"Delivery Time",headerFilter:enablefilters, field:"delay",sorter:"number",align:"left", width:"9%",
	
				
			},
			{resizable: false,title:"Invoice",headerFilter:enablefilters, field:"invoice",sorter:"number",align:"left", width:"7%",
				formatter:function(cell, formatterParams)
				{
					var value = cell.getValue();
					var row = cell.getRow();
					if(row._row.data.converted_invoice != -1)
						return value+'<span style="font-size:9px;"> '+row._row.data.converted_invoice+'$</span>';
					return value;
					//return '<a href="'+row._row.data.url+'">'+value+'</a>';
				}	
			},
			{resizable: false,title:"CUR",headerFilter:enablefilters, field:"currency",align:"left", width:"5%",
		
			},
			{resizable: false,title:"Exp",headerFilter:enablefilters, visible: visible,field:"export",align:"left", width:"3%",
		
			},
			{resizable: false,title:"QOS", headerFilter:enablefilters, field:"qos",sorter:"number",align:"left", width:"6%",
				formatter:"star", formatterParams:{stars:5},
				mutator:function(value, data, type, params, component)
				{
					if(value <=5 )
						return 5;
					if(value>5 && value<=10)
						return 4;
					if(value>10 && value<=15)
						return 3;
					if(value>15 && value<=20)
						return 2;
					if(value>20 && value<=25)
						return 1
					return 0;
				}
			},
			{resizable: false,title:"",visible: false, field:"error",align:"left", width:"3%",
				mutator:function(value, data, type, params, component)
				{
					if(data.export == 1)
						return '';
					
					if(value == 1)
					{
						error = 1;
						//console.log(error);
						return 'Ticket Parsing Error';
					}
					else 
						return '';
				},
				formatter:function(cell, formatterParams)
				{
					var value = cell.getValue();
					var row = cell.getRow();
					if(value.length>0)
					{
						return '<i class="fa fa-exclamation-triangle" style="color:red"></i>';
					}
					return value;
				}
			}
		],
		initialFilter:[
			{field:"export", type:"=", value:0}
		],
		initialSort:
		[
			{column:"received_date", dir:"dsc"} //sort by this first
		],
		renderComplete:function()
		{
			//console.log("Rendered "+error);
			if(error == 1)
				this.showColumn("error") ;
		}
	};
	var table = new Tabulator("#table1", settings);
	//setTimeout(function(){ table.replaceData(); alert("Hello"); }, 3000);

})
