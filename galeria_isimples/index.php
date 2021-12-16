<?php require_once('../Connections/conection.php'); ?>
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

$maxRows_rsAlbum = 25;
$pageNum_rsAlbum = 0;
if (isset($_GET['pageNum_rsAlbum'])) {
  $pageNum_rsAlbum = $_GET['pageNum_rsAlbum'];
}
$startRow_rsAlbum = $pageNum_rsAlbum * $maxRows_rsAlbum;

mysql_select_db($database_conection, $conection);
$query_rsAlbum = "SELECT * FROM Albuns ORDER BY IDAlbum DESC";
$query_limit_rsAlbum = sprintf("%s LIMIT %d, %d", $query_rsAlbum, $startRow_rsAlbum, $maxRows_rsAlbum);
$rsAlbum = mysql_query($query_limit_rsAlbum, $conection) or die(mysql_error());
$row_rsAlbum = mysql_fetch_assoc($rsAlbum);

if (isset($_GET['totalRows_rsAlbum'])) {
  $totalRows_rsAlbum = $_GET['totalRows_rsAlbum'];
} else {
  $all_rsAlbum = mysql_query($query_rsAlbum);
  $totalRows_rsAlbum = mysql_num_rows($all_rsAlbum);
}
$totalPages_rsAlbum = ceil($totalRows_rsAlbum/$maxRows_rsAlbum)-1;
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>iSimples Slide</title>
<style type="text/css">
<!--
body {
	background-image: url(interface/gradtab.png);
	background-repeat: repeat-x;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #000000;
}
.style1 {
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-weight: bold;
	font-size: medium;
}
.style2 {
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #333333;
}
.style5 {font-size: 10px; color: #333333; }

.highslide img {
	border: 2px solid silver;
}
.highslide:hover img {
	border-color: gray;
}
.highslide-image {
	border-width: 2px;
	border-style: solid;
	border-color: black black #202020 black;
	background: gray;
}
-->
</style></head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="10">&nbsp;</td>
    <td>&nbsp;</td>
    <td width="10">&nbsp;</td>
  </tr>
  <tr>
    <td width="10">&nbsp;</td>
    <td align="center"><span class="style1">CLIQUE NO ÁLBUM QUE DESEJA VER AS FOTOS</span></td>
    <td width="10">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center"><table border="0">
      <tr>
        <?php
do { // horizontal looper version 3
?>
          <td align="center"><a href="fotos.php?IDAlbum=<?php echo $row_rsAlbum['IDAlbum']; ?>"><img src="imagens/capa/<?php echo $row_rsAlbum['Capa']; ?>" border="0" class="highslide-image" /></a><a href="fotos.php?IDAlbum=<?php echo $row_rsAlbum['IDAlbum']; ?>"></a><a href="fotos.php?IDAlbum=<?php echo $row_rsAlbum['IDAlbum']; ?>"><br />
            </a><span class="style2"><a href="fotos.php?IDAlbum=<?php echo $row_rsAlbum['IDAlbum']; ?>" class="style2"><?php echo $row_rsAlbum['Desc']; ?></a></span><span class="style5"><a href="fotos.php?IDAlbum=<?php echo $row_rsAlbum['IDAlbum']; ?>"></a></span><a href="fotos.php?IDAlbum=<?php echo $row_rsAlbum['IDAlbum']; ?>"><br />
            </a></td>
          <?php
$row_rsAlbum = mysql_fetch_assoc($rsAlbum);
    if (!isset($nested_rsAlbum)) {
      $nested_rsAlbum= 1;
    }
    if (isset($row_rsAlbum) && is_array($row_rsAlbum) && $nested_rsAlbum++ % 5==0) {
      echo "</tr><tr>";
    }
  } while ($row_rsAlbum); //end horizontal looper version 3
?>
      </tr>
    </table></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="10">&nbsp;</td>
    <td>&nbsp;</td>
    <td width="10">&nbsp;</td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($rsAlbum);
?>
