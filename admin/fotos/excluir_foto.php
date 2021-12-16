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
if ((isset($HTTP_GET_VARS['IDFoto'])) && ($HTTP_GET_VARS['IDFoto'] != "")) {
	mysql_select_db($database_conection, $conection);
	$dbr_result = mysql_query("SELECT Foto FROM Fotos WHERE IDFoto=".$HTTP_GET_VARS['IDFoto'], $conection) or die(mysql_error());
	$dbr = new deleteFileBeforeRecord();
	$dbr->sqldata = mysql_fetch_array($dbr_result);
	$dbr->path = "../../imagens";
	$dbr->pathThumb = "../../imagens/minis";
	$dbr->naming = "prefix";
	$dbr->suffix = "";
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

if ((isset($HTTP_GET_VARS['IDFoto'])) && ($HTTP_GET_VARS['IDFoto'] != "")) {
  $deleteSQL = sprintf("DELETE FROM Fotos WHERE IDFoto=%s",
                       GetSQLValueString($HTTP_GET_VARS['IDFoto'], "int"));

  mysql_select_db($database_conection, $conection);
  $Result1 = mysql_query($deleteSQL, $conection) or die(mysql_error());

  $deleteGoTo = "incluir_foto.php";
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

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
if (isset($HTTP_GET_VARS['IDFoto'])) {
  $colname_rsFotos = (get_magic_quotes_gpc()) ? $HTTP_GET_VARS['IDFoto'] : addslashes($HTTP_GET_VARS['IDFoto']);
}
mysql_select_db($database_conection, $conection);
$query_rsFotos = sprintf("SELECT * FROM Fotos WHERE IDFoto = %s", $colname_rsFotos);
$rsFotos = mysql_query($query_rsFotos, $conection) or die(mysql_error());
$row_rsFotos = mysql_fetch_assoc($rsFotos);
$totalRows_rsFotos = mysql_num_rows($rsFotos);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style type="text/css">
<!--
.style5 {font-family: Arial, Helvetica, sans-serif; font-size: 10px; }
.style6 {font-size: 10px}
.style7 {	font-size: 18px;
	font-family: Geneva, Arial, Helvetica, sans-serif;
}
-->
</style>
</head>

<body>
<table width="580" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="9">&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td width="10">&nbsp;</td>
  </tr>
  <tr>
    <td width="9">&nbsp;</td>
    <td colspan="2" align="left">&nbsp;</td>
    <td width="10">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2" class="style7">Editando Fotos no Álbum <?php echo $row_rsAlbuns['Desc']; ?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="9">&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td width="10">&nbsp;</td>
  </tr>
  <tr>
    <td width="9">&nbsp;</td>
    <td colspan="2" align="center"><form action="" method="post" enctype="multipart/form-data" name="fm_criaalbum" id="fm_criaalbum">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="140" align="right"><span class="style5">Descrição da Foto:</span></td>
          <td width="10">&nbsp;</td>
          <td><input name="Desc" type="text" id="Desc" value="<?php echo $row_rsFotos['Desc']; ?>" size="40" /></td>
        </tr>
        <tr>
          <td align="right">&nbsp;</td>
          <td>&nbsp;</td>
          <td><img src="../../imagens/minis/<?php echo $row_rsFotos['Foto']; ?>" /> <span class="style6">Foto Atual</span></td>
        </tr>
        <tr>
          <td width="140" align="right"><span class="style5">Foto:</span></td>
          <td width="10">&nbsp;</td>
          <td><input name="Capa" type="file" id="Capa" size="40" />
            <input name="IDAlbum" type="hidden" id="IDAlbum" value="<?php echo $row_rsAlbuns['IDAlbum']; ?>" />
            <input name="IDFoto" type="hidden" id="IDFoto" value="<?php echo $row_rsFotos['IDFoto']; ?>" /></td>
        </tr>
        <tr>
          <td width="140" align="right"><span class="style6"></span></td>
          <td width="10">&nbsp;</td>
          <td><input type="submit" name="btn_editarfoto" id="btn_editarfoto" value="Editar Foto" /></td>
        </tr>
      </table>
    </form></td>
    <td width="10">&nbsp;</td>
  </tr>
  <tr>
    <td width="9">&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td width="10">&nbsp;</td>
  </tr>
  <tr>
    <td width="9">&nbsp;</td>
    <td colspan="2" class="style7">Fotos deste Álbum <span class="style5">(<?php echo $totalRows_rsFotos ?> Fotos)</span></td>
    <td width="10">&nbsp;</td>
  </tr>
  <tr>
    <td width="9">&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td width="10">&nbsp;</td>
  </tr>
  <?php do { ?>
    <tr>
      <td>&nbsp;</td>
      <td width="390"><img src="../../imagens/minis/<?php echo $row_rsFotos['IDAlbum']; ?>" /> <span class="style5"><?php echo $row_rsFotos['Desc']; ?></span></td>
      <td width="171" align="center" class="style5"><a href="editar_foto.php?IDAlbum=<?php echo $row_rsFotos['IDAlbum']; ?>&amp;IDFoto=<?php echo $row_rsFotos['IDFoto']; ?>">Editar</a> / <a href="excluir_foto.php?IDAlbum=<?php echo $row_rsFotos['IDAlbum']; ?>&amp;IDFoto=<?php echo $row_rsFotos['IDFoto']; ?>">Excluir</a></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <?php } while ($row_rsFotos = mysql_fetch_assoc($rsFotos)); ?>
  <tr>
    <td width="9">&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td width="10">&nbsp;</td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($rsAlbuns);

mysql_free_result($rsFotos);
?>
