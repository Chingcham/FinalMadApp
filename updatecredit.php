<?php
$name=$_POST['name'];
$sub=$_POST['sub_name'];
$date=$_POST['class_date'];$date=strtotime($date);
$status=$_POST['status'];
$lesson=$_POST['lesson'];

$con=mysql_connect("localhost", "root") or die("Could not connect to database. Please check your internet connection");
$db = mysql_select_db("madapp",$con);

$query=mysql_query("select id from user where name='$name';");
$r1=mysql_fetch_row($query);	//user_id

$query=mysql_query("select class_id from userclass where user_id='r1[0]' and status="projected";");
$que=mysql_query("select id from class where class_on LIKE '$date'; ");
while($r2=mysql_fetch_row($query))
{
	while($r3=mysql_fetch_row($que))
	if(strcmp($r3[0],$r2[0])==0)		//finding class_id
		break;
}

//status=Attended
if(strcmp($status,"Attended")==0)
{
	$flag_sub=0;
	//substituted
	if(!(is_null($sub)))
	{
	$flag_sub=1;
	$query=mysql_query("select id from user where name='$sub';");
	$r2=mysql_fetch_row($query);	//substitute_id
	$query=mysql_query("UPDATE userclass SET substitute_id='$r2[0]', status="attended" 
		WHERE user_id='$r1[0]' and status="projected" and class_id='$r3[0]';");

		//credit when substituted for own class
		$cred_flag=0;
		$res=mysql_query("SELECT batch_id FROM  userbatch WHERE user_id ='$r2[0]';");
		$res1=mysql_fetch_row($res);
		$r2=mysql_query("select batch_id from class where class_id='$r3[0]';");
		while($r4=mysql_fetch_row($r2))
		{
			if($r4[0]==$res1[0])
			{	
				$q1=mysql_query("select credit from user where user_id='r2[0]';");
				$credit=mysql_fetch_row($q1); $credit[0]++;
				$query=mysql_query("UPDATE user SET credit='$credit[0]' 
				WHERE user_id='$r2[0]';");
				$cred_flag=1;
			}
		}

		//credit when substituted for other class
		if($cred_flag==0)
		{
			$q1=mysql_query("select credit from user where user_id='r2[0]';");
			$credit=mysql_fetch_row($q1); $credit[0]+= 0.5;
			$query=mysql_query("UPDATE user SET credit='$credit[0]' 
				WHERE user_id='$r2[0]';");
		}

		//credit for the user who is the one absent but has given substitute
		$q1=mysql_query("select credit from user where user_id='r1[0]';");
		$credit=mysql_fetch_row($q1); $credit[0]--;
		$query=mysql_query("UPDATE user SET credit='$credit[0]' WHERE user_id='$r1[0]';");
	}
	//actually attended
	else
	{
	$query=mysql_query("UPDATE userclass SET status="attended" 
		WHERE user_id='$r1[0]' and status="projected" and class_id='$r3[0]';");
	}

	//settin lesson which was taken
	$q2=mysql_query("select id from lesson where name='$lesson';");
	$lesson_id=mysql_fetch_row($q2);
	$query=mysql_query("UPDATE class SET lesson_id='$lesson_id' WHERE id='$r3[0]';");

}
//status=Cancelled
else if(strcmp($status,"cancelled")==0)
{
	$query=mysql_query("UPDATE userclass SET status="cancelled" 
		WHERE user_id='$r1[0]' and status="projected" and class_id='$r3[0]'");
}

//status=Absent (means no substitute)
else if(strcmp($status,"absent")==0)
{
	$query=mysql_query("UPDATE userclass SET status="absent" 
		WHERE user_id='$r1[0]' and status="projected" and class_id='$r3[0]'");

	//calculating credit
	$q1=mysql_query("select credit from user where user_id='r1[0]'");
	$credit=mysql_fetch_row($q1); $credit[0]-=2;
	$query=mysql_query("UPDATE user SET credit='$credit[0]' WHERE user_id='$r1[0]';");
}

?>