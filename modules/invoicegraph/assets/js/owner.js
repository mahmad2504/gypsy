var options = 
{
	curveType: 'function',
	hAxis: { slantedText:true, slantedTextAngle:90, fontSize: 18},
	bar: {groupWidth: "20%"},
	height: 400,
	legend: { position: 'right' },
	vAxis: {minValue: 0},
	//'backgroundColor': 'transparent',
	//hAxis : { textPosition: 'none', gridlines: {color:"#000000"} },
	
	//'backgroundColor': 'transparent',
};
$(function() 
{
	"use strict";
	console.log("Starting Module Visual JS");
	if(typeof(params.owner) === 'undefined')
		params.owner = '';
	if(typeof(params.duration) === 'undefined')
		params.duration = 'month';
	
	$('#cbquarterly').click(function() {
		DrawInvoiceChart('Quarterly Invoices for '+owner+" owned shipments",jsondata.data[owner]['quarterly']);
		duration = 'quarter';
		$('#cbquarterly').attr('checked', true);
	});
	
	$('#cbmonthly').click(function() {
		DrawInvoiceChart('Monthly Invoices for '+owner+" owned shipments",jsondata.data[owner]['monthly']);
		duration = 'month';
		$('#cbmonthly').attr('checked', true);
	});
	$('#cbbiannually').click(function() {
		DrawInvoiceChart('Biannual Invoices for '+owner+" owned shipments",jsondata.data[owner]['biannually']);
		duration = 'biannual';
		$('#cbbiannually').attr('checked', true);
	});
	$('#cbannually').click(function() {
		DrawInvoiceChart('Annual Invoices for '+owner+" owned shipments",jsondata.data[owner]['yearly']);
		duration = 'annual';
		$('#cbannually').attr('checked', true);
	});
	
	
	google.charts.load('current', {'packages':['corechart']});
	google.charts.setOnLoadCallback(drawChart);
})
function selectchange()
{
	owner = jsondata.data.owners[document.getElementById("select").selectedIndex];
	console.log(duration);
	if(duration == 'quarter')
		$('#cbquarterly').click();
	else if(duration == 'biannual')
		$('#cbbiannually').click();
	else if(duration == 'annual')
		$('#cbannually').click();
	else 
		$('#cbmonthly').click();
}
function FindAverage(indata)
{
	var acc = 0;
	var count = 0;
	for (var k in indata)
	{
		count++;
		acc  += indata[k];
	}
	return acc/count;
}
function ArrayToGoogleChartTable(header,indata,average)
{
	var i=0;
	var data = [];
	data[i++] = header;
	for (var k in indata)
	{
		if(average != null)
			var array = [k,indata[k],average];
		else
			var array = [k,indata[k]];
		
		data[i++] = array;
	}
	//console.log(data);
	return data;
}
var jsondata;
var owner = '';
var duration;
function drawChart() 
{
	var jsonData = $.ajax({
		url: "getinvoiceamountbyowner",
        dataType: "json",
        success: function (json) {
			jsondata = json;
			
			console.log(jsondata.data.owners);
			$('#spinner').hide();
			$('#radios').show();
			var dropdown = $("#select");
			
			owner = jsondata.data.owners[0];
			for(var i=0;i<jsondata.data.owners.length;i++)
			{
				if(jsondata.data.owners[i].toLowerCase() == params.owner.toLowerCase())
				{
					dropdown.append($('<option selected></option>').attr('value', jsondata.data.owners[i]).text(jsondata.data.owners[i]));
					owner = jsondata.data.owners[i];
				}
				else
				{
					dropdown.append($('<option></option>').attr('value', jsondata.data.owners[i]).text(jsondata.data.owners[i]));
				}
			}
			
			//console.log(jsondata.data[owner]);
			
			params.duration = params.duration.toLowerCase();
			if(params.duration == 'quarter')
			{
				$('#cbquarterly').click();
			}
			else if(params.duration == 'biannual')
			{
				$('#cbbiannually').click();
			}
			else if(params.duration == 'annual')
			{
				$('#cbannually').click();
			}
			else
			{
				$('#cbmonthly').click();
			}
		}
    }) 
    // Create our data table out of JSON data loaded from server.
    //var data = new google.visualization.DataTable(jsonData);
    // Instantiate and draw our chart, passing in some options.
    //var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
    //chart.draw(data, {width: 400, height: 240});
}
function DrawInvoiceChart(title,data)
{
	console.log(data);
	var average = FindAverage(data);
	var ol = Object.keys(data);

	length = ol.length;
	console.log(length);
	var data = ArrayToGoogleChartTable( ['Duration', 'Invoices','Average'],data,average);
	data =  new google.visualization.arrayToDataTable(data);
	
	//var length = data.length;
	//console.log(length);
	var groupwidth = length*2;
//groupwidth =20;
	options.title = title;
	options.bar = {groupWidth: groupwidth+"%"};
	options.series = {1: {type: 'line'}};
	options.legend = { position: 'top', alignment: 'start' };
	
	var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
	chart.draw(data, options);
	//var anchor1 = document.getElementById('anchor1')
	//anchor1.innerHTML = '<a href="' + chart.getImageURI() + '">D</a>';
	// '<a href="' + chart.getChart().getImageURI() + '">Printable version</a>'
}