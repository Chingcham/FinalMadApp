<?php
echo "<html><body>";
$flag=0;
$name=$_COOKIE['uname'];
//$name="anupama chingacham";
$con=mysql_connect("localhost", "root") or die("Could not connect to database. Please check your internet connection");
$db = mysql_select_db("madapp",$con);
$r=0;
$query=mysql_query("select credit from user where name='$name';");
$r=mysql_fetch_row($query);
echo " You have $r[0]  credits <br><br>";

$r1=mysql_query("select id from user where name='$name';");
$id=mysql_fetch_row($r1);
echo "<table><tr><b><td>STATUS</td><td><pre>	</pre></td><td>DATE</td><td><pre>	</pre></td><td>REMARKS</td></b></tr>";

//absent without substitute
$rm1=mysql_query("select class_id from userclass where user_id='$id[0]' and status='absent';");
if(!(is_null($rm1)))
{
while($subs=mysql_fetch_row($rm1))
{
	$r2=mysql_query("select class_on from class where id='$subs[0]' ORDER BY class_on;");
	while($r3=mysql_fetch_row($r2))
	{
		echo "<tr><td>Absent without substitute</td><td></td><td>$r3[0]</td><td></td><td>Lost 2 credits</td></tr>";
	}
	$flag++;
}
}

//absent with substitute
$rm2=mysql_query("select class_id from userclass where user_id='$id[0]' and status='attended' and substitute_id!=0;");
if(!(is_null($rm2)))
{
while($subs=mysql_fetch_row($rm2))
{
	$r2=mysql_query("select class_on from class where id='$subs[0]' ORDER BY class_on;");
	while($r3=mysql_fetch_row($r2))
	{
		echo "<tr><td>Absent with substitute</td><td></td><td>$r3[0]</td><td></td><td>Lost 1 credit</td></tr>";
	}
	$flag++;
}
}

//substituted
$rm3=mysql_query("select class_id from userclass where substitute_id='$id[0]' and status='attended';");
if(!(is_null($rm3)))
{
while($subs=mysql_fetch_row($rm3))
{
	$res=mysql_query("SELECT batch_id FROM  userbatch WHERE user_id ='$id[0]'");$res1=mysql_fetch_row($res);
	$r2=mysql_query("select class_on, batch_id from class where id='$subs[0]' ORDER BY class_on;");
	while($r3=mysql_fetch_row($r2))
	{
	//for own class
		if($r3[1]==$res1[0])
			echo "<tr><td>Substituted for own class</td><td></td><td>$r3[0]</td><td></td><td>Gained 1 credit</td></tr>";
	//for other class
		if($r3[1]!=$res1[0])
			echo "<tr><td>Substituted</td><td></td><td>$r3[0]</td><td></td><td>Gained 0.5 credit</td></tr>";
	}
	$flag++;
}
}
if($flag==0)
echo "You have neither substituted nor been absent.";
echo "</table></body></html>";
?>