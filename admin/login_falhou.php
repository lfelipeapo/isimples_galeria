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
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['Usuario'])) {
  $loginUsername=$_POST['Usuario'];
  $password=$_POST['Senha'];
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "index.php";
  $MM_redirectLoginFailed = "login_falhou.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_conection, $conection);
  
  $LoginRS__query=sprintf("SELECT Usuario, Senha FROM `Admin` WHERE Usuario=%s AND Senha=%s",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $conection) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Login</title>
<style type="text/css">
<!--
.style5 {font-family: Arial, Helvetica, sans-serif; font-size: 10px; }
.style6 {font-size: 10px}
.style7 {font-size: 18px;
	font-family: Geneva, Arial, Helvetica, sans-serif;
}
body {
	background-color: #FDB813;
}
-->
</style>
</head>

<body>
<table width="22%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  
  <tr>
    <td>&nbsp;</td>
    <td colspan="2" align="center" class="style7">Login Falhou!</td>
    <td width="13">&nbsp;</td>
  </tr>
  <tr>
    <td width="6">&nbsp;</td>
    <td colspan="2" align="center">&nbsp;</td>
    <td width="13">&nbsp;</td>
  </tr>
  
  <tr>
    <td width="6">&nbsp;</td>
    <td colspan="2"><form ACTION="<?php echo $loginFormAction; ?>" id="fm_atua_dados" name="fm_atua_dados" method="POST">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="140" align="right"><span class="style5">Usuário:</span></td>
          <td width="10">&nbsp;</td>
          <td><input name="Usuario" type="text" id="Usuario" /></td>
        </tr>
        <tr>
          <td width="140" align="right"><span class="style5">Senha:</span></td>
          <td width="10">&nbsp;</td>
          <td><input name="Senha" type="text" id="Senha" />          </td>
        </tr>
        <tr>
          <td width="140" align="right"><span class="style6">
            <input name="IDAdmin" type="hidden" id="IDAdmin" />
          </span></td>
          <td width="10">&nbsp;</td>
          <td><input type="submit" name="btn_editarfoto" id="btn_editarfoto" value="Entrar" /></td>
        </tr>
      </table>
            
    </form>    </td>
    <td width="13">&nbsp;</td>
  </tr>

  <tr>
    <td width="6">&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td width="13">&nbsp;</td>
  </tr>
</table>
</body>
</html>