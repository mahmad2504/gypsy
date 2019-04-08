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
	if(typeof(params.team) === 'undefined')
		params.team = '';
	if(typeof(params.duration) === 'undefined')
		params.duration = 'month';
	
	$('#cbquarterly').click(function() {
		DrawInvoiceChart('Quarterly Invoices for '+team+" team",jsondata.data[team]['quarterly']);
		duration = 'quarter';
		$('#cbquarterly').attr('checked', true);
	});
	
	$('#cbmonthly').click(function() {
		console.log(team);
		DrawInvoiceChart('Monthly Invoices for  '+team+" team",jsondata.data[team]['monthly']);
		duration = 'month';
		$('#cbmonthly').attr('checked', true);
	});
	$('#cbbiannually').click(function() {
		DrawInvoiceChart('Biannual Invoices for '+team+" team",jsondata.data[team]['biannually']);
		duration = 'biannual';
		$('#cbbiannually').attr('checked', true);
	});
	$('#cbannually').click(function() {
		DrawInvoiceChart('Annual Invoices for '+team+" team",jsondata.data[team]['yearly']);
		duration = 'annual';
		$('#cbannually').attr('checked', true);
	});
	
	
	google.charts.load('current', {'packages':['corechart']});
	google.charts.setOnLoadCallback(drawChart);
})
function selectchange()
{
	team = jsondata.data.teams[document.getElementById("select").selectedIndex];
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
var team = '';
var duration;
function drawChart() 
{
	var jsonData = $.ajax({
		url: "getinvoiceamountbyteam",
        dataType: "json",
        success: function (json) {
			jsondata = json;
			
			console.log(jsondata.data.teams);
			$('#spinner').hide();
			$('#radios').show();
			var dropdown = $("#select");
			
			team = jsondata.data.teams[0];
			console.log(team);
			for(var i=0;i<jsondata.data.teams.length;i++)
			{
				if(jsondata.data.teams[i].toLowerCase() == params.team.toLowerCase())
				{
					dropdown.append($('<option selected></option>').attr('value', jsondata.data.teams[i]).text(jsondata.data.teams[i]));
					team = jsondata.data.teams[i];
				}
				else
				{
					dropdown.append($('<option></option>').attr('value', jsondata.data.teams[i]).text(jsondata.data.teams[i]));
				}
			}
			
			console.log(team);
			
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
	var data = ArrayToGoogleChartTable( ['Duration', 'Invoice Amount','Average Amount'],data,average);
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