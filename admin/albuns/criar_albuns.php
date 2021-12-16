<?php require_once('../../../Connections/conection.php'); ?>
<?php require_once('../../../ScriptLibrary/incPureUpload.php'); ?>
<?php require_once('../../../ScriptLibrary/incResize.php'); ?>
<?php require_once('../../../ScriptLibrary/incPUAddOn.php'); ?>
<?php
// Pure PHP Upload 2.1.2
if (isset($HTTP_GET_VARS['GP_upload'])) {
	$ppu = new pureFileUpload();
	$ppu->path = "../../imagens/capa";
	$ppu->extensions = "GIF,JPG,JPEG,BMP,PNG";
	$ppu->formName = "fm_criaalbum";
	$ppu->storeType = "file";
	$ppu->sizeLimit = "1000";
	$ppu->nameConflict = "uniq";
	$ppu->requireUpload = "true";
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

// Rename Uploaded Files Addon 1.0.3
if (isset($HTTP_GET_VARS['GP_upload'])) {
  $ruf = new renameUploadedFiles($ppu);
  $ruf->renameMask = "capa_album.jpg";
  $ruf->checkVersion("1.0.3");
  $ruf->doRename();
}

// Smart Image Processor 1.0.3
if (isset($HTTP_GET_VARS['GP_upload'])) {
  $sip = new resizeUploadedFiles($ppu);
  $sip->component = "GD";
  $sip->resizeImages = "true";
  $sip->aspectImages = "true";
  $sip->maxWidth = "100";
  $sip->maxHeight = "75";
  $sip->quality = "80";
  $sip->makeThumb = "false";
  $sip->pathThumb = "";
  $sip->aspectThumb = "true";
  $sip->naming = "suffix";
  $sip->suffix = "_small";
  $sip->maxWidthThumb = "";
  $sip->maxHeightThumb = "";
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

if ((isset($HTTP_POST_VARS["MM_insert"])) && ($HTTP_POST_VARS["MM_insert"] == "fm_criaalbum")) {
  $insertSQL = sprintf("INSERT INTO Albuns (Capa, `Desc`) VALUES (%s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['Capa'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['Desc'], "text"));

  mysql_select_db($database_conection, $conection);
  $Result1 = mysql_query($insertSQL, $conection) or die(mysql_error());

  $insertGoTo = "../albuns.php";
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
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
.style7 {
	font-size: 18px;
	font-family: Geneva, Arial, Helvetica, sans-serif;
}
-->
</style>
<script language='javascript' src='../../../ScriptLibrary/incPureUpload.js'></script>

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
    <td class="style7">Criando Álbum</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="10">&nbsp;</td>
    <td width="580">&nbsp;</td>
    <td width="10">&nbsp;</td>
  </tr>
  <tr>
    <td width="10">&nbsp;</td>
    <td width="580" align="center"><form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="fm_criaalbum" id="fm_criaalbum" onSubmit="checkFileUpload(this,'GIF,JPG,JPEG,BMP,PNG',true,1000,'','','','','','');showProgressWindow('fileCopyProgress.htm',300,100);return document.MM_returnValue">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="140" align="right"><span class="style5">Nome do Álbum:</span></td>
          <td width="10">&nbsp;</td>
          <td><input name="Desc" type="text" id="Desc" size="40" /></td>
        </tr>
        <tr>
          <td width="140" align="right"><span class="style5">Foto Capa do Álbum:</span></td>
          <td width="10">&nbsp;</td>
          <td><input name="Capa" type="file" id="Capa" onChange="checkOneFileUpload(this,'GIF,JPG,JPEG,BMP,PNG',true,1000,'','','','','','')" size="40" /></td>
        </tr>
        <tr>
          <td width="140" align="right"><span class="style6"></span></td>
          <td width="10">&nbsp;</td>
          <td><input type="submit" name="btn_criaalbum" id="btn_criaalbum" value="Criar Álbum" /></td>
        </tr>
      </table>
        <input type="hidden" name="MM_insert" value="fm_criaalbum">
    </form>
    </td>
    <td width="10">&nbsp;</td>
  </tr>
  <tr>
    <td width="10">&nbsp;</td>
    <td width="580">&nbsp;</td>
    <td width="10">&nbsp;</td>
  </tr>
  <tr>
    <td width="10">&nbsp;</td>
    <td width="580">&nbsp;</td>
    <td width="10">&nbsp;</td>
  </tr>
  <tr>
    <td width="10">&nbsp;</td>
    <td width="580">&nbsp;</td>
    <td width="10">&nbsp;</td>
  </tr>
  <tr>
    <td width="10">&nbsp;</td>
    <td width="580">&nbsp;</td>
    <td width="10">&nbsp;</td>
  </tr>
</table>
</body>
</html>
