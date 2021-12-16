<?php require_once('../../../Connections/conection.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "../login.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php require_once('../../../ScriptLibrary/incAddOnDelete.php'); ?>
<?php
// Delete Before Record Addon 1.0.3
if ((isset($HTTP_GET_VARS['IDAlbum'])) && ($HTTP_GET_VARS['IDAlbum'] != "")) {
	mysql_select_db($database_conection, $conection);
	$dbr_result = mysql_query("SELECT Capa FROM Albuns WHERE IDAlbum=".$HTTP_GET_VARS['IDAlbum'], $conection) or die(mysql_error());
	$dbr = new deleteFileBeforeRecord();
	$dbr->sqldata = mysql_fetch_array($dbr_result);
	$dbr->path = "../../imagens/capa";
	$dbr->pathThumb = "";
	$dbr->naming = "prefix";
	$dbr->suffix = "_small";
	$dbr->checkVersion("1.0.3");
	$dbr->deleteFile();
}

function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

if ((isset($HTTP_GET_VARS['IDAlbum'])) && ($HTTP_GET_VARS['IDAlbum'] != "")) {
  $deleteSQL = sprintf("DELETE FROM Albuns WHERE IDAlbum=%s",
                       GetSQLValueString($HTTP_GET_VARS['IDAlbum'], "int"));

  mysql_select_db($database_conection, $conection);
  $Result1 = mysql_query($deleteSQL, $conection) or die(mysql_error());

  $deleteGoTo = "../albuns.php";
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

if ((isset($_GET['IDAlbum'])) && ($_GET['IDAlbum'] != "")) {
  $deleteSQL = sprintf("DELETE FROM Albuns WHERE IDAlbum=%s",
                       GetSQLValueString($_GET['IDAlbum'], "int"));

  mysql_select_db($database_conection, $conection);
  $Result1 = mysql_query($deleteSQL, $conection) or die(mysql_error());

  $deleteGoTo = "../albuns.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

$colname_rsAlbum = "1";
if (isset($HTTP_GET_VARS['IDAlbum'])) {
  $colname_rsAlbum = $HTTP_GET_VARS['IDAlbum'];
}
$colname_rsAlbum = "1";
if (isset($HTTP_GET_VARS['IDAlbum'])) {
  $colname_rsAlbum = (get_magic_quotes_gpc()) ? $HTTP_GET_VARS['IDAlbum'] : addslashes($HTTP_GET_VARS['IDAlbum']);
}
mysql_select_db($database_conection, $conection);
$query_rsAlbum = sprintf("SELECT * FROM Albuns WHERE IDAlbum = %s", $colname_rsAlbum);
$rsAlbum = mysql_query($query_rsAlbum, $conection) or die(mysql_error());
$row_rsAlbum = mysql_fetch_assoc($rsAlbum);
$totalRows_rsAlbum = mysql_num_rows($rsAlbum);

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$colname_rsFotos = "-1";
if (isset($_GET['IDAlbum'])) {
  $colname_rsFotos = $_GET['IDAlbum'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<form id="fm_excluirfotoalbum" name="fm_excluirfotoalbum" method="post" action="">
  <input name="IDAlbum" type="hidden" id="IDAlbum" value="<?php echo $row_rsAlbum['IDAlbum']; ?>" />
</form>
</body>
</html>
<?php
mysql_free_result($rsAlbum);
?>
