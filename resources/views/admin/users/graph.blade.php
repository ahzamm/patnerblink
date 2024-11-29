<head>
	<style type="text/css">
		}*/
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
				dataPoints: [
				{ x: new Date('2019-01-03'), y: 300 },
				]
			},
			{
				type: "stackedColumn",
				showInLegend: true,
				name: "Upload",
				color: "#23527c",
				dataPoints: [
				{ x: new Date('2019-01-01'), y: 500 },
				]
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
<div id="chartContainer" ></div>
<p id="des" >Powered by Logon Broadband Pvt. Limited</p>
<script src="{{asset('js/canvasjs.min.js')}}"></script>