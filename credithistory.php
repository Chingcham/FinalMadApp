<html>
<head>      
        <title>Credit History</title>           
        <meta name="viewport" content="width=device-width,initial-scale=1"> 

<link rel="stylesheet" type="text/css" href="css/ThisIsIt.css">
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery.mobile-1.1.1.min.js"></script>
<script type= "text/javascript">

$(document).ready(function(){
	$("#logout").click(function()
	{
	window.location="index.html";
	})

	$("#dashboard").click(function()
	{
	window.location="home.html";
 	})
});
</script>
<link rel="stylesheet" href="css/jquery.mobile.structure-1.1.1.css" />
</head>
<body>
	<div data-role="page">

		<div data-role="header" data-position="fixed" data-fullscreen="true">

<a href="" data-icon="home"   data-iconpos="notext" id="dashboard"/>Home</a>
<h1>MADApp</h1>
<a href="" data-iconpos="notext" data-icon="logout" id="logout">Logout</a>
</div><!--header-->
<br><br><br>

		
<?php
$name=$_COOKIE['uname'];
//$name="anupama chingacham";
$con=mysql_connect("localhost", "root") or die("Could not connect to database. Please check your internet connection");
$db = mysql_select_db("madapp",$con);

$query=mysql_query("select credit from user where name='$name';");
$r=mysql_fetch_row($query);
echo " You have $r[0]  credits <br><br>";

$r1=mysql_query("select id from user where name='$name';");
$id=mysql_fetch_row($r1);

$rm=mysql_query("select class_id from userclass where user_id='$id[0]' and status='attended' and substitute_id!=0;");
echo "<table><tr><b><td>STATUS</td><td><pre>	</pre></td><td>DATE</td><td><pre>	</pre></td><td>REMARKS</td></b></tr>";
while($subs=mysql_fetch_row($rm))
{
	$r2=mysql_query("select class_on from class where id='$subs[0]';");
	while($r3=mysql_fetch_row($r2))
	{
		echo "<tr><td>Substituted</td><td></td><td>$r3[0]</td><td></td><td>Gained 1 credit</td></tr>";
	}
}
mysql_query("select class_id from userclass where user_id='$id[0]' and status='absent';");
while($subs=mysql_fetch_row($rm))
{
	$r2=mysql_query("select class_on from class where id='$subs[0]';");
	while($r3=mysql_fetch_row($r2))
	{
		echo "<tr><td>Absent</td><td></td><td>$r3[0]</td><td></td><td>Lost 1 credit</td></tr>";
	}
}
echo "</table>";
?>

<div data-role="footer" data-theme="a" data-position="fixed" data-fullscreen="true" data-id="foo">

	<div data-role="navbar"  >
		<ul>
			<li><a href="home.html" data-icon="back" data-ajax="false">Previous</a></li>
			<li><a href="updates.html" data-icon="star" data-ajax="false">Updates</a></li>
			<li><a href="profile.html" data-icon="info">Profile</a></li>
		
		</ul>
	</div><!-- /navbar -->

</div>

</div>

	</body>


</html>