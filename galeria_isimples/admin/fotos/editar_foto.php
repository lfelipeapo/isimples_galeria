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
<?php require_once('../../../ScriptLibrary/incPureUpload.php'); ?>
<?php require_once('../../../ScriptLibrary/incResize.php'); ?>
<?php require_once('../../../ScriptLibrary/incPUAddOn.php'); ?>
<?php
// Pure PHP Upload 2.1.2
if (isset($HTTP_GET_VARS['GP_upload'])) {
	$ppu = new pureFileUpload();
	$ppu->path = "../../imagens";
	$ppu->extensions = "GIF,JPG,JPEG,BMP,PNG";
	$ppu->formName = "fm_criaalbum";
	$ppu->storeType = "file";
	$ppu->sizeLimit = "2000";
	$ppu->nameConflict = "uniq";
	$ppu->requireUpload = "false";
	$ppu->minWidth = "";
	$ppu->minHeight = "";
	$ppu->maxWidth = "";
	$ppu->maxHeight = "";
	$ppu->saveWidth = "";
	$ppu->saveHeight = "";
	$ppu->timeout = "600";
	$ppu->progressBar = "fileCopyProgress.htm";
	$ppu->progressWidth = "300";
	$ppu->progressHeight = "100";
	$ppu->checkVersion("2.1.2");
	$ppu->doUpload();
}
$GP_uploadAction = $HTTP_SERVER_VARS['PHP_SELF'];
if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
  if (!eregi("GP_upload=true", $HTTP_SERVER_VARS['QUERY_STRING'])) {
		$GP_uploadAction .= "?".$HTTP_SERVER_VARS['QUERY_STRING']."&GP_upload=true";
	} else {
		$GP_uploadAction .= "?".$HTTP_SERVER_VARS['QUERY_STRING'];
	}
} else {
  $GP_uploadAction .= "?"."GP_upload=true";
}

// Delete Before Update Addon 1.0.3
if ((isset($HTTP_POST_VARS["MM_update"])) && ($HTTP_POST_VARS["MM_update"] == "fm_criaalbum")) {
  mysql_select_db($database_conection, $conection);
	$dbu_result = mysql_query("SELECT * FROM Fotos WHERE IDFoto=".$HTTP_POST_VARS['IDFoto'] , $conection) or die(mysql_error());
	$dbu = new deleteFileBeforeUpdate($ppu);
	$dbu->sqldata = mysql_fetch_array($dbu_result);
	$dbu->pathThumb = "../../imagens/minis";
	$dbu->naming = "suffix";
	$dbu->suffix = "";
	$dbu->checkVersion("1.0.3");
	$dbu->deleteFile();
}

// Rename Uploaded Files Addon 1.0.3
if (isset($HTTP_GET_VARS['GP_upload'])) {
  $ruf = new renameUploadedFiles($ppu);
  $ruf->renameMask = "fotos_album.jpg";
  $ruf->checkVersion("1.0.3");
  $ruf->doRename();
}

// Smart Image Processor 1.0.3
if (isset($HTTP_GET_VARS['GP_upload'])) {
  $sip = new resizeUploadedFiles($ppu);
  $sip->component = "GD";
  $sip->resizeImages = "true";
  $sip->aspectImages = "true";
  $sip->maxWidth = "500";
  $sip->maxHeight = "333";
  $sip->quality = "80";
  $sip->makeThumb = "true";
  $sip->pathThumb = "../../imagens/minis";
  $sip->aspectThumb = "true";
  $sip->naming = "suffix";
  $sip->suffix = "";
  $sip->maxWidthThumb = "100";
  $sip->maxHeightThumb = "75";
  $sip->qualityThumb = "70";
  $sip->checkVersion("1.0.3");
  $sip->doResize();
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

$editFormAction = $HTTP_SERVER_VARS['PHP_SELF'];
if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $editFormAction .= "?" . $HTTP_SERVER_VARS['QUERY_STRING'];
}

if (isset($editFormAction)) {
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
	  if (!eregi("GP_upload=true", $HTTP_SERVER_VARS['QUERY_STRING'])) {
  	  $editFormAction .= "&GP_upload=true";
		}
  } else {
    $editFormAction .= "?GP_upload=true";
  }
}

if ((isset($HTTP_POST_VARS["MM_update"])) && ($HTTP_POST_VARS["MM_update"] == "fm_criaalbum")) {
  $updateSQL = sprintf("UPDATE Fotos SET IDAlbum=%s, Foto=IFNULL(%s,Foto), `Desc`=%s WHERE IDFoto=IFNULL(%s,Foto)",
                       GetSQLValueString($HTTP_POST_VARS['IDAlbum'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['Foto'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['Desc'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['IDFoto'], "int"));

  mysql_select_db($database_conection, $conection);
  $Result1 = mysql_query($updateSQL, $conection) or die(mysql_error());

  $updateGoTo = "incluir_foto.php";
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
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
if (isset($HTTP_GET_VARS['IDAlbum'])) {
  $colname_rsFotos = (get_magic_quotes_gpc()) ? $HTTP_GET_VARS['IDAlbum'] : addslashes($HTTP_GET_VARS['IDAlbum']);
}
mysql_select_db($database_conection, $conection);
$query_rsFotos = sprintf("SELECT * FROM Fotos WHERE IDAlbum = '%s'", $colname_rsFotos);
$rsFotos = mysql_query($query_rsFotos, $conection) or die(mysql_error());
$row_rsFotos = mysql_fetch_assoc($rsFotos);
$totalRows_rsFotos = mysql_num_rows($rsFotos);

$colname_rsFoto = "1";
if (isset($HTTP_GET_VARS['IDFoto'])) {
  $colname_rsFoto = (get_magic_quotes_gpc()) ? $HTTP_GET_VARS['IDFoto'] : addslashes($HTTP_GET_VARS['IDFoto']);
}
mysql_select_db($database_conection, $conection);
$query_rsFoto = sprintf("SELECT * FROM Fotos WHERE IDFoto = %s", $colname_rsFoto);
$rsFoto = mysql_query($query_rsFoto, $conection) or die(mysql_error());
$row_rsFoto = mysql_fetch_assoc($rsFoto);
$totalRows_rsFoto = mysql_num_rows($rsFoto);
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
<script language='javascript' src='../../../ScriptLibrary/incPureUpload.js'></script>
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
    <td colspan="2" align="center"><form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="fm_criaalbum" id="fm_criaalbum" onsubmit="checkFileUpload(this,'GIF,JPG,JPEG,BMP,PNG',false,2000,'','','','','','');showProgressWindow('fileCopyProgress.htm',300,100);return document.MM_returnValue">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="140" align="right"><span class="style5">Descrição da Foto:</span></td>
          <td width="10">&nbsp;</td>
          <td><input name="Desc" type="text" id="Desc" value="<?php echo $row_rsFoto['Desc']; ?>" size="40" /></td>
        </tr>
        <tr>
          <td width="140" align="right"><span class="style5">Foto:</span></td>
          <td width="10">&nbsp;</td>
          <td><input name="Foto" type="file" id="Foto" onchange="checkOneFileUpload(this,'GIF,JPG,JPEG,BMP,PNG',false,2000,'','','','','','')" size="40" />
            <input name="IDAlbum" type="hidden" id="IDAlbum" value="<?php echo $row_rsAlbuns['IDAlbum']; ?>" />
            <input name="IDFoto" type="hidden" id="IDFoto" value="<?php echo $row_rsFoto['IDFoto']; ?>" />
            <br />
            <img src="../../imagens/minis/<?php echo $row_rsFoto['Foto']; ?>" /> <span class="style5">Foto
            Atual</span></td>
        </tr>
        <tr>
          <td width="140" align="right"><span class="style6"></span></td>
          <td width="10">&nbsp;</td>
          <td><input type="submit" name="btn_adicionarfoto" id="btn_adicionarfoto" value="Atualizar Foto" /></td>
        </tr>
      </table>
      
      <input type="hidden" name="MM_update" value="fm_criaalbum">
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
      <td width="390"><img src="../../imagens/minis/<?php echo $row_rsFotos['Foto']; ?>" /> <span class="style5"><?php echo $row_rsFotos['Desc']; ?></span></td>
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

mysql_free_result($rsFoto);
?>
