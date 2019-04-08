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

	$('#cbproperty').click(function() {
		DrawPropertyShipmentChart(jsondata.data['property']);
	});
	$('#cbcountry').click(function() {
		DrawCountryShipmentChart(jsondata.data['country']);
	});
	$('#cbteam').click(function() {
		DrawTeamShipmentChart(jsondata.data['team']);
	});
	google.charts.load('current', {'packages':['corechart']});
	google.charts.setOnLoadCallback(drawChart);
})
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
	console.log(data);
	return data;
}
var jsondata;
function drawChart() 
{
	var jsonData = $.ajax({
		url: "getshipmentstatistics",
        dataType: "json",
        success: function (json) {
			jsondata = json;
			
			$('#spinner').hide();
			$('#radios').show();
			
			if(params.country == 1)
			{
				$('#cbcountry').attr('checked', true);
				DrawCountryShipmentChart(jsondata.data['country']);
			}
			else if(params.team == 1)
			{
				$('#cbteam').attr('checked', true);
				DrawTeamShipmentChart(jsondata.data['team']);
			}
			else
			{
				$('#cbproperty').attr('checked', true);
				DrawPropertyShipmentChart(jsondata.data['property']);
			}
		}
    }) 
    // Create our data table out of JSON data loaded from server.
    //var data = new google.visualization.DataTable(jsonData);
    // Instantiate and draw our chart, passing in some options.
    //var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
    //chart.draw(data, {width: 400, height: 240});
}
function DrawTeamShipmentChart(data)
{
	var data = ArrayToGoogleChartTable( ['Team', 'Shipments'],data,null);
	data =  new google.visualization.arrayToDataTable(data);
	options.title = 'Shipments for';
	options.bar = {groupWidth: "20%"};
	options.series = {1: {type: 'line'}};
	
	var chart = new google.visualization.PieChart(document.getElementById('shipment_chart_div'));
	chart.draw(data, options);
}
function DrawCountryShipmentChart(data)
{
	var data = ArrayToGoogleChartTable( ['Property', 'Shipments'],data,null);
	data =  new google.visualization.arrayToDataTable(data);
	options.title = 'Shipments from';
	options.bar = {groupWidth: "20%"};
	options.series = {1: {type: 'line'}};
	
	var chart = new google.visualization.PieChart(document.getElementById('shipment_chart_div'));
	chart.draw(data, options);
}
function DrawPropertyShipmentChart(data)
{
	var data = ArrayToGoogleChartTable( ['Property', 'Shipments'],data,null);
	data =  new google.visualization.arrayToDataTable(data);
	
	options.title = 'Property Of';
	options.bar = {groupWidth: "20%"};
	options.series = {1: {type: 'line'}};
	
	var chart = new google.visualization.PieChart(document.getElementById('shipment_chart_div'));
	chart.draw(data, options);
}
function DrawYearShipmentChart(data)
{
	var average = FindAverage(data);
	length = Object.keys(data).length;
	var groupwidth = length*2;
	var data = ArrayToGoogleChartTable( ['Month', 'Shipments','Average'],data,average);
	data =  new google.visualization.arrayToDataTable(data);
	
	options.title = 'Annual';
	options.bar = {groupWidth: groupwidth+"%"};
	options.series = {1: {type: 'line'}};
	options.legend = { position: 'top', alignment: 'start' };

	var chart = new google.visualization.ColumnChart(document.getElementById('shipment_chart_div'));
	chart.draw(data, options);
}
function DrawBiannuallShipmentChart(data)
{
	var average = FindAverage(data);
	length = Object.keys(data).length;
	var groupwidth = length*2;
	var data = ArrayToGoogleChartTable( ['Month', 'Shipments','Average'],data,average);
	data =  new google.visualization.arrayToDataTable(data);
	
	options.title = 'Biannual';
	options.bar = {groupWidth: groupwidth+"%"};
	options.series = {1: {type: 'line'}};
	options.legend = { position: 'top', alignment: 'start' };
	
	var chart = new google.visualization.ColumnChart(document.getElementById('shipment_chart_div'));
	chart.draw(data, options);
}
function DrawQuarterShipmentChart(data)
{
	var average = FindAverage(data);
	length = Object.keys(data).length;
	var groupwidth = length*2;
	var data = ArrayToGoogleChartTable( ['Month', 'Shipments','Average'],data,average);
	data =  new google.visualization.arrayToDataTable(data);
	
	options.title = 'Quarterly';
	options.bar = {groupWidth: groupwidth+"%"};
	options.series = {1: {type: 'line'}};
	options.legend = { position: 'top', alignment: 'start' };
	var chart = new google.visualization.ColumnChart(document.getElementById('shipment_chart_div'));
	chart.draw(data, options);
}
function DrawMonthShipmentChart(data)
{
	var average = FindAverage(data);
	var data = ArrayToGoogleChartTable( ['Month', 'Shipments','Average'],data,average);
	data =  new google.visualization.arrayToDataTable(data);
	
	length = Object.keys(data).length;
	var groupwidth = length*2;
	
	options.title = 'Monthly';
	options.bar = {groupWidth: groupwidth+"%"};
	options.series = {1: {type: 'line'}};
	options.legend = { position: 'top', alignment: 'start' };
	
	var chart = new google.visualization.ColumnChart(document.getElementById('shipment_chart_div'));
	chart.draw(data, options);
	//var anchor1 = document.getElementById('anchor1')
	//anchor1.innerHTML = '<a href="' + chart.getImageURI() + '">D</a>';
	// '<a href="' + chart.getChart().getImageURI() + '">Printable version</a>'
}