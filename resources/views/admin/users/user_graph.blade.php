<head>
	<style type="text/css">
		#des{
			margin-top: -15px;
			margin-top: -15px;
			margin-right: 74%;
			position: absolute;
			background: white;
			color: white;
			padding: 2px 3px 2px 5px;
			border: 2px solid white;
		}
	</style>
</head>
<script>
	window.onload = function () {
		var chart = new CanvasJS.Chart("chartContainer", {
			animationEnabled: true,
			title:{
				text: "Daily Data Usage",
				fontFamily: "arial black",
				fontColor: "black"
			},
			axisX: {
				valueFormatString: "DD MMM",
			},
			axisY:{
				title: "Data in Mega Bytes",
				valueFormatString:"#0.00Mbs",
				gridColor: "#B6B1A8",
				tickColor: "#B6B1A8"
			},
			toolTip: {
				shared: true,
				content: toolTipContent
			},
			data: [{
				type: "stackedColumn",
				showInLegend: true,
				color: "#388a5a",
				name: "Download",
				dataPoints: []
			},
			{
				type: "stackedColumn",
				showInLegend: true,
				name: "Upload",
				color: "#23527c",
				dataPoints: []
			}]
		});
		chart.render();
		function toolTipContent(e) {
			var str = "";
			var total = 0;
			var str2, str3;
			for (var i = 0; i < e.entries.length; i++){
				var  str1 = "<span style= 'color:"+e.entries[i].dataSeries.color + "'> "+e.entries[i].dataSeries.name+"</span>: <strong>"+e.entries[i].dataPoint.y+"</strong>Mbs<br/>";
				total = e.entries[i].dataPoint.y + total;
				str = str.concat(str1);
			}
			str2 = "<span style = 'color:DodgerBlue;'><strong>"+(e.entries[0].dataPoint.x).getFullYear()+"</strong></span><br/>";
			total = Math.round(total * 100) / 100;
			str3 = "<span style = 'color:Tomato'>Total:</span><strong> "+total+"</strong>Mbs<br/>";
			return (str2.concat(str)).concat(str3);
		}
	}
</script>
<div id="chartContainer" style="height: 370px; max-width: 920px; margin: 0px auto;"></div>
<p id="des" >Powered by Logon Broadband Pvt. Limited</p>
<script src="api/canvasjs.min.js"></script>