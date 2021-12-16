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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "fm_atua_dados")) {
  $updateSQL = sprintf("UPDATE `Admin` SET Usuario=%s, Senha=%s WHERE IDAdmin=%s",
                       GetSQLValueString($_POST['Usuario'], "text"),
                       GetSQLValueString($_POST['Senha'], "text"),
                       GetSQLValueString($_POST['IDAdmin'], "int"));

  mysql_select_db($database_conection, $conection);
  $Result1 = mysql_query($updateSQL, $conection) or die(mysql_error());

  $updateGoTo = "editar_usuario_ok.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_conection, $conection);
$query_rsAdmin = "SELECT * FROM `Admin` WHERE IDAdmin = 1";
$rsAdmin = mysql_query($query_rsAdmin, $conection) or die(mysql_error());
$row_rsAdmin = mysql_fetch_assoc($rsAdmin);
$totalRows_rsAdmin = mysql_num_rows($rsAdmin);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style type="text/css">
<!--
.style5 {font-family: Arial, Helvetica, sans-serif; font-size: 10px; }
.style6 {font-size: 10px}
.style7 {font-size: 18px;
	font-family: Geneva, Arial, Helvetica, sans-serif;
}
.style8 {
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #FF0000;
	font-weight: bold;
}
.style9 {
	font-size: 12px;
	color: #333333;
	font-weight: bold;
}
-->
</style>
</head>

<body>
<table width="580" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="10">&nbsp;</td>
    <td width="560" colspan="2">&nbsp;</td>
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
    <td colspan="2" class="style7">Editar senha de Acesso <span class="style9">(Aqui altera-se a senha de acesso a Administração)</span></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="10">&nbsp;</td>
    <td colspan="2" align="center">&nbsp;</td>
    <td width="10">&nbsp;</td>
  </tr>
  
  <tr>
    <td width="10">&nbsp;</td>
    <td colspan="2"><form action="<?php echo $editFormAction; ?>" id="fm_atua_dados" name="fm_atua_dados" method="POST">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="140" align="right"><span class="style5">Usuário:</span></td>
          <td width="10">&nbsp;</td>
          <td><input name="Usuario" type="text" id="Usuario" readonly="readonly" value="<?php echo $row_rsAdmin['Usuario']; ?>" />
            <input name="IDAdmin" type="hidden" id="IDAdmin"  value="<?php echo $row_rsAdmin['IDAdmin']; ?>" /></td>
        </tr>
        <tr>
          <td width="140" align="right"><span class="style5">Senha:</span></td>
          <td width="10">&nbsp;</td>
          <td><input name="Senha" type="text" id="Senha" readonly="readonly" value="<?php echo $row_rsAdmin['Senha']; ?>" />
          </td>
        </tr>
        <tr>
          <td width="140" align="right"><span class="style6"></span></td>
          <td width="10">&nbsp;</td>
          <td><input type="submit" name="btn_editarfoto" id="btn_editarfoto" value="Atualizar Dados" /></td>
        </tr>
      </table>
            
      <input type="hidden" name="MM_update" value="fm_atua_dados" />
</form>
    </td>
    <td width="10">&nbsp;</td>
  </tr>
<tr>
    <td width="10">&nbsp;</td>
    <td colspan="2" align="center"><span class="style8">Não é possível alterar os dados na versão de demonstração!</span></td>
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
mysql_free_result($rsAdmin);
?>