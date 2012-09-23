<?php
require 'functions.php';
?>
<!doctype html>
<html>
<head>
	<!-- datagutt -->
	<title>Is it up?</title>
	<style>
	* {
		margin: 0px;
		padding: 0px;
	}
	html, body {
		height: 100%;
	}
	body {
		background: #fff;
		color: #333;
		font: 15px/1.8em "Helvetica Neue", "HelveticaNeue", Helvetica, Arial, "Lucida Grande", sans-serif;
		-webkit-font-smoothing: antialiased;
		font-size: 25px;
		margin: 10% 5% 0;
		overflow: hidden;
	}
	#wrapper {
		min-width: 960px;
		text-align: center;
		margin: 0 auto;
	}
	/* Inputs */
	input[type="text"] {
		outline: 0;
		-webkit-appearance: none;
		-moz-appearance: none;
		background: white;
		border: none;
	  	display: inline;
		margin: 10px auto;
		padding: 5px;
		text-align: center;
		font-size: 25px;
		color: #AAA;
		border-bottom: 1px solid #ccc;
	}
	a, a:visited {
		color: #00B0FF;
		text-decoration: none;
		-webkit-transition: color 0.1s linear;
		-moz-transition: color 0.1s linear;
		-ms-transition: color 0.1s linear;
		-o-transition: color 0.1s linear;
		transition: color 0.1s linear;
	}
	a:hover {
		color: #0080FF;
	}
	</style>
</head>
<body>
<div id="wrapper">
	<div id="status"></div>
	<form method="post" action="">
		<p>Is <input type="text" name="domain" value="google.com" onclick="clearDomainInput(this);" autocomplete="off" spellcheck="false" /><a href="#" id="bacon">up or not?</a></p>
	</form>
</div>
<!-- because jQuery is for wimps -->
<script src="./scripts/BBQ.js"></script>
<script>
function clearDomainInput(e){
	if (e.cleared){return;}
	e.cleared = true;
	e.value = '';
	e.style.color = '#000';
}
function receiveResponse(returned, domain){
	var status = document.getElementById('status');
	// Check for http at start of url
	realUrl = domain;
	displayUrl = domain;
	if (!domain.match(/^(?:f|ht)tps?:\/\//)){
		realUrl = 'http://' + domain;
	}else{
		displayUrl = domain.replace(/^(?:f|ht)tps?:\/\//, '');
	}
	displayUrl = displayUrl.replace(/\/{2,}/, '/');
	domainLink = '<a href="'+realUrl+'">'+displayUrl+'</a>';
	if(returned == '1'){
		response = domainLink + ' is up!';
	}else if(returned == '2'){
		response = domainLink + ' is up, but returning 404 (not found)!';
	}else if(returned == '0'){
		response = domainLink + ' is down :(';
	}else{
		response = returned;
	}
	status.innerHTML = '<h1>'+response+'</h1>';
}
if(BBQ && BBQ.areFeatures && BBQ.areFeatures('attachListener', 'isHostMethod')){
	function checkIfUp(e){
		if(BBQ.areFeatures('ajaxGet')){
			var domain = document.forms[0].domain.value;
			BBQ.ajaxGet('api.php?url=' + domain, {
				success: function(returned){
					receiveResponse(returned, domain);
				}
			});
		}else{
			document.forms[0].submit();
		}
		if(e && e.preventDefault){
			e.preventDefault();
		}else{
			e.returnValue = false;
		}
	}
	BBQ.attachListener(window, 'load', function(){
		var bacon = document.getElementById('bacon');
		document.documentElement.className += 'js';
		BBQ.attachListener(bacon, 'click', function(e){
			checkIfUp(e);
		});
		BBQ.attachListener(document.forms[0], 'submit', function(e){
			checkIfUp(e);
		});
	});
};
</script>
<script>
<?php
if(!empty($_REQUEST['url'])){
	$url = ltrim($_REQUEST['url'], "/");
	$url = str_replace("/", "//", $url);
	echo "receiveResponse('".getResponse($url)."', '$url');\n";
}
?>
</script>
</body>
</html>