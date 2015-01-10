<?php
// Function list
// 1 = username check
// 2 = groupname check
// 3 = profilename check
// 4 = adminname check
// 5 = cspvalue check
// 6 = enable user
// 7 = disable user
// 8 = enable admin
// 9 = disable admin
// 10 = get user id
// 11 = kick csp user
// 12 = send csp osd message
// 13 = get csp user info

require("../config.php");
require("cmum.php");
	
if(isset($_POST["function"]) && $_POST["function"]=="1" && $_POST["username"]<>"") {	
	$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
	$sql=$mysqli->query("SELECT user FROM users WHERE user='".$mysqli->real_escape_string(trim($_POST["username"]))."' LIMIT 1");
	$rowcheck=$sql->num_rows;
echo $rowcheck;
}

if(isset($_POST["function"]) && $_POST["function"]=="2" && $_POST["groupname"]<>"") {	
	$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
	$sql=$mysqli->query("SELECT name FROM groups WHERE name='".$mysqli->real_escape_string(trim($_POST["groupname"]))."' LIMIT 1");
	$rowcheck=$sql->num_rows;
echo $rowcheck;
}

if(isset($_POST["function"]) && $_POST["function"]=="3" && $_POST["profilename"]<>"") {	
	$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
	$sql=$mysqli->query("SELECT name FROM profiles WHERE name='".$mysqli->real_escape_string(trim($_POST["profilename"]))."' LIMIT 1");
	$rowcheck=$sql->num_rows;
echo $rowcheck;
}

if(isset($_POST["function"]) && $_POST["function"]=="4" && $_POST["adminname"]<>"") {	
	$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
	$sql=$mysqli->query("SELECT user FROM admins WHERE user='".$mysqli->real_escape_string(trim($_POST["adminname"]))."' LIMIT 1");
	$rowcheck=$sql->num_rows;
echo $rowcheck;
}

if(isset($_POST["function"]) && $_POST["function"]=="5" && $_POST["cspvalue"]<>"") {	
	$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
	$sql=$mysqli->query("SELECT cspvalue FROM profiles WHERE cspvalue='".$mysqli->real_escape_string(trim($_POST["cspvalue"]))."' LIMIT 1");
	$rowcheck=$sql->num_rows;
echo $rowcheck;
}

if(isset($_POST["function"]) && $_POST["function"]=="6" && $_POST["uid"]<>"") {	
	$status=enableuser($_POST["uid"],$_POST["admlvl"],$_POST["admgrp"],$_POST["admid"]);
echo $status;
}

if(isset($_POST["function"]) && $_POST["function"]=="7" && $_POST["uid"]<>"") {	
	$status=disableuser($_POST["uid"],$_POST["admlvl"],$_POST["admgrp"],$_POST["admid"]);
echo $status;
}

if(isset($_POST["function"]) && $_POST["function"]=="8" && $_POST["aid"]<>"") {	
	$status=enableadmin($_POST["aid"]);
echo $status;
}

if(isset($_POST["function"]) && $_POST["function"]=="9" && $_POST["aid"]<>"") {	
	$status=disableadmin($_POST["aid"]);
echo $status;
}

if(isset($_POST["function"]) && $_POST["function"]=="10" && $_POST["username"]<>"") {	
	$status=usertoid($_POST["username"]);
echo $status;
}

if(isset($_POST["function"]) && $_POST["function"]=="11" && $_POST["username"]<>"") {	
	$status=cspkickuser($_POST["username"]);
echo $status;
}

if(isset($_POST["function"]) && $_POST["function"]=="12" && $_POST["username"]<>"" && $_POST["message"]<>"") {	
	$status=cspsendosd($_POST["username"],$_POST["message"]);
echo $status;
}

if(isset($_POST["function"]) && $_POST["function"]=="13" && $_POST["username"]<>"") {	
	$status=cspgetuserinfo($_POST["username"]);
echo $status;
}
?>