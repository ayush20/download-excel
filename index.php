<!DOCTYPE html>
<html>
<head>
	<title>LoanTap</title>

	<style type="text/css">
		html, body {height:50%;}
		html {display:table; width:100%;}
		body {display:table-cell; text-align:center; vertical-align:middle;}
		#myProgress {
		    width: 100%;
		    background-color: grey;
		}
		#myBar {
		    width: 0%;
		    height: 30px;
		    background-color: green;
		}
	</style>
</head>
<body>
	<p>
		<button onclick="download()">Download Excel</button>
	</p>
	<div id="progress" style="visibility: hidden">
		<div id="myProgress">
		  <div id="myBar"></div>
		</div>
		Processed Row Count : <span id="process_count"></span>
	</div>

	<script type="text/javascript">

		// initiate excel download
		function download(){
			var elem = document.getElementById("myBar");
			document.getElementById("progress").style.visibility = 'visible';

			var source = new EventSource("get.php");
	        source.onmessage = function(event){

	        	var res = JSON.parse( event.data );

	        	if(res.done == 1){
	            	source.close();
//	            	alert('Excel generated successfully');
					window.location.href = res.path;
	        	}else{
		            document.getElementById("process_count").innerHTML = res.count + ' ( ' + res.percent +'% )';
		            elem.style.width = res.percent +'%';
	        	}
	        };
		}

	</script>
</body>
</html>