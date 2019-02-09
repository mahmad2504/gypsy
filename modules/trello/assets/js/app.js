
$(function() 
{
	"use strict";
	console.log("Here");
	google.charts.load('current', {
		callback: drawChart,
		packages: ['table']
	});	
});
function drawChart() 
{
	console.log("Drawing Chart");
	LoadDataTable();
}

function HandleTableData(jsonData) 
{
	$('#wait').css("display","none")
	datatable = new google.visualization.DataTable(jsonData);
	var view = new google.visualization.DataView(datatable);

	for(var i=0;i<jsonData.rows.length;i++)
	{
		var exp = jsonData.rows[i].c[13].v;
		var error = jsonData.rows[i].c[14].v;
		if(error == 1)
		{
			datatable.setProperty(i, 1, 'style', 'color: red;');
			datatable.setProperty(i, 2, 'style', 'color: red;');
			datatable.setProperty(i, 3, 'style', 'color: red;');
			datatable.setProperty(i, 4, 'style', 'color: red;');
			datatable.setProperty(i, 5, 'style', 'color: red;');
			datatable.setProperty(i, 6, 'style', 'color: red;');
			datatable.setProperty(i, 7, 'style', 'color: red;');
			datatable.setProperty(i, 8, 'style', 'color: red;');
			datatable.setProperty(i, 9, 'style', 'color: red;');
			datatable.setProperty(i, 10, 'style', 'color: red;');
			datatable.setProperty(i, 11, 'style', 'color: red;');
		}
		
			/*	datatable.setProperty(i, 1, 'style', 'color: red;');
				
				datatable.setProperty(i, 7, 'style', 'color: red;');
				datatable.setProperty(i, 2, 'style', 'color: red;');
				datatable.setProperty(i, 3, 'style', 'color: red;');
				datatable.setProperty(i, 4, 'style', 'color: red;');
				
				datatable.setProperty(i, 5, 'style', 'color: red;');
				datatable.setProperty(i, 6, 'style', 'color: red;');
				datatable.setProperty(i, 7, 'style', 'color: red;');
				datatable.setProperty(i, 8, 'style', 'color: red;');*/
	}
	/*
		var old = jsonData.rows[i].c[6].v;
		var deadline = jsonData.rows[i].c[7].v;
		var path = jsonData.rows[i].c[10].v;
		var popup='deletepopup';
		var link ='<a href="#'+popup+'" data-rel="popup" data-transition="pop" onclick="selectedticket='
		 link =  link+"'"+path+"'"+'">Delete</a>';
		jsonData.rows[i].c[9].v = link;
		if(deadline.length > 0)
		{
			if(old > deadline)
			{
				datatable.setProperty(i, 0, 'style', 'color: red;');
				datatable.setProperty(i, 1, 'style', 'color: red;');
				
				datatable.setProperty(i, 7, 'style', 'color: red;');
				datatable.setProperty(i, 2, 'style', 'color: red;');
				datatable.setProperty(i, 3, 'style', 'color: red;');
				datatable.setProperty(i, 4, 'style', 'color: red;');
				
				datatable.setProperty(i, 5, 'style', 'color: red;');
				datatable.setProperty(i, 6, 'style', 'color: red;');
				datatable.setProperty(i, 7, 'style', 'color: red;');
				datatable.setProperty(i, 8, 'style', 'color: red;');
			}
			if(old < 0)
			{
				datatable.setProperty(i, 0, 'style', 'color: silver;');
				datatable.setProperty(i, 1, 'style', 'color: silver;');
				
				datatable.setProperty(i, 7, 'style', 'color: silver;');
				datatable.setProperty(i, 2, 'style', 'color: silver;');
				datatable.setProperty(i, 3, 'style', 'color: silver;');
				datatable.setProperty(i, 4, 'style', 'color: silver;');
				
				datatable.setProperty(i, 5, 'style', 'color: silver;');
				datatable.setProperty(i, 6, 'style', 'color: silver;');
				datatable.setProperty(i, 7, 'style', 'color: silver;');
				datatable.setProperty(i, 8, 'style', 'color: silver;');
			}
		}
		
	}
	
	var showRowNumber = true;
	if(details==1)
	{
		view.setColumns([0,1,2,3,4,5,6,7,8,9]);
		showRowNumber = true;
	}
	else
	{
		view.setColumns([0,2,3,4,8,9]);
		showRowNumber = false;
	}*/
	if(params.exporttickets == 1)
		view.setColumns([0,1,2,3,4,5,6,7,8,9,10,11,13]);
	else
		view.setColumns([0,1,2,3,4,5,6,7,8,9,10,11]);
	var options = {
		showRowNumber: true,
		width: '100%', 
		height: '100%',
		allowHtml:true,
		sortColumn: 1,
		sortAscending: false,
		sort: 'enable'
	};
	// Instantiate and draw our chart, passing in some options.
	var chart = new google.visualization.Table(document.getElementById('chart_div'));
	//chart.draw(datatable, options);
	chart.draw(view, options);
}

function LoadDataTable()
{
	$('#wait').css("display","block");
	GetResource(0,null,'data=data',params,null,HandleTableData);
}
