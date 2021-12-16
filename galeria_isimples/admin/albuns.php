<?php require_once('../../Connections/conection.php'); ?>
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

mysql_select_db($database_conection, $conection);
$query_rsAlbuns = "SELECT * FROM Albuns ORDER BY IDAlbum DESC";
$rsAlbuns = mysql_query($query_rsAlbuns, $conection) or die(mysql_error());
$row_rsAlbuns = mysql_fetch_assoc($rsAlbuns);
$totalRows_rsAlbuns = mysql_num_rows($rsAlbuns);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
    <td width="10">&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td width="10">&nbsp;</td>
  </tr>
  <tr>
    <td width="10">&nbsp;</td>
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
    <td colspan="2" class="style7">Álbuns <span class="style6">(<?php echo $totalRows_rsAlbuns ?> Álbuns Criados)
        <?php if ($totalRows_rsAlbuns == 0) { // Show if recordset empty ?>
        <a href="albuns/criar_albuns.php">CRIAR ÁLBUM</a>
          <?php } // Show if recordset empty ?>
</span></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="10">&nbsp;</td>
    <td colspan="2" align="center">&nbsp;</td>
    <td width="10">&nbsp;</td>
  </tr>
  <?php if ($totalRows_rsAlbuns > 0) { // Show if recordset not empty ?>
    <?php do { ?>
      <tr>
        <td width="10">&nbsp;</td>
        <td width="280" align="left"><img src="../imagens/capa/<?php echo $row_rsAlbuns['Capa']; ?>" /> <span class="style5"><?php echo $row_rsAlbuns['Desc']; ?></span></td>
        <td width="280" align="center" class="style5"> <a href="fotos/incluir_foto.php?IDAlbum=<?php echo $row_rsAlbuns['IDAlbum']; ?>">Ver Fotos</a> / <a href="fotos/incluir_foto.php?IDAlbum=<?php echo $row_rsAlbuns['IDAlbum']; ?>">Adicionar Fotos</a> / <a href="albuns/editar_albuns.php?IDAlbum=<?php echo $row_rsAlbuns['IDAlbum']; ?>">Editar</a> / <a href="albuns/excluir_albuns.php?IDAlbum=<?php echo $row_rsAlbuns['IDAlbum']; ?>">Excluir</a></td>
        <td width="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="10">&nbsp;</td>
        <td colspan="2">&nbsp;</td>
        <td width="10">&nbsp;</td>
      </tr>
      <?php } while ($row_rsAlbuns = mysql_fetch_assoc($rsAlbuns)); ?>
    <?php } // Show if recordset not empty ?>

  <tr>
    <td width="10">&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td width="10">&nbsp;</td>
  </tr>
  <tr>
    <td width="10">&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td width="10">&nbsp;</td>
  </tr>
  <tr>
    <td width="10">&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td width="10">&nbsp;</td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($rsAlbuns);
?>
