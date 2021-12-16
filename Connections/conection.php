<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_conection = "127.0.0.1:3306";//local host
$database_conection = "galeria";//nome da tabela
$username_conection = "root";//usuario
$password_conection = "";//senha
$conection = mysql_pconnect($hostname_conection, $username_conection, $password_conection) or trigger_error(mysql_error(),E_USER_ERROR); 
?>