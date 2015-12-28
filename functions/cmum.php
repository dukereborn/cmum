<?php
//
// common functions
//
function counter() {
	// return structure
	// 0 = users
	// 1 = groups
	// 2 = profiles
	// 3 = admins
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
			if($_SESSION[$secretkey."admlvl"]=="0" ||  $_SESSION[$secretkey."admlvl"]=="1") {
				$u_sql=$mysqli->query("SELECT id FROM users");	
			} elseif($_SESSION[$secretkey."admlvl"]=="2" && $_SESSION[$secretkey."admgrp"]<>"0") {
				$u_sql=$mysqli->query("SELECT id FROM users WHERE usrgroup='".$_SESSION[$secretkey."admgrp"]."'");
			} else {
				$u_sql="";
			}
			$g_sql=$mysqli->query("SELECT id FROM groups");
			$p_sql=$mysqli->query("SELECT id FROM profiles");
			$a_sql=$mysqli->query("SELECT id FROM admins");
		mysqli_close($mysqli);
return($u_sql->num_rows.";".$g_sql->num_rows.";".$p_sql->num_rows.";".$a_sql->num_rows);
}

function errorpage($errortitle,$errortext,$charset,$title,$uribase,$ver,$build,$mod) {
	$newuribase=substr($uribase,0,strrpos($uribase,"/")+1);
	$file=file_get_contents("includes/error.php");
	$file=str_replace("%errortitle%",$errortitle,$file);
	$file=str_replace("%errortext%",$errortext,$file);
	$file=str_replace("%charset%",$charset,$file);
	$file=str_replace("%title%",$title,$file);
	$file=str_replace("%uribase%",$newuribase,$file);
	if($build<>"" && $mod=="") {
		$file=str_replace("%version%",$ver."-".$build,$file);
	} elseif($build<>"" && $mod<>"") {
		$file=str_replace("%version%",$ver."-".$build." (".$mod.")",$file);
	} elseif($build=="" && $mod<>"") {
		$file=str_replace("%version%",$ver." (".$mod.")",$file);
	} else {
		$file=str_replace("%version%",$ver,$file);
	}
	print($file);
	exit;
}

function truefalse($value) {
	if($value=="true") {
		$status="1";
	} elseif($value=="false") {
		$status="0";
	} elseif($value=="1") {
		$status="1";
	} elseif($value=="0") {
		$status="0";
	} else {
		$status="";
	}
return($status);
}

function numbertotf($value) {
	if($value=="1") {
		$status="true";
	} elseif($value=="0") {
		$status="false";
	} else {
		$status="";
	}
return($status);
}

function formatdate($format,$date) {
	if($date<>"0000-00-00") {
		$newdate=date($format,strtotime($date));	
	} else {
		$newdate="";
	}
return($newdate);
}

function xmloutformat($name,$value) {
	if($value<>"") {
		$status=$name."=\"".$value."\" ";
	} else {
		$status="";
	}
return($status);
}

function xmloutformatwusrgrp($name,$value,$usrgrp) {
	if($value<>"") {
		if(idtogrp($usrgrp)<>"") {
			$status=$name."=\"[".idtogrp($usrgrp)."] ".$value."\" ";
		} else {
			$status=$name."=\"".$value."\" ";
		}
	} else {
		$status="";
	}
return($status);
}

function valuetohttpprotocol($value) {
	if($value=="0") {
		$status="http";
	} elseif($value=="1") {
		$status="https";
	}
return($status);
}

function printdate($value) {
	if($value=="0000-00-00") {
		$status="";
	} else {
		$status=$value;
	}
return($status);
}

function percent($num_amount,$num_total) {
	$count1=$num_amount/$num_total;
	$count2=$count1*100;
	$count=number_format($count2,1);
return($count);
}

function sendemail($to,$subject,$body) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
	if(file_exists("functions/class.phpmailer.php")) {
		require("functions/class.phpmailer.php");
	} else {
		require("../functions/class.phpmailer.php");
	}
	if(file_exists("functions/class.smtp.php")) {
		require("functions/class.smtp.php");
	} else {
		require("../functions/class.smtp.php");
	}
		if($to=="") {
			$status="1";
		} else {
			$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
				$sql=$mysqli->query("SELECT email_host,email_port,email_secure,email_auth,email_authuser,email_authpass,email_fromname,email_fromaddr FROM settings WHERE id='1'");
				$data=$sql->fetch_array();
			mysqli_close($mysqli);
				$mail=new PHPMailer;
					$mail->isSMTP();
					$mail->CharSet=$charset;
					$mail->Host=$data["email_host"];
					$mail->Port=$data["email_port"];
						if($data["email_secure"]==1) {
							$mail->SMTPSecure="ssl";
						} elseif($data["email_secure"]==2) {
							$mail->SMTPSecure="tls";
						}
						if($data["email_auth"]==1) {
							$mail->SMTPAuth=true;
							$mail->Username=$data["email_authuser"];
							$mail->Password=$data["email_authpass"];
						} else {
							$mail->SMTPAuth=false;
						}
					$mail->isHTML(false);
					$mail->setFrom($data["email_fromaddr"],$data["email_fromname"]);
					$mail->addAddress(trim($to));
					$mail->Subject=trim($subject);
					$mail->Body=trim($body);
						if(!$mail->send()) {
						    $status="2";
						} else {
						    $status="0";
						}
		}
return($status);
}

//
// admin functions
//
function addadmin($user,$pass,$name,$admlvl,$ugroup) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
			$mysqli->query("INSERT INTO admins (user,pass,name,enabled,admlvl,ugroup) VALUES ('".$user."','".hash("sha256",$pass.$secretkey)."','".$name."','1','".$admlvl."','".$ugroup."')");
			$status="0";
		mysqli_close($mysqli);
return($status);
}

function editadmin($aid,$user,$name,$admlvl,$ugroup) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		if($admlvl=="2" && $ugroup=="0" || $admlvl=="2" && $ugroup=="") {
			$status="1";
		} else {
			$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
				$mysqli->query("UPDATE admins SET user='".$user."',name='".$name."',admlvl='".$admlvl."',ugroup='".$ugroup."' WHERE id='".$aid."'");
			mysqli_close($mysqli);
			$status="0";	
		}
return($status);
}

function chpassadmin($aid,$pass1,$pass2) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		if($pass1=="" || $pass2=="") {
			$status="1";
		} else {
			if($pass1<>$pass2) {
				$status="2";
			} else {
				$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
					$mysqli->query("UPDATE admins SET pass='".hash("sha256",$pass1.$secretkey)."' WHERE id='".$mysqli->real_escape_string($aid)."'");
				mysqli_close($mysqli);
				$status="0";
			}
		}
return($status);
}

function deleteadmin($aid) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		@session_start();
		$admlvl=$_SESSION[$secretkey."admlvl"];
		$admid=$_SESSION[$secretkey."admid"];
			if(trim($aid)=="") {
				$status="2";
			} else {
				if($admlvl<>"0" || $admid=="") {
					$status="1";
				} else {
					$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
						$mysqli->query("DELETE FROM admins WHERE id='".$aid."'");
					mysqli_close($mysqli);
					$status="0";
				}
			}
return($status);
}

function enableadmin($aid) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
			$mysqli->query("UPDATE admins SET enabled='1' WHERE id='".$aid."'");
		mysqli_close($mysqli);
		$status="0";
return($status);
}

function disableadmin($aid) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
			$mysqli->query("UPDATE admins SET enabled='0' WHERE id='".$aid."'");
		mysqli_close($mysqli);
		$status="0";
return($status);
}

function idtoadmin($aid) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		if($aid=="0" || $aid=="") {
			$status="";
		} else {
			$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
				$a_sql=$mysqli->query("SELECT user FROM admins WHERE id='".$aid."'");
				$a_res=$a_sql->fetch_array();
					if($a_res["user"]<>"") {
						$status=$a_res["user"];	
					} else {
						$status="";
					}
			mysqli_close($mysqli);
		}
return($status);
}

function admintoid($adm) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		if($adm=="") {
			$status="";
		} else {
			$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
				$a_sql=$mysqli->query("SELECT id FROM admins WHERE user='".$adm."'");
				$a_res=$a_sql->fetch_array();
					if($a_res["id"]<>"") {
						$status=$a_res["id"];	
					} else {
						$status="";
					}
			mysqli_close($mysqli);
		}
return($status);
}

//
// csp server functions
//
function checkcspconn() {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
			$setsql=$mysqli->query("SELECT cspsrv_ip,cspsrv_port,cspsrv_user,cspsrv_pass,cspsrv_protocol FROM settings WHERE id='1'");
			$setdata=$setsql->fetch_array();
		mysqli_close($mysqli);
			if(@simplexml_load_file(valuetohttpprotocol($setdata["cspsrv_protocol"])."://".$setdata["cspsrv_user"].":".$setdata["cspsrv_pass"]."@".$setdata["cspsrv_ip"].":".$setdata["cspsrv_port"]."/xmlHandler?command=proxy-status")) {
				$status="1";
			} else {
				$status="0";
			}
return($status);
}

function cspgetuserlist() {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
	$i=0;
	$proxyusers="proxy-users";
		$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
			$setsql=$mysqli->query("SELECT cspsrv_ip,cspsrv_port,cspsrv_user,cspsrv_pass,cspsrv_protocol FROM settings WHERE id='1'");
			$setdata=$setsql->fetch_array();
		mysqli_close($mysqli);
			$xmldata=simplexml_load_file(valuetohttpprotocol($setdata["cspsrv_protocol"])."://".$setdata["cspsrv_user"].":".$setdata["cspsrv_pass"]."@".$setdata["cspsrv_ip"].":".$setdata["cspsrv_port"]."/xmlHandler?command=proxy-users");
				foreach($xmldata->$proxyusers->user as $xmlusr) {
					$y=0;
					$usrstate="0";
					foreach($xmldata->$proxyusers->user[$i]->session as $active) {
						if((string)$xmldata->$proxyusers->user[$i]->session[$y]->attributes()->active=="true") {
							$usrstate="1";
						}
						$y++;
					}
					$cspuserlist[(string)$xmldata->$proxyusers->user[$i]->attributes()->name]=$usrstate;
				$i++;
				}
return($cspuserlist);
}

function cspkickuser($user) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
			$setsql=$mysqli->query("SELECT cspsrv_ip,cspsrv_port,cspsrv_user,cspsrv_pass,cspsrv_protocol FROM settings WHERE id='1'");
			$setdata=$setsql->fetch_array();
		mysqli_close($mysqli);
			$url=valuetohttpprotocol($setdata["cspsrv_protocol"])."://".$setdata["cspsrv_user"].":".$setdata["cspsrv_pass"]."@".$setdata["cspsrv_ip"].":".$setdata["cspsrv_port"]."/xmlHandler?command=kick-user&name=".$user;
			file($url);
return("1");
}

function cspupdateusers() {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
	$cmdresult="cmd-result";
		$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
			$setsql=$mysqli->query("SELECT cspsrv_ip,cspsrv_port,cspsrv_user,cspsrv_pass,cspsrv_protocol FROM settings WHERE id='1'");
			$setdata=$setsql->fetch_array();
		mysqli_close($mysqli);
			$url=simplexml_load_file(valuetohttpprotocol($setdata["cspsrv_protocol"])."://".$setdata["cspsrv_user"].":".$setdata["cspsrv_pass"]."@".$setdata["cspsrv_ip"].":".$setdata["cspsrv_port"]."/xmlHandler?command=update-users");
			if((string)$url->$cmdresult->attributes()->success=="true") {
				$status="1";
			} else {
				$status="0";
			}
return($status);
}

function cspsendosd($user,$message) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
	$cmdresult="cmd-result";
		$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
			$setsql=$mysqli->query("SELECT cspsrv_ip,cspsrv_port,cspsrv_user,cspsrv_pass,cspsrv_protocol FROM settings WHERE id='1'");
			$setdata=$setsql->fetch_array();
		mysqli_close($mysqli);
			$url=simplexml_load_file(valuetohttpprotocol($setdata["cspsrv_protocol"])."://".$setdata["cspsrv_user"].":".$setdata["cspsrv_pass"]."@".$setdata["cspsrv_ip"].":".$setdata["cspsrv_port"]."/xmlHandler?command=osd-message&name=".$user."&text=".urlencode($message));
			if((string)$url->$cmdresult->attributes()->success=="true") {
				$status="1";
			} elseif((string)$url->$cmdresult->attributes()->success=="false") {
				$status="2";
			} else {
				$status="0";
			}
return($status);
}

function cspsendosdtoall($message) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
	$cmdresult="cmd-result";
		$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
			$setsql=$mysqli->query("SELECT cspsrv_ip,cspsrv_port,cspsrv_user,cspsrv_pass,cspsrv_protocol FROM settings WHERE id='1'");
			$setdata=$setsql->fetch_array();
		mysqli_close($mysqli);
			$url=simplexml_load_file(valuetohttpprotocol($setdata["cspsrv_protocol"])."://".$setdata["cspsrv_user"].":".$setdata["cspsrv_pass"]."@".$setdata["cspsrv_ip"].":".$setdata["cspsrv_port"]."/xmlHandler?command=osd-message&name=all&text=".urlencode($message));
			if((string)$url->$cmdresult->attributes()->success=="true") {
				$status="1;".(string)$url->$cmdresult->attributes()->data;
			} elseif((string)$url->$cmdresult->attributes()->success=="false") {
				$status="2;0";
			} else {
				$status="0;0";
			}
return($status);
}

function cspgetuserinfo($user) {
	// Return structure
	// loginfailures;sessions;host;id;count;profile;clientid;protocol;context;connected;duration;ecmcount;emmcount;pendingcount;keepalivecount;lasttransaction;lastzap;idletime;flags;avgecminterval;session-id;session-cdata;session-name
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
	$i=0;
	$proxyusers="proxy-users";
	$loginfailures="login-failures";
	$clientid="client-id";
	$ecmcount="ecm-count";
	$emmcount="emm-count";
	$pendingcount="pending-count";
	$lastzap="last-zap";
	$idletime="idle-time";
	$keepalivecount="keepalive-count";
	$avgecminterval="avg-ecm-interval";
	$lasttransaction="last-transaction";
	$actsess=0;
	$actusr="0";
		$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
			$setsql=$mysqli->query("SELECT cspsrv_ip,cspsrv_port,cspsrv_user,cspsrv_pass,cspsrv_protocol FROM settings WHERE id='1'");
			$setdata=$setsql->fetch_array();
		mysqli_close($mysqli);
			$xmldata=simplexml_load_file(valuetohttpprotocol($setdata["cspsrv_protocol"])."://".$setdata["cspsrv_user"].":".$setdata["cspsrv_pass"]."@".$setdata["cspsrv_ip"].":".$setdata["cspsrv_port"]."/xmlHandler?command=proxy-users&name=".$user);
				foreach($xmldata->$proxyusers->user as $xmlusr) {
					$y=0;
					foreach($xmldata->$proxyusers->user[$i]->session as $active) {
						if((string)$xmldata->$proxyusers->user[$i]->session[$y]->attributes()->active=="true") {
							$actsess=$y;
							$actusr="1";
						}
						$y++;
					}
				}
			if($actusr=="1") {
				$usrsession=@(string)$xmldata->$proxyusers->user[$i]->session[$actsess]->service->attributes()->id.";".@(string)$xmldata->$proxyusers->user[$i]->session[$actsess]->service->attributes()->cdata.";".@(string)$xmldata->$proxyusers->user[$i]->session[$actsess]->service->attributes()->name;
			} else {
				$usrsession=";";
			}
			$status=(string)$xmldata->$proxyusers->attributes()->$loginfailures.";".(string)$xmldata->$proxyusers->user[$i]->attributes()->sessions.";".(string)$xmldata->$proxyusers->user[$i]->session[$actsess]->attributes()->host.";".(string)$xmldata->$proxyusers->user[$i]->session[$actsess]->attributes()->id.";".(string)$xmldata->$proxyusers->user[$i]->session[$actsess]->attributes()->count.";".(string)$xmldata->$proxyusers->user[$i]->session[$actsess]->attributes()->profile.";".(string)$xmldata->$proxyusers->user[$i]->session[$actsess]->attributes()->$clientid.";".(string)$xmldata->$proxyusers->user[$i]->session[$actsess]->attributes()->protocol.";".(string)$xmldata->$proxyusers->user[$i]->session[$actsess]->attributes()->context.";".date("Y-m-d H:i:s", strtotime((string)$xmldata->$proxyusers->user[$i]->session[$actsess]->attributes()->connected)).";".(string)$xmldata->$proxyusers->user[$i]->session[$actsess]->attributes()->duration.";".(string)$xmldata->$proxyusers->user[$i]->session[$actsess]->attributes()->$ecmcount.";".(string)$xmldata->$proxyusers->user[$i]->session[$actsess]->attributes()->$emmcount.";".(string)$xmldata->$proxyusers->user[$i]->session[$actsess]->attributes()->$pendingcount.";".(string)$xmldata->$proxyusers->user[$i]->session[$actsess]->attributes()->$keepalivecount.";".(string)$xmldata->$proxyusers->user[$i]->session[$actsess]->attributes()->$lasttransaction.";".(string)$xmldata->$proxyusers->user[$i]->session[$actsess]->attributes()->$lastzap.";".(string)$xmldata->$proxyusers->user[$i]->session[$actsess]->attributes()->$idletime.";".(string)$xmldata->$proxyusers->user[$i]->session[$actsess]->attributes()->flags.";".(string)$xmldata->$proxyusers->user[$i]->session[$actsess]->attributes()->$avgecminterval.";".$usrsession;
return($status);
}

function cspgetuseripinfo($user) {
	// Return structure
	// ip;hostname;country;region;city;zipcode;timezone
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
	$i=0;
	$proxyusers="proxy-users";
	$actsess=0;
	$actusr="0";
		$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
			$setsql=$mysqli->query("SELECT cspsrv_ip,cspsrv_port,cspsrv_user,cspsrv_pass,cspsrv_protocol FROM settings WHERE id='1'");
			$setdata=$setsql->fetch_array();
		mysqli_close($mysqli);
			$xmldata=simplexml_load_file(valuetohttpprotocol($setdata["cspsrv_protocol"])."://".$setdata["cspsrv_user"].":".$setdata["cspsrv_pass"]."@".$setdata["cspsrv_ip"].":".$setdata["cspsrv_port"]."/xmlHandler?command=proxy-users&name=".$user);
				foreach($xmldata->$proxyusers->user as $xmlusr) {
					$y=0;
					foreach($xmldata->$proxyusers->user[$i]->session as $active) {
						if((string)$xmldata->$proxyusers->user[$i]->session[$y]->attributes()->active=="true") {
							$actsess=$y;
							$actusr="1";
						}
						$y++;
					}
				}
			$usrip=(string)$xmldata->$proxyusers->user[$i]->session[$actsess]->attributes()->host;
			$data=json_decode(file_get_contents("http://freegeoip.net/json/".$usrip),true);
				if(isset($data["ip"])) {
					$ip_ip=$data["ip"];
					$ip_hostname=gethostbyaddr($data["ip"]);
				} else {
					$ip_ip="";
					$ip_hostname="";
				}
				if(isset($data["country_name"])) {
					$ip_country=$data["country_name"]." (".$data["country_code"].")";
				} else {
					$ip_country="";
				}
				if(isset($data["region_name"])) {
					$ip_region=$data["region_name"]." (".$data["region_code"].")";
				} else {
					$ip_region="";
				}
				if(isset($data["city"])) {
					$ip_city=$data["city"];
				} else {
					$ip_city="";
				}
				if(isset($data["zip_code"])) {
					$ip_zipcode=$data["zip_code"];
				} else {
					$ip_zipcode="";
				}
				if(isset($data["time_zone"])) {
					$ip_timezone=$data["time_zone"];
				} else {
					$ip_timezone="";
				}
		$status=$ip_ip.";".$ip_hostname.";".$ip_country.";".$ip_region.";".$ip_city.";".$ip_zipcode.";".$ip_timezone;
return($status);
}

function cspgetsrvinfo() {
	// Return structure
	// name;state;version;started;uptime;connectors;sessions;esttotalcapacity;ecmtotal;ecmforwards;ecmcachehits;ecmdenied;ecmfiltered;ecmfailures;emmtotal;javavm;os;heap;tc;fd
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
	$proxystatus="proxy-status";
	$jvm="jvm";
	$activesessions="active-sessions";
	$ecmcount="ecm-count";
	$ecmrate="ecm-rate";
	$ecmforwards="ecm-forwards";
	$ecmcachehits="ecm-cache-hits";
	$ecmdenied="ecm-denied";
	$ecmfiltered="ecm-filtered";
	$ecmfailures="ecm-failures";
	$emmcount="emm-count";
	$heaptotal="heap-total";
	$heapfree="heap-free";
	$filedescmax="filedesc-max";
	$filedescopen="filedesc-open";
		$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
			$setsql=$mysqli->query("SELECT cspsrv_ip,cspsrv_port,cspsrv_user,cspsrv_pass,cspsrv_protocol FROM settings WHERE id='1'");
			$setdata=$setsql->fetch_array();
		mysqli_close($mysqli);
			$xmldata=simplexml_load_file(valuetohttpprotocol($setdata["cspsrv_protocol"])."://".$setdata["cspsrv_user"].":".$setdata["cspsrv_pass"]."@".$setdata["cspsrv_ip"].":".$setdata["cspsrv_port"]."/xmlHandler?command=proxy-status");
		$heapvalue=(string)$xmldata->$proxystatus->$jvm->attributes()->$heaptotal-(string)$xmldata->$proxystatus->$jvm->attributes()->$heapfree;
			$status=$xmldata->$proxystatus->attributes()->name.";".$xmldata->$proxystatus->attributes()->state.";".$xmldata->$proxystatus->attributes()->version." (build ".str_replace("r","",$xmldata->$proxystatus->attributes()->build).");".date("Y-m-d H:i:s", strtotime($xmldata->$proxystatus->attributes()->started)).";".$xmldata->$proxystatus->attributes()->duration.";".$xmldata->$proxystatus->attributes()->connectors.";".$xmldata->$proxystatus->attributes()->sessions." (active: ".$xmldata->$proxystatus->attributes()->$activesessions.");".$xmldata->$proxystatus->attributes()->capacity." (ECM->CW transactions per CW-validity-period);".$xmldata->$proxystatus->attributes()->$ecmcount." (average rate: ".$xmldata->$proxystatus->attributes()->$ecmrate."/s);".$xmldata->$proxystatus->attributes()->$ecmforwards." (".percent($xmldata->$proxystatus->attributes()->$ecmforwards,$xmldata->$proxystatus->attributes()->$ecmcount)."%);".$xmldata->$proxystatus->attributes()->$ecmcachehits." (".percent($xmldata->$proxystatus->attributes()->$ecmcachehits,$xmldata->$proxystatus->attributes()->$ecmcount)."%);".$xmldata->$proxystatus->attributes()->$ecmdenied." (".percent($xmldata->$proxystatus->attributes()->$ecmdenied,$xmldata->$proxystatus->attributes()->$ecmcount)."%);".$xmldata->$proxystatus->attributes()->$ecmfiltered." (".percent($xmldata->$proxystatus->attributes()->$ecmfiltered,$xmldata->$proxystatus->attributes()->$ecmcount)."%);".$xmldata->$proxystatus->attributes()->$ecmfailures.";".$xmldata->$proxystatus->attributes()->$emmcount.";".$xmldata->$proxystatus->$jvm->attributes()->name.$xmldata->$proxystatus->$jvm->attributes()->version.";".$xmldata->$proxystatus->$jvm->attributes()->os.";".$heapvalue."k/".$xmldata->$proxystatus->$jvm->attributes()->$heaptotal."k;".$xmldata->$proxystatus->$jvm->attributes()->threads.";".$xmldata->$proxystatus->$jvm->attributes()->$filedescopen."/".$xmldata->$proxystatus->$jvm->attributes()->$filedescmax;
return($status);
}

function cspshutdown() {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
	$cmdresult="cmd-result";
		$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
			$setsql=$mysqli->query("SELECT cspsrv_ip,cspsrv_port,cspsrv_user,cspsrv_pass,cspsrv_protocol FROM settings WHERE id='1'");
			$setdata=$setsql->fetch_array();
		mysqli_close($mysqli);
			$url=simplexml_load_file(valuetohttpprotocol($setdata["cspsrv_protocol"])."://".$setdata["cspsrv_user"].":".$setdata["cspsrv_pass"]."@".$setdata["cspsrv_ip"].":".$setdata["cspsrv_port"]."/xmlHandler?command=shutdown");
			if((string)$url->$cmdresult->attributes()->success=="true") {
				$status="1";
			} else {
				$status="0";
			}
return($status);
}

//
// dashboard functions
//
function dashcheckcspconn($value) {
	if($value=="0") {
		$status="<span class=\"label label-important pull-right\">!</span>";
	} else {
		$status="";
	}
return($status);
}

function countusers($type) {
	// Type codes
	// 0 = total
	// 1 = enabled
	// 2 = disabled
	// 3 = expired
	// 4 = admins
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
			if($type=="0") {
				if($_SESSION[$secretkey."admlvl"]=="0" ||  $_SESSION[$secretkey."admlvl"]=="1") {
					$sql=$mysqli->query("SELECT id FROM users");
				} elseif($_SESSION[$secretkey."admlvl"]=="2" && $_SESSION[$secretkey."admgrp"]<>"0") {
					$sql=$mysqli->query("SELECT id FROM users WHERE usrgroup='".$_SESSION[$secretkey."admgrp"]."'");
				} else {
					$sql="";
				}
			}
			if($type=="1") {
				if($_SESSION[$secretkey."admlvl"]=="0" ||  $_SESSION[$secretkey."admlvl"]=="1") {
					$sql=$mysqli->query("SELECT id FROM users WHERE enabled='1' OR enabled=''");
				} elseif($_SESSION[$secretkey."admlvl"]=="2" && $_SESSION[$secretkey."admgrp"]<>"0") {
					$sql=$mysqli->query("SELECT id FROM users WHERE (enabled='1' OR enabled='') AND usrgroup='".$_SESSION[$secretkey."admgrp"]."'");
				} else {
					$sql="";
				}
			}
			if($type=="2") {
				if($_SESSION[$secretkey."admlvl"]=="0" ||  $_SESSION[$secretkey."admlvl"]=="1") {
					$sql=$mysqli->query("SELECT id FROM users WHERE enabled='0'");
				} elseif($_SESSION[$secretkey."admlvl"]=="2" && $_SESSION[$secretkey."admgrp"]<>"0") {
					$sql=$mysqli->query("SELECT id FROM users WHERE enabled='0' AND usrgroup='".$_SESSION[$secretkey."admgrp"]."'");
				} else {
					$sql="";
				}
			}
			if($type=="3") {
				if($_SESSION[$secretkey."admlvl"]=="0" ||  $_SESSION[$secretkey."admlvl"]=="1") {
					$sql=$mysqli->query("SELECT id FROM users WHERE expiredate<='".date("Y-m-d")."' AND expiredate<>'0000-00-00'");
				} elseif($_SESSION[$secretkey."admlvl"]=="2" && $_SESSION[$secretkey."admgrp"]<>"0") {
					$sql=$mysqli->query("SELECT id FROM users WHERE expiredate<='".date("Y-m-d")."' AND expiredate<>'0000-00-00' AND usrgroup='".$_SESSION[$secretkey."admgrp"]."'");
				} else {
					$sql="";
				}
			}
			if($type=="4") {
				if($_SESSION[$secretkey."admlvl"]=="0" ||  $_SESSION[$secretkey."admlvl"]=="1") {
					$sql=$mysqli->query("SELECT id FROM users WHERE admin='1'");
				} elseif($_SESSION[$secretkey."admlvl"]=="2" && $_SESSION[$secretkey."admgrp"]<>"0") {
					$sql=$mysqli->query("SELECT id FROM users WHERE admin='1' AND usrgroup='".$_SESSION[$secretkey."admgrp"]."'");
				} else {
					$sql="";
				}
			}
		$count=$sql->num_rows;
		mysqli_close($mysqli);
return($count);
}

//
// group functions
//
function addgroup($name,$comment) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
			$mysqli->query("INSERT INTO groups (name,comment,enabled) VALUES ('".trim($name)."','".trim($comment)."','1')");
			$status="0";
		mysqli_close($mysqli);
return($status);
}

function editgroup($gid,$name,$comment) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		if(trim($name)=="") {
			$status="1";
		} else {
			$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
			$sqli=$mysqli->query("SELECT * FROM groups WHERE id='".trim($gid)."'");
			$res=$sqli->fetch_array();
				if($res["name"]==$name) {
					$mysqli->query("UPDATE groups SET comment='".trim($comment)."' WHERE id='".$gid."'");
					mysqli_close($mysqli);
					$status="0";
				} else {
					$sqln=$mysqli->query("SELECT * FROM groups WHERE name='".trim($name)."'");
					$rowcheck=$sqln->num_rows;
						if($rowcheck==1) {
							mysqli_close($mysqli);
							$status="2";
						} else {
							$mysqli->query("UPDATE groups SET name='".trim($name)."',comment='".trim($comment)."' WHERE id='".$gid."'");
							mysqli_close($mysqli);
							$status="0";
						}
				}
		}
return($status);
}

function deletegroup($gid) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		@session_start();
		$admlvl=$_SESSION[$secretkey."admlvl"];
		$admid=$_SESSION[$secretkey."admid"];
			if(trim($gid)=="") {
				$status="2";
			} else {
				if($admlvl<>"0" || $admid=="") {
					$status="1";
				} else {
					$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
						$mysqli->query("DELETE FROM groups WHERE id='".$gid."'");
						$mysqli->query("DELETE FROM users WHERE usrgroup='".$gid."'");
						$mysqli->query("UPDATE admins SET enabled='0',ugroup='0' WHERE ugroup='".$gid."' AND admlvl='2'");
						$mysqli->query("UPDATE admins SET ugroup='0' WHERE ugroup='".$gid."' AND admlvl<>'2'");
					mysqli_close($mysqli);
					$status="0";
				}
			}
return($status);
}

function enablegroup($gid) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
			$mysqli->query("UPDATE groups SET enabled='1' WHERE id='".$gid."'");
		mysqli_close($mysqli);
		$status="0";
return($status);
}

function disablegroup($gid) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
			$mysqli->query("UPDATE groups SET enabled='0' WHERE id='".$gid."'");
		mysqli_close($mysqli);
		$status="0";
return($status);
}

function usersingroup($gid) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		$mysqlig=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
			$cgsql=$mysqlig->query("SELECT id FROM users WHERE usrgroup='".trim($gid)."'");
			$rows=$cgsql->num_rows;
		mysqli_close($mysqlig);
return($rows);
}

function idtogrp($gid) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		if($gid=="0" || $gid=="") {
			$status="";
		} else {
			$mysqlig=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
				$g_sql=$mysqlig->query("SELECT name FROM groups WHERE id='".$gid."'");
				$g_res=$g_sql->fetch_array();
					if($g_res["name"]<>"") {
						$status=$g_res["name"];	
					} else {
						$status="";
					}
			mysqli_close($mysqlig);
		}
return($status);
}

function grptoid($grp) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		if($grp=="") {
			$status="";
		} else {
			$mysqlig=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
				$g_sql=$mysqlig->query("SELECT id FROM groups WHERE name='".$grp."'");
				$g_res=$g_sql->fetch_array();
					if($g_res["id"]<>"") {
						$status=$g_res["id"];	
					} else {
						$status="";
					}
			mysqli_close($mysqlig);
		}
return($status);
}

function enabledgroups() {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		$enabledgroups=array();
		array_push($enabledgroups,"");
		$mysqlig=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
			$gsql=$mysqlig->query("SELECT id FROM groups WHERE enabled='1'");
			while($gres=$gsql->fetch_array()) {
				array_push($enabledgroups,$gres["id"]);	
			}
		mysqli_close($mysqlig);
return($enabledgroups);
}

//
// profile functions
//
function addprofile($name,$cspvalue,$comment) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
			$mysqli->query("INSERT INTO profiles (name,cspvalue,comment) VALUES ('".trim($name)."','".trim($cspvalue)."','".trim($comment)."')");
			$status="0";
		mysqli_close($mysqli);
return($status);
}

function editprofile($pid,$name,$cspvalue,$comment) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		if(trim($name)=="") {
			$status="1";
		} else {
			$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
			$sqli=$mysqli->query("SELECT * FROM profiles WHERE id='".trim($pid)."'");
			$res=$sqli->fetch_array();
				if($res["name"]==$name) {
					$mysqli->query("UPDATE profiles SET cspvalue='".trim($cspvalue)."',comment='".trim($comment)."' WHERE id='".$pid."'");
					mysqli_close($mysqli);
					$status="0";
				} else {
					$sqlg=$mysqli->query("SELECT * FROM profiles WHERE name='".trim($name)."'");
					$rowcheck=$sqlg->num_rows;
						if($rowcheck==1) {
							mysqli_close($mysqli);
							$status="2";
						} else {
							$mysqli->query("UPDATE profiles SET name='".trim($name)."',cspvalue='".trim($cspvalue)."',comment='".trim($comment)."' WHERE id='".$pid."'");
							mysqli_close($mysqli);
							$status="0";
						}
				}
		}
return($status);
}

function deleteprofile($pid) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		@session_start();
		$admlvl=$_SESSION[$secretkey."admlvl"];
		$admid=$_SESSION[$secretkey."admid"];
			if(trim($pid)=="") {
				$status="2";
			} else {
				if($admlvl<>"0" || $admid=="") {
					$status="1";
				} else {
					$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
						cleanprofile($pid);
						$sql=$mysqli->query("DELETE FROM profiles WHERE id='".$pid."'");
					mysqli_close($mysqli);
					$status="0";
				}
		}
return($status);
}

function cleanprofile($pid) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
			$sql=$mysqli->query("SELECT id,profiles FROM users");
				while($udata=$sql->fetch_array()) {
					if(is_array(unserialize($udata["profiles"])) && in_array($pid,unserialize($udata["profiles"]))) {
						$cleanprof=array_merge(array_diff(unserialize($udata["profiles"]),array($pid)));
							$mysqli->query("UPDATE users SET profiles='".serialize($cleanprof)."' WHERE id='".$udata["id"]."'");
						$cleanprof="";
					}
				}
		mysqli_close($mysqli);
}

function getprofiles() {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
        	$psql=$mysqli->query("SELECT id,cspvalue FROM profiles");
				$profiles=array();
				while($pdata=$psql->fetch_array()) {
					$profiles[$pdata["id"]]=$pdata["cspvalue"];
				}
		mysqli_close($mysqli);
return($profiles);
}

//
// security functions
//
function logactivity($act,$aid,$alvl,$uid,$data) {
	// activity codes
	// 1 = add user
	// 2 = edit user
	// 3 = del user
	// 4 = enable user
	// 5 = disable user
	if($alvl<>"0") {
		if(file_exists("config.php")) {
			require("config.php");
		} else {
			require("../config.php");
		}
			$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
				$mysqli->query("INSERT INTO log_activity (activity,adminid,userid,data) VALUES ('".trim($act)."','".trim($aid)."','".trim($uid)."','".trim($data)."')");
			mysqli_close($mysqli);
	}
}

//
// settings functions
//
function updatesettings($servername,$timeout,$rndstring,$rndstringlength,$loglogins,$logactivity,$cleanlogin,$genxmlkey,$genxmllogreq,$genxmlusrgrp,$genxmldateformat,$genxmlintstrexp,$def_autoload,$def_ipmask,$def_profiles,$def_maxconn,$def_admin,$def_enabled,$def_mapexc,$def_debug,$def_custcspval,$def_ecmrate,$fetchcsp,$cspsrv_ip,$cspsrv_port,$cspsrv_user,$cspsrv_pass,$cspsrv_protocol,$comptables,$extrausrtbl,$notstartusrorder,$expusrorder,$soonexpusrorder,$autoupdcheck,$usrorderby,$usrorder,$email_host,$email_port,$email_secure,$email_auth,$email_authuser,$email_authpass,$email_fromname,$email_fromaddr) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
			if($def_profiles=="") {
				$def_profiles="N;";
			} else {
				$def_profiles=serialize($def_profiles);
			}
			$mysqli->query("UPDATE settings SET servername='".stripslashes(trim($servername))."',timeout='".stripslashes(trim($timeout))."',rndstring='".stripslashes(trim($rndstring))."',rndstringlength='".stripslashes(trim($rndstringlength))."',loglogins='".stripslashes(trim($loglogins))."',logactivity='".stripslashes(trim($logactivity))."',cleanlogin='".stripslashes(trim($cleanlogin))."',genxmlkey='".stripslashes(trim($genxmlkey))."',genxmllogreq='".stripslashes(trim($genxmllogreq))."',genxmlusrgrp='".stripslashes(trim($genxmlusrgrp))."',genxmldateformat='".stripslashes(trim($genxmldateformat))."',genxmlintstrexp='".stripslashes(trim($genxmlintstrexp))."',def_autoload='".stripslashes(trim($def_autoload))."',def_ipmask='".stripslashes(trim($def_ipmask))."',def_profiles='".$def_profiles."',def_maxconn='".stripslashes(trim($def_maxconn))."',def_admin='".stripslashes(trim($def_admin))."',def_enabled='".stripslashes(trim($def_enabled))."',def_mapexc='".stripslashes(trim($def_mapexc))."',def_debug='".stripslashes(trim($def_debug))."',def_custcspval='".$mysqli->real_escape_string(trim($def_custcspval))."',def_ecmrate='".stripslashes(trim($def_ecmrate))."',fetchcsp='".stripslashes(trim($fetchcsp))."',cspsrv_ip='".stripslashes(trim($cspsrv_ip))."',cspsrv_port='".stripslashes(trim($cspsrv_port))."',cspsrv_user='".stripslashes(trim($cspsrv_user))."',cspsrv_pass='".stripslashes(trim($cspsrv_pass))."',cspsrv_protocol='".stripslashes(trim($cspsrv_protocol))."',comptables='".stripslashes(trim($comptables))."',extrausrtbl='".stripslashes(trim($extrausrtbl))."',notstartusrorder='".stripslashes(trim($notstartusrorder))."',expusrorder='".stripslashes(trim($expusrorder))."',soonexpusrorder='".stripslashes(trim($soonexpusrorder))."',autoupdcheck='".stripslashes(trim($autoupdcheck))."',usrorderby='".stripslashes(trim($usrorderby))."',usrorder='".stripslashes(trim($usrorder))."',email_host='".stripslashes(trim($email_host))."',email_port='".stripslashes(trim($email_port))."',email_secure='".stripslashes(trim($email_secure))."',email_auth='".stripslashes(trim($email_auth))."',email_authuser='".stripslashes(trim($email_authuser))."',email_authpass='".stripslashes(trim($email_authpass))."',email_fromname='".stripslashes(trim($email_fromname))."',email_fromaddr='".stripslashes(trim($email_fromaddr))."' WHERE id='1'");
		mysqli_close($mysqli);
			@session_start();
				$_SESSION[$secretkey."timeout"]=$timeout;
				$_SESSION[$secretkey."servername"]=$servername;
				$_SESSION[$secretkey."fetchcsp"]=$fetchcsp;
		$status="0";
return($status);
}

function checksetting($setting) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
			$sql=$mysqli->query("SELECT ".$setting." FROM settings WHERE id='1'");
			$rdata=$sql->fetch_array();
		mysqli_close($mysqli);
return($rdata[$setting]);
}

function checkemailsettings() {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
			$sql=$mysqli->query("SELECT email_host,email_port,email_fromaddr FROM settings WHERE id='1'");
			$rdata=$sql->fetch_array();
		mysqli_close($mysqli);
			if($rdata["email_host"]=="" || $rdata["email_port"]=="" || $rdata["email_fromaddr"]=="") {
				$status="1";
			} else {
				$status="0";
			}
return($status);
}

//
// tool functions
//
function includetool($toolid) {
	$tools=array(
		"101"=>"tools/enallusr.php",
		"102"=>"tools/disallusr.php",
		"103"=>"tools/deldisusr.php",
		"104"=>"tools/delexpusr.php",
		"105"=>"tools/emptyudb.php",
		"201"=>"tools/impusrcspxml.php",
		"202"=>"tools/impusrcsv.php",
		"203"=>"tools/expusrcspxml.php",
		"204"=>"tools/expusrcsv.php",
		"205"=>"tools/impcspprof.php",
		"301"=>"tools/cspupdusers.php",
		"302"=>"tools/cspsendosdtoall.php",
		"303"=>"tools/cspshutdown.php",
		"401"=>"tools/logadminlogin.php",
		"402"=>"tools/loggenxmlreq.php",
		"403"=>"tools/logactivity.php",
		"501"=>"tools/emptygdb.php",
		"502"=>"tools/emptypdb.php",
		"503"=>"tools/sendemailtoall.php",
		"601"=>"tools/dbmaint.php",
		"901"=>"tools/updcheck.php"
	);
return($tools[$toolid]);
}

function enallusr($uid,$passwd) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
			$sql=$mysqli->query("SELECT user FROM admins WHERE id='".$uid."' AND pass='".hash("sha256",$passwd.$secretkey)."'");
				$rowcheck=$sql->num_rows;
					if($rowcheck==1) {
						$mysqli->query("UPDATE users SET enabled='1' WHERE enabled='0' OR enabled=''");
						$status="1";
					} elseif($rowcheck==0) {
						$status="2";
					} else {
						$status="0";
					}
		mysqli_close($mysqli);
return($status);
}

function disallusr($uid,$passwd) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
			$sql=$mysqli->query("SELECT user FROM admins WHERE id='".$uid."' AND pass='".hash("sha256",$passwd.$secretkey)."'");
				$rowcheck=$sql->num_rows;
					if($rowcheck==1) {
						$mysqli->query("UPDATE users SET enabled='0' WHERE enabled='1' OR enabled=''");
						$status="1";
					} elseif($rowcheck==0) {
						$status="2";
					} else {
						$status="0";
					}
		mysqli_close($mysqli);
return($status);
}

function deldisusr($uid,$passwd) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
			$sql=$mysqli->query("SELECT user FROM admins WHERE id='".$uid."' AND pass='".hash("sha256",$passwd.$secretkey)."'");
				$rowcheck=$sql->num_rows;
					if($rowcheck==1) {
						$mysqli->query("DELETE FROM users WHERE enabled='0'");
						$status="1";
					} elseif($rowcheck==0) {
						$status="2";
					} else {
						$status="0";
					}
		mysqli_close($mysqli);
return($status);
}

function delexpusr($uid,$passwd,$expdate) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
			$sql=$mysqli->query("SELECT user FROM admins WHERE id='".$uid."' AND pass='".hash("sha256",$passwd.$secretkey)."'");
				$rowcheck=$sql->num_rows;
					if($rowcheck==1) {
						$mysqli->query("DELETE FROM users WHERE expiredate<>'0000-00-00' AND expiredate<'".$expdate."'");
						$status="1";
					} elseif($rowcheck==0) {
						$status="2";
					} else {
						$status="0";
					}
		mysqli_close($mysqli);
return($status);
}

function emptyudb($uid,$passwd) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
			$sql=$mysqli->query("SELECT user FROM admins WHERE id='".$uid."' AND pass='".hash("sha256",$passwd.$secretkey)."'");
				$rowcheck=$sql->num_rows;
					if($rowcheck==1) {
						$mysqli->query("TRUNCATE TABLE users");
						$status="1";
					} elseif($rowcheck==0) {
						$status="2";
					} else {
						$status="0";
					}
		mysqli_close($mysqli);
return($status);
}

function emptygdb($uid,$passwd) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
			$sql=$mysqli->query("SELECT user FROM admins WHERE id='".$uid."' AND pass='".hash("sha256",$passwd.$secretkey)."'");
				$rowcheck=$sql->num_rows;
					if($rowcheck==1) {
						$mysqli->query("UPDATE users SET usrgroup=''");
						$mysqli->query("TRUNCATE TABLE groups");
						$status="1";
					} elseif($rowcheck==0) {
						$status="2";
					} else {
						$status="0";
					}
		mysqli_close($mysqli);
return($status);
}

function emptypdb($uid,$passwd) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
			$sql=$mysqli->query("SELECT user FROM admins WHERE id='".$uid."' AND pass='".hash("sha256",$passwd.$secretkey)."'");
				$rowcheck=$sql->num_rows;
					if($rowcheck==1) {
						$psql=$mysqli->query("SELECT id FROM profiles");
							while($pdata=$psql->fetch_array()) {
								cleanprofile($pdata["id"]);
							}
						$mysqli->query("TRUNCATE TABLE profiles");
						$status="1";
					} elseif($rowcheck==0) {
						$status="2";
					} else {
						$status="0";
					}
		mysqli_close($mysqli);
return($status);
}

function clearlog($logdb) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
			$mysqli->query("TRUNCATE TABLE ".$logdb);
			$status="1";
		mysqli_close($mysqli);
return($status);
}

function impusrcspxml($cspxml,$usrgrp,$createprof) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		$cspxml=str_replace("<auth-config>","",$cspxml);
		$cspxml=str_replace("</auth-config>","",$cspxml);
		$cspxml=str_replace("<xml-user-manager ver=\"1.0\">","",$cspxml);
		$cspxml=str_replace("</user-manager>","",$cspxml);
		$cspxml=str_replace("<user-manager class=\"com.bowman.cardserv.SimpleUserManager\" allow-on-failure=\"true\" log-failures=\"true\">","",$cspxml);
		$cspxml=str_replace("<user-manager class=\"com.bowman.cardserv.SimpleUserManager\" allow-on-failure=\"false\" log-failures=\"false\">","",$cspxml);
		$cspxml=str_replace("<user-manager class=\"com.bowman.cardserv.SimpleUserManager\" allow-on-failure=\"true\" log-failures=\"false\">","",$cspxml);
		$cspxml=str_replace("<user-manager class=\"com.bowman.cardserv.SimpleUserManager\" allow-on-failure=\"false\" log-failures=\"true\">","",$cspxml);
		$cspxml=str_replace("<user-manager class=\"com.bowman.cardserv.SimpleUserManager\">","",$cspxml);	
		$cspxml=str_replace("display-name","displayname",$cspxml);
		$cspxml=str_replace("ip-mask","ipmask",$cspxml);
		$cspxml=str_replace("max-connections","maxconnections",$cspxml);
		$cspxml=str_replace("map-exclude","mapexclude",$cspxml);
		$cspxml=str_replace("email-address","emailaddress",$cspxml);
		$cspxml=str_replace("ecm-rate","ecmrate",$cspxml);
		$cspxml=str_replace("start-date","startdate",$cspxml);
		$cspxml=str_replace("expire-date","expiredate",$cspxml);
			$cspxmllines=preg_split("/\r\n|[\r\n]/",$cspxml);
				$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
					$i=0;
					$x=0;
					$y=0;
					$e=0;
					$p=0;
					$impusers="";
					$impexists="";
					$impprofiles="";
						foreach($cspxmllines as $i => $value) {
							libxml_use_internal_errors(true);
								$cspxmldata=simplexml_load_string(stripslashes($cspxmllines[$i]));
									if(!$cspxmldata) {
										$y++;
									} elseif($cspxmldata->attributes()->name=="" || $cspxmldata->attributes()->password=="") {
										$y++;
									} else {
										$xml_username=$cspxmldata->attributes()->name;
										$sql=$mysqli->query("SELECT id FROM users WHERE user='".$xml_username."'");
										$rowcheck=$sql->num_rows;
											if($rowcheck==0) {
												$xml_password=$cspxmldata->attributes()->password;
												$xml_displayname=$cspxmldata->attributes()->displayname;
												$xml_ipmask=$cspxmldata->attributes()->ipmask;
												$xml_profiles=$cspxmldata->attributes()->profiles;
												$xml_maxconn=$cspxmldata->attributes()->maxconnections;
												$xml_admin=truefalse($cspxmldata->attributes()->admin);
												$xml_enabled=truefalse($cspxmldata->attributes()->enabled);
												$xml_mapexclude=truefalse($cspxmldata->attributes()->mapexclude);
												$xml_debug=truefalse($cspxmldata->attributes()->debug);
												$xml_email=$cspxmldata->attributes()->emailaddress;
												$xml_ecmrate=$cspxmldata->attributes()->ecmrate;
												if($cspxmldata->attributes()->startdate<>"") {
													$xml_startdate=date("Y-m-d", strtotime($cspxmldata->attributes()->startdate));
												} else {
													$xml_startdate="";
												}
												if($cspxmldata->attributes()->expiredate<>"") {
													$xml_expiredate=date("Y-m-d", strtotime($cspxmldata->attributes()->expiredate));
												} else {
													$xml_expiredate="";
												}
												$xml_improfiles=explode(" ",$xml_profiles);
													foreach($xml_improfiles as $profvalue) {
														if(isset($createprof) && $createprof=="1" && isset($profvalue) && $profvalue<>"") {
															$pcsql=$mysqli->query("SELECT id FROM profiles WHERE cspvalue='".$profvalue."'");
																$prowcheck=$pcsql->num_rows;
																	if($prowcheck==0) {
																		addprofile($profvalue,$profvalue,"Auto created profile by user import");
																		$impprofiles=$impprofiles.$profvalue."<br>";
																		$p++;
																	}
														}
														$psql=$mysqli->query("SELECT id FROM profiles WHERE cspvalue='".$profvalue."'");
														$sql_prof=$psql->fetch_array();
														if($sql_prof["id"]<>"") {
															$convporf[]=$sql_prof["id"];
														}
													}
												if(!isset($convporf)) {
													$convporf="N;";
												} else {
													$convporf=serialize($convporf);
												}
												$mysqli->query("INSERT INTO users (user,password,displayname,ipmask,profiles,maxconn,admin,enabled,mapexclude,debug,comment,email,customvalues,ecmrate,startdate,expiredate,usrgroup,boxtype,macaddress,serialnumber,addedby,changed,changedby) VALUES ('".stripslashes(trim($xml_username))."','".stripslashes(trim($xml_password))."','".stripslashes(trim($xml_displayname))."','".stripslashes(trim($xml_ipmask))."','".$convporf."','".stripslashes(trim($xml_maxconn))."','".trim($xml_admin)."','".trim($xml_enabled)."','".trim($xml_mapexclude)."','".trim($xml_debug)."','','".stripslashes(trim($xml_email))."','','".stripslashes(trim($xml_ecmrate))."','".$xml_startdate."','".$xml_expiredate."','".$usrgrp."','','','','".$_SESSION[$secretkey."admid"]."','','')");
												unset($convporf);
												$sql="";
												$psql="";
												$impusers=$impusers.$xml_username."<br>";
												$x++;
											} else {
												$impexists=$impexists.$xml_username."<br>";
												$e++;
											}
									}
						}
				mysqli_close($mysqli);
		$status["usrimp"]=$x;
		$status["usrexi"]=$e;
		$status["profimp"]=$p;
		$status["implist"]=$impusers;
		$status["exilist"]=$impexists;
		$status["proflist"]=$impprofiles;
return($status);
}

function impusrcsv($csv,$creategrp,$createprof,$cmumcsvver) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
	$csv=preg_split("/\r\n|[\r\n]/",$csv);
		$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
			$i=0;
			$x=0;
			$y=0;
			$e=0;
			$p=0;
			$g=0;
			$impusers="";
			$impexists="";
			$impprofiles="";
			$impgroups="";
				foreach ($csv as $i => $value) {
					$csvuser=explode(";", $csv[$i]);
						if($csvuser[0]=="" || $csvuser[1]=="") {
							$y++;
						} else {
							$sql=$mysqli->query("SELECT id FROM users WHERE user='".$csvuser[0]."'");
							$urowcheck=$sql->num_rows;
								if($urowcheck==0) {
									$improf=explode(" ",$csvuser[4]);
										foreach($improf as $profvalue) {
											if(isset($createprof) && $createprof=="1" && isset($profvalue) && $profvalue<>"") {
												$pcsql=$mysqli->query("SELECT id FROM profiles WHERE cspvalue='".$profvalue."'");
													$prowcheck=$pcsql->num_rows;
														if($prowcheck==0) {
															addprofile($profvalue,$profvalue,"Auto created profile by user import");
															$impprofiles=$impprofiles.$profvalue."<br>";
															$p++;
														}
											}
											$psql=$mysqli->query("SELECT id FROM profiles WHERE cspvalue='".$profvalue."'");
											$pdata=$psql->fetch_array();
												if($pdata["id"]<>"") {
													$usrprof[]=$pdata["id"];
												}
										}
										if(!isset($usrprof)) {
											$usrprof="N;";
										} else {
											$usrprof=serialize($usrprof);
										}
									$csvchk="0";
										do {
											if(empty($csvuser[$csvchk])) {
												$csvuser[$csvchk]="";
											}
											$csvchk++;
										} while ($csvchk<"23");
									if($cmumcsvver=="3") {
										if(isset($_POST["creategrp"]) && $_POST["creategrp"]=="1" && !empty($csvuser[16]) && $csvuser[16]<>"") {
											$gsql=$mysqli->query("SELECT id FROM groups WHERE name='".$csvuser[16]."'");
											$growcheck=$gsql->num_rows;
												if($growcheck==0) {
													addgroup($csvuser[16],"Auto created group by user import");
													$impgroups=$impgroups.$csvuser[16]."<br>";
													$g++;
												}
										}
										if(!empty($csvuser[14]) && $csvuser[14]<>"") {
											$ustrdate=date("Y-m-d",strtotime($csvuser[14]));
										} else {
											$ustrdate="";
										}
										if(!empty($csvuser[15]) && $csvuser[15]<>"") {
											$uexpdate=date("Y-m-d",strtotime($csvuser[15]));
										} else {
											$uexpdate="";
										}
									} elseif($cmumcsvver=="2") {
										if(isset($_POST["creategrp"]) && $_POST["creategrp"]=="1" && !empty($csvuser[12]) && $csvuser[12]<>"") {
											$gsql=$mysqli->query("SELECT id FROM groups WHERE name='".$csvuser[12]."'");
											$growcheck=$gsql->num_rows;
												if($growcheck==0) {
													addgroup($csvuser[12],"Auto created group by user import");
													$impgroups=$impgroups.$csvuser[12]."<br>";
													$g++;
												}
										}
										if(!empty($csvuser[15]) && $csvuser[15]<>"") {
											$ustrdate=date("Y-m-d",strtotime($csvuser[15]));
										} else {
											$ustrdate="";
										}
										if(!empty($csvuser[16]) && $csvuser[16]<>"") {
											$uexpdate=date("Y-m-d",strtotime($csvuser[16]));
										} else {
											$uexpdate="";
										}
									}
									if($cmumcsvver=="3") {
										$mysqli->query("INSERT INTO users (user,password,displayname,ipmask,profiles,maxconn,admin,enabled,mapexclude,debug,comment,email,customvalues,ecmrate,startdate,expiredate,usrgroup,boxtype,macaddress,serialnumber,added,addedby,changed,changedby) VALUES ('".stripslashes(trim($csvuser[0]))."','".stripslashes(trim($csvuser[1]))."','".stripslashes(trim($csvuser[2]))."','".stripslashes(trim($csvuser[3]))."','".$usrprof."','".stripslashes(trim($csvuser[5]))."','".trim($csvuser[6])."','".trim($csvuser[7])."','".trim($csvuser[8])."','".trim($csvuser[9])."','".stripslashes(trim($csvuser[10]))."','".stripslashes(trim($csvuser[11]))."','".$mysqli->real_escape_string(trim($csvuser[12]))."','".stripslashes(trim($csvuser[13]))."','".$ustrdate."','".$uexpdate."','".trim(grptoid($csvuser[16]))."','".stripslashes(trim($csvuser[17]))."','".stripslashes(trim($csvuser[18]))."','".stripslashes(trim($csvuser[19]))."','".$csvuser[20]."','".admintoid($csvuser[21])."','".$csvuser[22]."','".admintoid($csvuser[23])."')");
										unset($usrprof);
										$sql="";
										$psql="";
										$gsql="";
										$impusers=$impusers.$csvuser[0]."<br>";
										$x++;
									} elseif($cmumcsvver=="2") {
										$mysqli->query("INSERT INTO users (user,password,displayname,ipmask,profiles,maxconn,admin,enabled,mapexclude,debug,comment,email,customvalues,ecmrate,startdate,expiredate,usrgroup,boxtype,macaddress,serialnumber,added,addedby,changed,changedby) VALUES ('".stripslashes(trim($csvuser[0]))."','".stripslashes(trim($csvuser[1]))."','".stripslashes(trim($csvuser[2]))."','".stripslashes(trim($csvuser[3]))."','".$usrprof."','".stripslashes(trim($csvuser[5]))."','".truefalse(trim($csvuser[6]))."','".truefalse(trim($csvuser[7]))."','".truefalse(trim($csvuser[8]))."','".truefalse(trim($csvuser[9]))."','".stripslashes(trim($csvuser[10]))."','".stripslashes(trim($csvuser[11]))."','".$mysqli->real_escape_string(trim($csvuser[13]))."','".stripslashes(trim($csvuser[14]))."','".$ustrdate."','".$uexpdate."','".trim(grptoid($csvuser[12]))."','','','','".$csvuser[17]."','".admintoid($csvuser[18])."','".$csvuser[19]."','".admintoid($csvuser[20])."')");
										unset($usrprof);
										$sql="";
										$psql="";
										$gsql="";
										$impusers=$impusers.$csvuser[0]."<br>";
										$x++;
									}
								} else {
									$impexists=$impexists.$csvuser[0]."<br>";
									$e++;
								}
						}
				}
		mysqli_close($mysqli);
	$status["usrimp"]=$x;
	$status["usrexi"]=$e;
	$status["profimp"]=$p;
	$status["grpimp"]=$g;
	$status["implist"]=$impusers;
	$status["exilist"]=$impexists;
	$status["proflist"]=$impprofiles;
	$status["grplist"]=$impgroups;
return($status);
}

function expusrcspxml($usrgrp) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
	$xmlout="<xml-user-manager ver=\"1.0\">\n";
	$profvalues="";
		$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
			if(isset($usrgrp) && $usrgrp<>"") {
				$usql=$mysqli->query("SELECT user,password,displayname,ipmask,profiles,maxconn,admin,enabled,mapexclude,debug,email,customvalues,ecmrate,startdate,expiredate FROM users WHERE usrgroup='".$usrgrp."'");
			} else {
				$usql=$mysqli->query("SELECT user,password,displayname,ipmask,profiles,maxconn,admin,enabled,mapexclude,debug,email,customvalues,ecmrate,startdate,expiredate FROM users");
			}
		while($usrdata=$usql->fetch_array()) {
			$profiles="";
			if($usrdata["profiles"]=="") {
				$profiles="";
			} else {
				$dbprof=unserialize($usrdata["profiles"]);
					if($dbprof<>"" && $dbprof<>"N;") {
						foreach($dbprof as $useprof) {
							$psql=$mysqli->query("SELECT cspvalue FROM profiles WHERE id='".$useprof."'");
							$profdata=$psql->fetch_array();
							$profvalues.=$profdata["cspvalue"]." ";
						}
						$profiles=trim($profvalues);
						$profdata="";
						$profvalues="";
					} else {
						$profiles="";
					}
			}
			$xmlout.="<user ".xmloutformat("name",$usrdata["user"]).xmloutformat("password",$usrdata["password"]).xmloutformat("display-name",$usrdata["displayname"]).xmloutformat("ip-mask",$usrdata["ipmask"]).xmloutformat("profiles",$profiles).xmloutformat("max-connections",$usrdata["maxconn"]).xmloutformat("admin",numbertotf($usrdata["admin"])).xmloutformat("enabled",numbertotf($usrdata["enabled"])).xmloutformat("map-exclude",numbertotf($usrdata["mapexclude"])).xmloutformat("debug",numbertotf($usrdata["debug"])).xmloutformat("email-address",$usrdata["email"]).xmloutformat("start-date",formatdate("d-m-Y",$usrdata["startdate"])).xmloutformat("expire-date",formatdate("d-m-Y",$usrdata["expiredate"])).htmlspecialchars($usrdata["customvalues"])."/>\n";
		}
		mysqli_close($mysqli);
	$xmlout.="</xml-user-manager>";
return($xmlout);
}

function expusrcsv($usrgrp) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
	$csvout="";
	$profvalues="";
		$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
			if(isset($usrgrp) && $usrgrp<>"") {
				$usql=$mysqli->query("SELECT * FROM users WHERE usrgroup='".$usrgrp."'");
			} else {
				$usql=$mysqli->query("SELECT * FROM users");
			}
		while($usrdata=$usql->fetch_array()) {
			$profiles="";
			if($usrdata["profiles"]=="") {
				$profiles="";
			} else {
				$dbprof=unserialize($usrdata["profiles"]);
					if($dbprof<>"" && $dbprof<>"N;") {
						foreach($dbprof as $useprof) {
							$psql=$mysqli->query("SELECT cspvalue FROM profiles WHERE id='".$useprof."'");
							$profdata=$psql->fetch_array();
							$profvalues.=$profdata["cspvalue"]." ";
						}
						$profiles=trim($profvalues);
						$profdata="";
						$profvalues="";
					} else {
						$profiles="";
					}
			}
			if($usrdata["comment"]<>"") {
				$comment=str_replace("\n"," ",$usrdata["comment"]);
				$comment=str_replace("\r"," ",$comment);
				$comment=preg_replace("/\s+/"," ",$comment);
			} else {
				$comment=$usrdata["comment"];
			}
		$csvout.=$usrdata["user"].";".$usrdata["password"].";".$usrdata["displayname"].";".$usrdata["ipmask"].";".$profiles.";".$usrdata["maxconn"].";".$usrdata["admin"].";".$usrdata["enabled"].";".$usrdata["mapexclude"].";".$usrdata["debug"].";".$comment.";".$usrdata["email"].";".htmlspecialchars($usrdata["customvalues"]).";".$usrdata["ecmrate"].";".$usrdata["startdate"].";".$usrdata["expiredate"].";".idtogrp($usrdata["usrgroup"]).";".$usrdata["boxtype"].";".$usrdata["macaddress"].";".$usrdata["serialnumber"].";".$usrdata["added"].";".idtoadmin($usrdata["addedby"]).";".$usrdata["changed"].";".idtoadmin($usrdata["changedby"]).";\n";
		}
		mysqli_close($mysqli);
return(trim($csvout));
}

function checktables() {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
	$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
		$mysqli->query("CHECK TABLE admins");
		$mysqli->query("CHECK TABLE groups");
		$mysqli->query("CHECK TABLE log_genxmlreq");
		$mysqli->query("CHECK TABLE log_login");
		$mysqli->query("CHECK TABLE profiles");
		$mysqli->query("CHECK TABLE settings");
		$mysqli->query("CHECK TABLE users");
	mysqli_close($mysqli);
return("1");
}

function analyzetables() {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
	$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
		$mysqli->query("ANALYZE TABLE admins");
		$mysqli->query("ANALYZE TABLE groups");
		$mysqli->query("ANALYZE TABLE log_genxmlreq");
		$mysqli->query("ANALYZE TABLE log_login");
		$mysqli->query("ANALYZE TABLE profiles");
		$mysqli->query("ANALYZE TABLE settings");
		$mysqli->query("ANALYZE TABLE users");
	mysqli_close($mysqli);
return("1");
}

function repairtables() {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
	$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
		$mysqli->query("REPAIR TABLE admins");
		$mysqli->query("REPAIR TABLE groups");
		$mysqli->query("REPAIR TABLE log_genxmlreq");
		$mysqli->query("REPAIR TABLE log_login");
		$mysqli->query("REPAIR TABLE profiles");
		$mysqli->query("REPAIR TABLE settings");
		$mysqli->query("REPAIR TABLE users");
	mysqli_close($mysqli);
return("1");
}

function optimizetables() {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
	$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
		$mysqli->query("OPTIMIZE TABLE admins");
		$mysqli->query("OPTIMIZE TABLE groups");
		$mysqli->query("OPTIMIZE TABLE log_genxmlreq");
		$mysqli->query("OPTIMIZE TABLE log_login");
		$mysqli->query("OPTIMIZE TABLE profiles");
		$mysqli->query("OPTIMIZE TABLE settings");
		$mysqli->query("OPTIMIZE TABLE users");
	mysqli_close($mysqli);
return("1");
}

function downloadexpusrcsv($data) {
	ob_end_clean();
		header('Content-type: text/csv; charset='.$charset);
		header('Content-Disposition: attachment; filename="userexport-'.date("Ymd").'.csv"');
		echo trim($data);
	exit();
}

function downloadexpusrxml($data) {
	ob_end_clean();
		header('Content-type: text/xml; charset='.$charset);
		header('Content-Disposition: attachment; filename="userexport-'.date("Ymd").'.xml"');
		echo trim($data);
	exit();
}

function impcspprofiles($pvalue,$pname) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
	$p=0;
	$e=0;
	$impprofiles="";
	$impexists="";
	$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
		foreach($pvalue as $x => $value) {
			if(isset($pvalue[$x]) && $pvalue[$x]<>"") {
				$pcsql=$mysqli->query("SELECT id FROM profiles WHERE cspvalue='".$pvalue[$x]."'");
				$prowcheck=$pcsql->num_rows;
					if($prowcheck==0) {
						if(isset($pname[$x]) && $pname[$x]<>"") {
							$pncsql=$mysqli->query("SELECT id FROM profiles WHERE name='".$pname[$x]."'");
							$pnrowcheck=$pncsql->num_rows;
								if($pnrowcheck==0) {
									addprofile($pname[$x],$pvalue[$x],"Imported profile");
									$impprofiles=$impprofiles.$pname[$x]." (".$pvalue[$x].")<br>";
									$p++;
								} else {
									$impexists=$impexists.$pname[$x]." (".$pvalue[$x].")<br>";
									$e++;
								}
						}
					} else {
						$impexists=$impexists.$pname[$x]." (".$pvalue[$x].")<br>";
						$e++;
					}
			}
		}
	mysqli_close($mysqli);
	$status["profimp"]=$p;
	$status["profexi"]=$e;
	$status["proflist"]=$impprofiles;
	$status["exilist"]=$impexists;
return($status);
}

function cmumupdcheck($currver) {
	$latestver=@file_get_contents("https://raw.githubusercontent.com/dukereborn/cmum/master/VERSION");
	if($latestver<>"" && $latestver>$currver) {
		$status="1;".$latestver;
	} elseif($latestver<>"" && $latestver==$currver || $latestver<>"" && $latestver<$currver) {
		$status="0;".$latestver;
	} else {
		$status="2;";
	}
return($status);
}

function sendemailtoall($subject,$body) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
	if(file_exists("functions/class.phpmailer.php")) {
		require("functions/class.phpmailer.php");
	} else {
		require("../functions/class.phpmailer.php");
	}
	if(file_exists("functions/class.smtp.php")) {
		require("functions/class.smtp.php");
	} else {
		require("../functions/class.smtp.php");
	}
	set_time_limit(0);
		$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
			$sql=$mysqli->query("SELECT email_host,email_port,email_secure,email_auth,email_authuser,email_authpass,email_fromname,email_fromaddr FROM settings WHERE id='1'");
			$data=$sql->fetch_array();
			$usrsql=$mysqli->query("SELECT email FROM users WHERE email<>''");
		mysqli_close($mysqli);
			$mail=new PHPMailer;
				$mail->isSMTP();
				$mail->CharSet=$charset;
				$mail->Host=$data["email_host"];
				$mail->Port=$data["email_port"];
					if($data["email_secure"]==1) {
						$mail->SMTPSecure="ssl";
					} elseif($data["email_secure"]==2) {
						$mail->SMTPSecure="tls";
					}
					if($data["email_auth"]==1) {
						$mail->SMTPAuth=true;
						$mail->Username=$data["email_authuser"];
						$mail->Password=$data["email_authpass"];
					} else {
						$mail->SMTPAuth=false;
					}
				$mail->isHTML(false);
				$mail->setFrom($data["email_fromaddr"],$data["email_fromname"]);
				$mail->Subject=trim($subject);
				$mail->Body=trim($body);
					while($usrdata=$usrsql->fetch_array()) {
						$mail->addAddress($usrdata["email"]);
							if(!$mail->send()) {
								$status="1";
								break;
							} else {
								$status="0";
							}
						$mail->clearAddresses();
					}
return($status);
}

//
// version functions
//
function checkversion($currver) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
	$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
		$versql=$mysqli->query("SELECT cmumversion FROM settings WHERE id='1' LIMIT 1");
		$verres=$versql->fetch_array();
			if($currver>$verres["cmumversion"]) {
				$status="0";
			} else {
				$status="1";
			}
	mysqli_close($mysqli);
return($status);
}


//
// user functions
//
function adduser($user,$password,$displayname,$email,$ipmask,$maxconn,$ecmrate,$customvalues,$usrgroup,$admin,$enabled,$mapexclude,$debug,$startdate,$expiredate,$profiles,$boxtype,$macaddress,$serialnumber,$comment) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
			if(stripslashes(trim($user))=="" || stripslashes(trim($password))=="") {
				$status="1";
			} else {
				if($profiles=="") {
					$profiles="N;";
				} else {
					$profiles=serialize($profiles);
				}
				if($startdate<>"") {
					$startdate=strtotime($startdate);
					$startdate=date('Y-m-d',$startdate);
				} else {
					$startdate="";
				}
				if($expiredate<>"") {
					$expiredate=strtotime($expiredate);
					$expiredate=date('Y-m-d',$expiredate);
				} else {
					$expiredate="";
				}
				$mysqli->query("INSERT INTO users (user,password,displayname,ipmask,profiles,maxconn,admin,enabled,mapexclude,debug,comment,email,customvalues,ecmrate,startdate,expiredate,usrgroup,boxtype,macaddress,serialnumber,addedby,changed,changedby) VALUES ('".stripslashes(trim($user))."','".stripslashes(trim($password))."','".stripslashes(trim($displayname))."','".stripslashes(trim($ipmask))."','".$profiles."','".stripslashes(trim($maxconn))."','".trim($admin)."','".trim($enabled)."','".trim($mapexclude)."','".trim($debug)."','".stripslashes(trim($comment))."','".stripslashes(trim($email))."','".$mysqli->real_escape_string(trim($customvalues))."','".stripslashes(trim($ecmrate))."','".$startdate."','".$expiredate."','".trim($usrgroup)."','".stripslashes(trim($boxtype))."','".stripslashes(trim($macaddress))."','".stripslashes(trim($serialnumber))."','".$_SESSION[$secretkey."admid"]."','','')");
				$newuid=$mysqli->insert_id;
				if(checksetting("logactivity")=="1") {
					logactivity("1",$_SESSION[$secretkey."admid"],$_SESSION[$secretkey."admlvl"],$newuid,$user);
				}
				$status="0";
			}
		mysqli_close($mysqli);
return($status);
}

function edituser($uid,$user,$password,$displayname,$email,$ipmask,$maxconn,$ecmrate,$customvalues,$usrgroup,$admin,$enabled,$mapexclude,$debug,$startdate,$expiredate,$profiles,$boxtype,$macaddress,$serialnumber,$comment) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
			if(stripslashes(trim($user))=="" || stripslashes(trim($password))=="") {
				$status="1";
			} else {
				if($profiles=="") {
					$profiles="N;";
				} else {
					$profiles=serialize($profiles);
				}
				if($startdate<>"") {
					$startdate=strtotime($startdate);
					$startdate=date('Y-m-d',$startdate);
				} else {
					$startdate="";
				}
				if($expiredate<>"") {
					$expiredate=strtotime($expiredate);
					$expiredate=date('Y-m-d',$expiredate);
				} else {
					$expiredate="";
				}
				$mysqli->query("UPDATE users SET user='".stripslashes(trim($user))."',password='".stripslashes(trim($password))."',displayname='".stripslashes(trim($displayname))."',ipmask='".stripslashes(trim($ipmask))."',profiles='".$profiles."',maxconn='".trim($maxconn)."',admin='".trim($admin)."',enabled='".trim($enabled)."',mapexclude='".trim($mapexclude)."',debug='".trim($debug)."',user='".stripslashes(trim($user))."',comment='".stripslashes(trim($comment))."',email='".stripslashes(trim($email))."',customvalues='".$mysqli->real_escape_string(trim($customvalues))."',ecmrate='".stripslashes(trim($ecmrate))."',startdate='".$startdate."',expiredate='".$expiredate."',usrgroup='".trim($usrgroup)."',boxtype='".stripslashes(trim($boxtype))."',macaddress='".stripslashes(trim($macaddress))."',serialnumber='".stripslashes(trim($serialnumber))."',changed='".date('Y-m-d H:i:s')."',changedby='".$_SESSION[$secretkey."admid"]."' WHERE id='".$uid."'");
				if(checksetting("logactivity")=="1") {
					logactivity("2",$_SESSION[$secretkey."admid"],$_SESSION[$secretkey."admlvl"],$uid,$user);
				}
				$status="0";
			}
		mysqli_close($mysqli);
return($status);
}

function deleteuser($uid) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
	@session_start();
		$admlvl=$_SESSION[$secretkey."admlvl"];
		$admgrp=$_SESSION[$secretkey."admgrp"];
		$admid=$_SESSION[$secretkey."admid"];
			$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
				$sql=$mysqli->query("SELECT usrgroup FROM users WHERE id='".$uid."'");
				$delres=$sql->fetch_array();
					if($admlvl=="2" && $admgrp<>$delres["usrgroup"]) {
						$status="1";
					} else {
						$username=idtouser($uid);
						$mysqli->query("DELETE FROM users WHERE id='".$uid."'");
						if(checksetting("logactivity")=="1") {
							logactivity("3",$admid,$admlvl,$uid,$username);
						}
						$status="0";
					}
			mysqli_close($mysqli);
return($status);
}

function enableuser($uid) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		@session_start();
		$admlvl=$_SESSION[$secretkey."admlvl"];
		$admgrp=$_SESSION[$secretkey."admgrp"];
		$admid=$_SESSION[$secretkey."admid"];
			$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
				$sql=$mysqli->query("SELECT usrgroup FROM users WHERE id='".$uid."'");
				$delres=$sql->fetch_array();
					if($admlvl=="2" && $admgrp<>$delres["usrgroup"]) {
						$status="1";
					} else {
						$usrexp=checkuserstartexpire($uid);
						if($usrexp=="2") {
							$mysqli->query("UPDATE users SET enabled='1',startdate='".date('Y-m-d')."',changed='".date('Y-m-d H:i:s')."',changedby='".$admid."' WHERE id='".$uid."'");
						} elseif($usrexp=="3") {
							$mysqli->query("UPDATE users SET enabled='1',expiredate='0000-00-00',changed='".date('Y-m-d H:i:s')."',changedby='".$admid."' WHERE id='".$uid."'");
						} else {
							$mysqli->query("UPDATE users SET enabled='1',changed='".date('Y-m-d H:i:s')."',changedby='".$admid."' WHERE id='".$uid."'");
						}
						if(checksetting("logactivity")=="1") {
							logactivity("4",$admid,$admlvl,$uid,idtouser($uid));
						}
						$status="0";
					}
			mysqli_close($mysqli);
return($status);
}

function disableuser($uid) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		@session_start();
		$admlvl=$_SESSION[$secretkey."admlvl"];
		$admgrp=$_SESSION[$secretkey."admgrp"];
		$admid=$_SESSION[$secretkey."admid"];
			$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
				$sql=$mysqli->query("SELECT usrgroup FROM users WHERE id='".$uid."'");
				$delres=$sql->fetch_array();
					if($admlvl=="2" && $admgrp<>$delres["usrgroup"]) {
						$status="1";
					} else {
						$mysqli->query("UPDATE users SET enabled='0',changed='".date('Y-m-d H:i:s')."',changedby='".$admid."' WHERE id='".$uid."'");
						if(checksetting("logactivity")=="1") {
							logactivity("5",$admid,$admlvl,$uid,idtouser($uid));
						}
						$status="0";
					}
			mysqli_close($mysqli);
return($status);
}

function checkstartexpire($start,$expire,$enabled) {
	// return codes
	// 0 = disabled
	// 1 = enabled
	// 2 = not started
	// 3 = expired
	if($start<>"0000-00-00" && $expire<>"0000-00-00") {
		if(time()>=strtotime($start) && time()<=strtotime($expire) && $enabled<>"0") {
			$status="1";
		} elseif(time()<=strtotime($start) && $enabled<>"0") {
			$status="2";
		} elseif(time()>=strtotime($expire) && $enabled<>"0") {
			$status="3";
		} else {
			if($enabled=="1" || $enabled=="") {
				$status="1";
			} else {
				$status="0";
			}
		}
	} elseif($start<>"0000-00-00" && $expire=="0000-00-00") {
		if(time()>=strtotime($start) && $enabled<>"0") {
			$status="1";
		} elseif(time()<=strtotime($start) && $enabled<>"0") {
			$status="2";
		} else {
			if($enabled=="1" || $enabled=="") {
				$status="1";
			} else {
				$status="0";
			}
		}
	} elseif($start=="0000-00-00" && $expire<>"0000-00-00") {
		if(time()<=strtotime($expire) && $enabled<>"0") {
			$status="1";
		} elseif(time()>=strtotime($expire) && $enabled<>"0") {
			$status="3";
		} else {
			if($enabled=="1" || $enabled=="") {
				$status="1";
			} else {
				$status="0";
			}
		}
	} elseif($start=="0000-00-00" && $expire=="0000-00-00") {
		if($enabled=="1" || $enabled=="") {
			$status="1";
		} else {
			$status="0";
		}
	}
return($status);
}

function checkuserstartexpire($uid) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		if($uid=="") {
			$status="";
		} else {
			$mysqliu=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
				$u_sql=$mysqliu->query("SELECT enabled,startdate,expiredate FROM users WHERE id='".$uid."'");
				$u_res=$u_sql->fetch_array();
			mysqli_close($mysqliu);
			$status=checkstartexpire($u_res["startdate"],$u_res["expiredate"],$u_res["enabled"]);
		}
return($status);
}

function usertoid($username) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		if($username=="") {
			$status="";
		} else {
			$mysqlig=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
				$u_sql=$mysqlig->query("SELECT id FROM users WHERE user='".$username."'");
				$u_res=$u_sql->fetch_array();
					if($u_res["id"]<>"") {
						$status=$u_res["id"];
					} else {
						$status="";
					}
			mysqli_close($mysqlig);
		}
return($status);
}

function idtouser($uid) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		if($uid=="") {
			$status="";
		} else {
			$mysqlig=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
				$u_sql=$mysqlig->query("SELECT user FROM users WHERE id='".$uid."'");
				$u_res=$u_sql->fetch_array();
					if($u_res["user"]<>"") {
						$status=$u_res["user"];	
					} else {
						$status="";
					}
			mysqli_close($mysqlig);
		}
return($status);
}

//
// genxml functions
//
function genxml($genxmlkey,$reqip,$option) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
	$dogen="";
	$xmlout="";
	$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
		$genxmlkey=$mysqli->real_escape_string($genxmlkey);
		$opts=explode(";",$option);
		$sql=$mysqli->query("SELECT genxmlkey,genxmlusrgrp,genxmllogreq,genxmldateformat,extrausrtbl FROM settings WHERE id='1'");
		$setres=$sql->fetch_array();
			if($setres["genxmlkey"]<>"") {
				if($setres["genxmlkey"]==$genxmlkey) {
					$dogen="1";
					if($setres["genxmllogreq"]=="1") {
						$mysqli->query("INSERT INTO log_genxmlreq (status,ip,genxmlkey) VALUES ('0','".$reqip."','')");
					}
				} else {
					if($setres["genxmllogreq"]=="1" || $setres["genxmllogreq"]=="2") {
						$mysqli->query("INSERT INTO log_genxmlreq (status,ip,genxmlkey) VALUES ('1','".$reqip."','".$genxmlkey."')");
					}
				}
			} elseif($setres["genxmlkey"]=="") {
				$dogen="1";
				if($setres["genxmllogreq"]=="1") {
					$mysqli->query("INSERT INTO log_genxmlreq (status,ip,genxmlkey) VALUES ('0','".$reqip."','')");
				}
			}
			if($dogen=="1") {
				$enabledgroups=enabledgroups();
				$intstrexp=checksetting("genxmlintstrexp");
				$xmlout="<xml-user-manager ver=\"1.0\">\n";
				$profvalues="";
					$usql=$mysqli->query("SELECT user,password,displayname,ipmask,profiles,maxconn,admin,enabled,mapexclude,debug,email,customvalues,ecmrate,startdate,expiredate,usrgroup FROM users");
				while($usrdata=$usql->fetch_array()) {
					$profres="";
					if($usrdata["profiles"]=="") {
						$profres="";
					} else {
						$dbprof=unserialize($usrdata["profiles"]);
						$profdata=getprofiles();
							if($dbprof<>"" && $dbprof<>"N;") {
								foreach($dbprof as $useprof) {
									$profvalues.=$profdata[$useprof]." ";
								}
								$profres=trim($profvalues);
								$profdata="";
								$profvalues="";
							} else {
								$profres="";
							}
					}
					if(in_array("nousername",$opts)) {
						$username="";
					} else {
						$username=xmloutformat("name",$usrdata["user"]);
					}
					if(in_array("nopassword",$opts)) {
						$password="";
					} else {
						$password=xmloutformat("password",$usrdata["password"]);
					}
					if(in_array("nodisplayname",$opts)) {
						$displayname="";
					} else {
						if($setres["genxmlusrgrp"]=="1") {
							$displayname=xmloutformatwusrgrp("display-name",$usrdata["displayname"],$usrdata["usrgroup"]);
						} else {
							$displayname=xmloutformat("display-name",$usrdata["displayname"]);
						}
					}
					if(in_array("noipmask",$opts)) {
						$ipmask="";
					} else {
						$ipmask=xmloutformat("ip-mask",$usrdata["ipmask"]);
					}
					if(in_array("noprofiles",$opts)) {
						$profiles="";
					} else {
						$profiles=xmloutformat("profiles",$profres);
					}
					if(in_array("nomaxconnections",$opts)) {
						$maxconn="";
					} else {
						$maxconn=xmloutformat("max-connections",$usrdata["maxconn"]);
					}
					if(in_array("noadmin",$opts)) {
						$admin="";
					} else {
						$admin=xmloutformat("admin",numbertotf($usrdata["admin"]));
					}
					if(in_array("noenabled",$opts)) {
						$enabled="";
					} else {
						if(!in_array($usrdata["usrgroup"],$enabledgroups)) {
							$enabled=xmloutformat("enabled","false");
						} elseif($intstrexp=="1") {
							$usrexp=checkstartexpire($usrdata["startdate"],$usrdata["expiredate"],$usrdata["enabled"]);
								if($usrexp=="0") {
									$enabled=xmloutformat("enabled","false");
								} elseif($usrexp=="1") {
									$enabled=xmloutformat("enabled","true");
								} elseif($usrexp=="2") {
									$enabled=xmloutformat("enabled","false");
								} elseif($usrexp=="3") {
									$enabled=xmloutformat("enabled","false");
								} else {
									$enabled=xmloutformat("enabled",numbertotf($usrdata["enabled"]));
								}
						} else {
							$enabled=xmloutformat("enabled",numbertotf($usrdata["enabled"]));
						}
					}
					if(in_array("nomapexclude",$opts)) {
						$mapexclude="";
					} else {
						$mapexclude=xmloutformat("map-exclude",numbertotf($usrdata["mapexclude"]));
					}
					if(in_array("nodebug",$opts)) {
						$debug="";
					} else {
						$debug=xmloutformat("debug",numbertotf($usrdata["debug"]));
					}
					if(in_array("noemail",$opts)) {
						$email="";
					} else {
						$email=xmloutformat("email-address",$usrdata["email"]);
					}
					if(in_array("nostartdate",$opts) || $intstrexp=="1") {
						$startdate="";
					} else {
						$startdate=xmloutformat("start-date",formatdate($setres["genxmldateformat"],$usrdata["startdate"]));
					}
					if(in_array("noexpiredate",$opts) || $intstrexp=="1") {
						$expiredate="";
					} else {
						$expiredate=xmloutformat("expire-date",formatdate($setres["genxmldateformat"],$usrdata["expiredate"]));
					}
					if(in_array("nocustomvalues",$opts)) {
						$customvalues="";
					} else {
						$customvalues=$usrdata["customvalues"];
					}
					$xmlout.="<user ".$username.$password.$displayname.$ipmask.$profiles.$maxconn.$admin.$enabled.$mapexclude.$debug.$email.$startdate.$expiredate.$customvalues."/>\n";
				}
				$xmlout.="</xml-user-manager>";
			}
	mysqli_close($mysqli);
return($xmlout);
}