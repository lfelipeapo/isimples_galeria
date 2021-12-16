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
<?php
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

$colname_rsAlbuns = "-1";
if (isset($_GET['IDAlbum'])) {
  $colname_rsAlbuns = $_GET['IDAlbum'];
}
$colname_rsAlbuns = "1";
if (isset($HTTP_GET_VARS['IDAlbum'])) {
  $colname_rsAlbuns = (get_magic_quotes_gpc()) ? $HTTP_GET_VARS['IDAlbum'] : addslashes($HTTP_GET_VARS['IDAlbum']);
}
mysql_select_db($database_conection, $conection);
$query_rsAlbuns = sprintf("SELECT * FROM Albuns WHERE IDAlbum = %s", $colname_rsAlbuns);
$rsAlbuns = mysql_query($query_rsAlbuns, $conection) or die(mysql_error());
$row_rsAlbuns = mysql_fetch_assoc($rsAlbuns);
$totalRows_rsAlbuns = mysql_num_rows($rsAlbuns);

$colname_rsFotos = "-1";
if (isset($_GET['IDAlbum'])) {
  $colname_rsFotos = $_GET['IDAlbum'];
}
$colname_rsFotos = "1";
if (isset($HTTP_GET_VARS['IDAlbum'])) {
  $colname_rsFotos = (get_magic_quotes_gpc()) ? $HTTP_GET_VARS['IDAlbum'] : addslashes($HTTP_GET_VARS['IDAlbum']);
}
mysql_select_db($database_conection, $conection);
$query_rsFotos = sprintf("SELECT * FROM Fotos WHERE IDAlbum = '%s'", $colname_rsFotos);
$rsFotos = mysql_query($query_rsFotos, $conection) or die(mysql_error());
$row_rsFotos = mysql_fetch_assoc($rsFotos);
$totalRows_rsFotos = mysql_num_rows($rsFotos);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style type="text/css">
<!--
.style5 {font-family: Arial, Helvetica, sans-serif; font-size: 10px; }
.style7 {
	font-size: 18px;
	font-family: Geneva, Arial, Helvetica, sans-serif;
}
.style8 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #FF0000;
	font-weight: bold;
}
.style9 {font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #0000FF; }
-->
</style>
</head>

<body>
<table width="580" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="10">&nbsp;</td>
    <td width="580">&nbsp;</td>
    <td width="10">&nbsp;</td>
  </tr>
  <tr>
    <td width="10">&nbsp;</td>
    <td width="580" align="left">&nbsp;</td>
    <td width="10">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td class="style7">Excluindo um Álbum</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="10">&nbsp;</td>
    <td width="580">&nbsp;</td>
    <td width="10">&nbsp;</td>
  </tr>
  <?php if ($totalRows_rsFotos == 0) { // Show if recordset empty ?>
    <tr>
      <td width="10">&nbsp;</td>
      <td width="580" align="center"><p><span class="style5">Você tem certeza que deseja excluir o Álbum<br />
      </span></p>
        <p> <img src="../../imagens/capa/<?php echo $row_rsAlbuns['Capa']; ?>" /><span class="style5"><br />
          </span><span class="style8"><?php echo $row_rsAlbuns['Desc']; ?></span></p>
        <p><span class="style5"><font color="#00CC00"><a href="excluir_albuns_ok.php?IDAlbum=<?php echo $row_rsAlbuns['IDAlbum']; ?>">Sim, excluir</a></font></span><br />
        </p>        <p class="style5"><font color="#FF0000"><a href="../albuns.php">Não, voltar</a></font></p></td>
      <td width="10">&nbsp;</td>
    </tr>
    <?php } // Show if recordset empty ?>

  <tr>
    <td width="10">&nbsp;</td>
    <td width="580">&nbsp;</td>
    <td width="10">&nbsp;</td>
  </tr>
  <?php if ($totalRows_rsFotos > 0) { // Show if recordset not empty ?>
    <tr>
      <td width="10">&nbsp;</td>
      <td width="580" align="center" class="style8">Antes de excluir este álbum delete todas as fotos deste álbum!</td>
      <td width="10">&nbsp;</td>
    </tr>
    <?php } // Show if recordset not empty ?>
  <?php if ($totalRows_rsFotos > 0) { // Show if recordset not empty ?>
    <?php do { ?>
      <tr>
        <td width="10">&nbsp;</td>
        <td width="580" align="left" class="style9"><img src="../../imagens/minis/<?php echo $row_rsFotos['Foto']; ?>" /> <span class="style8"><a href="excluir_foto_album.php?IDFoto=<?php echo $row_rsFotos['IDFoto']; ?>&amp;IDAlbum=<?php echo $row_rsFotos['IDAlbum']; ?>">Excluir</a></span></td>
        <td width="10">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <?php } while ($row_rsFotos = mysql_fetch_assoc($rsFotos)); ?>
    <?php } // Show if recordset not empty ?>
<tr>
    <td width="10">&nbsp;</td>
    <td width="580">&nbsp;</td>
    <td width="10">&nbsp;</td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($rsAlbuns);

mysql_free_result($rsFotos);
?>
