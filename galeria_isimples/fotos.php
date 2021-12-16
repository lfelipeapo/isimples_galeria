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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rsFotos = 16;
$pageNum_rsFotos = 0;
if (isset($_GET['pageNum_rsFotos'])) {
  $pageNum_rsFotos = $_GET['pageNum_rsFotos'];
}
$startRow_rsFotos = $pageNum_rsFotos * $maxRows_rsFotos;

$colname_rsFotos = "-1";
if (isset($_GET['IDAlbum'])) {
  $colname_rsFotos = $_GET['IDAlbum'];
}
mysql_select_db($database_conection, $conection);
$query_rsFotos = sprintf("SELECT * FROM Fotos WHERE IDAlbum = %s", GetSQLValueString($colname_rsFotos, "text"));
$query_limit_rsFotos = sprintf("%s LIMIT %d, %d", $query_rsFotos, $startRow_rsFotos, $maxRows_rsFotos);
$rsFotos = mysql_query($query_limit_rsFotos, $conection) or die(mysql_error());
$row_rsFotos = mysql_fetch_assoc($rsFotos);

if (isset($_GET['totalRows_rsFotos'])) {
  $totalRows_rsFotos = $_GET['totalRows_rsFotos'];
} else {
  $all_rsFotos = mysql_query($query_rsFotos);
  $totalRows_rsFotos = mysql_num_rows($all_rsFotos);
}
$totalPages_rsFotos = ceil($totalRows_rsFotos/$maxRows_rsFotos)-1;

$colname_rsAlbum = "-1";
if (isset($_GET['IDAlbum'])) {
  $colname_rsAlbum = $_GET['IDAlbum'];
}
mysql_select_db($database_conection, $conection);
$query_rsAlbum = sprintf("SELECT * FROM Albuns WHERE IDAlbum = %s", GetSQLValueString($colname_rsAlbum, "int"));
$rsAlbum = mysql_query($query_rsAlbum, $conection) or die(mysql_error());
$row_rsAlbum = mysql_fetch_assoc($rsAlbum);
$totalRows_rsAlbum = mysql_num_rows($rsAlbum);

$maxRows_rsAlbuns = 10;
$pageNum_rsAlbuns = 0;
if (isset($_GET['pageNum_rsAlbuns'])) {
  $pageNum_rsAlbuns = $_GET['pageNum_rsAlbuns'];
}
$startRow_rsAlbuns = $pageNum_rsAlbuns * $maxRows_rsAlbuns;

mysql_select_db($database_conection, $conection);
$query_rsAlbuns = "SELECT * FROM Albuns ORDER BY IDAlbum ASC";
$query_limit_rsAlbuns = sprintf("%s LIMIT %d, %d", $query_rsAlbuns, $startRow_rsAlbuns, $maxRows_rsAlbuns);
$rsAlbuns = mysql_query($query_limit_rsAlbuns, $conection) or die(mysql_error());
$row_rsAlbuns = mysql_fetch_assoc($rsAlbuns);

if (isset($_GET['totalRows_rsAlbuns'])) {
  $totalRows_rsAlbuns = $_GET['totalRows_rsAlbuns'];
} else {
  $all_rsAlbuns = mysql_query($query_rsAlbuns);
  $totalRows_rsAlbuns = mysql_num_rows($all_rsAlbuns);
}
$totalPages_rsAlbuns = ceil($totalRows_rsAlbuns/$maxRows_rsAlbuns)-1;

$queryString_rsAlbuns = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsAlbuns") == false && 
        stristr($param, "totalRows_rsAlbuns") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsAlbuns = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsAlbuns = sprintf("&totalRows_rsAlbuns=%d%s", $totalRows_rsAlbuns, $queryString_rsAlbuns);

$queryString_rsFotos = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsFotos") == false && 
        stristr($param, "totalRows_rsFotos") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsFotos = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsFotos = sprintf("&totalRows_rsFotos=%d%s", $totalRows_rsFotos, $queryString_rsFotos);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Galeria iSimples</title>
<!-- 
	1 ) Reference to the file containing the javascript. 
	This file must be located on your server. 
-->
<script type="text/javascript" src="highslide/highslide-with-gallery.js"></script>

<!-- 
	2) Optionally override the settings defined at the top
	of the highslide.js file. The parameter hs.graphicsDir is important!
-->

<script type="text/javascript">
	hs.graphicsDir = 'highslide/graphics/';
	hs.align = 'center';
	hs.transitions = ['expand', 'crossfade'];
	hs.outlineType = 'glossy-dark';
	hs.fadeInOut = true;
	//hs.dimmingOpacity = 0.75;
	
	// Add the controlbar
	if (hs.addSlideshow) hs.addSlideshow({
		//slideshowGroup: 'group1',
		interval: 5000,
		repeat: false,
		useControls: true,
		fixedControls: true,
		overlayOptions: {
			opacity: .6,
			position: 'top center',
			hideOnMouseOut: true
		}
	});
</script>


<!-- 
	3) These CSS-styles are necessary for the script to work. You may also put
	them in an external CSS-file. See the webpage for documentation.
-->



<style type="text/css">

.highslide-wrapper div {
	font-family: Verdana, Helvetica;
	font-size: 10pt;
}
.highslide {
	cursor: url(highslide/graphics/zoomin.cur) , pointer;
	outline: none;
	text-decoration: none;
	padding: 40px;
}
.highslide img {
	border: 2px solid silver;
}
.highslide:hover img {
	border-color: gray;
}

.highslide-active-anchor img, .highslide-active-anchor:hover img {
	border-color: black;
}
.highslide-image {
	border-width: 2px;
	border-style: solid;
	border-color: black black #202020 black;
	background: gray;
}
.highslide-wrapper, .glossy-dark {
	background: #111;
}
.highslide-image-blur {
}
.highslide-caption {
	display: none;
	border-top: none;
	font-size: 1em;
	padding: 5px;
	color: white;
	background: #111;
}
.highslide-heading {
	display: none;
	color: white;
	font-size: 1.2em;
	font-weight: bold;
	margin-bottom: 0.4em;
}
.highslide-dimming {
	position: absolute;
	background: black;
}
.highslide-loading {
	display: block;
	color: white;
	font-size: 9px;
	font-weight: bold;
	text-transform: uppercase;
	text-decoration: none;
	padding: 3px;
	border-top: 1px solid white;
	border-bottom: 1px solid white;
	background-color: black;
	/*
	padding-left: 22px;
	background-image: url(highslide/graphics/loader.gif);
	background-repeat: no-repeat;
	background-position: 3px 1px;
	*/
}
a.highslide-credits,
a.highslide-credits i {
	padding: 2px;
	color: silver;
	text-decoration: none;
	font-size: 10px;
}
a.highslide-credits:hover,
a.highslide-credits:hover i {
	color: white;
	background-color: gray;
}
.highslide-move a {
	cursor: move;
}


/* Controls. See http://www.google.com/search?q=css+sprites */
.highslide-controls {
	width: 195px;
	height: 40px;
	background: url(highslide/graphics/controlbar-black-border.gif) 0 -90px no-repeat;
	margin-right: 15px;
	margin-bottom: 10px;
	margin-top: 20px;
}
.highslide-controls ul {
	position: relative;
	left: 15px;
	height: 40px;
	list-style: none;
	margin: 0;
	padding: 0;
	background: url(highslide/graphics/controlbar-black-border.gif) right -90px no-repeat;
}
.highslide-controls li {
	float: left;
	padding: 5px 0;
}
.highslide-controls a {
	background: url(highslide/graphics/controlbar-black-border.gif);
	display: block;
	float: left;
	height: 30px;
	width: 30px;
	outline: none;
}
.highslide-controls a.disabled {
	cursor: default;
}
.highslide-controls a span {
	/* hide the text for these graphic buttons */
	display: none;
}

/* The CSS sprites for the controlbar */
.highslide-controls .highslide-previous a {
	background-position: 0 0;
}
.highslide-controls .highslide-previous a:hover {
	background-position: 0 -30px;
}
.highslide-controls .highslide-previous a.disabled {
	background-position: 0 -60px !important;
}
.highslide-controls .highslide-play a {
	background-position: -30px 0;
}
.highslide-controls .highslide-play a:hover {
	background-position: -30px -30px;
}
.highslide-controls .highslide-play a.disabled {
	background-position: -30px -60px !important;
}
.highslide-controls .highslide-pause a {
	background-position: -60px 0;
}
.highslide-controls .highslide-pause a:hover {
	background-position: -60px -30px;
}
.highslide-controls .highslide-next a {
	background-position: -90px 0;
}
.highslide-controls .highslide-next a:hover {
	background-position: -90px -30px;
}
.highslide-controls .highslide-next a.disabled {
	background-position: -90px -60px !important;
}
.highslide-controls .highslide-move a {
	background-position: -120px 0;
}
.highslide-controls .highslide-move a:hover {
	background-position: -120px -30px;
}
.highslide-controls .highslide-full-expand a {
	background-position: -150px 0;
}
.highslide-controls .highslide-full-expand a:hover {
	background-position: -150px -30px;
}
.highslide-controls .highslide-full-expand a.disabled {
	background-position: -150px -60px !important;
}
.highslide-controls .highslide-close a {
	background-position: -180px 0;
}
.highslide-controls .highslide-close a:hover {
	background-position: -180px -30px;
} 
.style1 {
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 24px;
	margin-left: 30px;
}
body {
	background-image: url(interface/gradtab.png);
	background-color: #000000;
	background-repeat: repeat-x;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
.style2 {color: #FFFFFF}
.bdTab {
	border: 1px solid #463305;
	margin: 30px;
}
.style3 {
	font-family: Geneva, Arial, Helvetica, sans-serif;
	color: #FFFFFF;
	font-size: 10px;
}
.style3 a:link, .style3 a:active, .style3 a:visited  {
	font-family: Geneva, Arial, Helvetica, sans-serif;
	color: #FFFFFF;
	font-size: 10px;
	text-decoration: none;
}
.style3 a:hover {
	font-family: Geneva, Arial, Helvetica, sans-serif;
	color: #FFFFFF;
	font-size: 10px;
		text-decoration: none;
}
.style4 {
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 10px;
}
</style>

</head>

<body>
<p>&nbsp;</p>
<table width="919" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="799">&nbsp;</td>
    <td width="10">&nbsp;</td>
    <td width="116" align="center" class="highslide-full-expand style4">+ Álbuns</td>
  </tr>
  <tr>
    <td valign="top"><table width="769" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#463305" class="bdTab">
      <tr>
        <td height="65" colspan="3" background="interface/backfotos.png"><p class="style1 style2"><?php echo $row_rsAlbum['Desc']; ?><a href="index.php" class="style3"></a><br />
        </p></td>
      </tr>
      <tr>
        <td width="10" bgcolor="#896C25">&nbsp;</td>
        <td bgcolor="#896C25">&nbsp;</td>
        <td width="10" bgcolor="#896C25">&nbsp;</td>
      </tr>
      <?php if ($totalRows_rsFotos > 0) { // Show if recordset not empty ?>
        <tr>
          <td colspan="3" bgcolor="#896C25"><table width="46" border="0" align="center">
              <tr>
                <?php
  do { // horizontal looper version 3
?>
                  <td><a href="imagens/<?php echo $row_rsFotos['Foto']; ?>" class="highslide" onclick="return hs.expand(this)"> <img src="imagens/minis/<?php echo $row_rsFotos['Foto']; ?>" alt="<?php echo $row_rsFotos['Desc']; ?>" border="0" 
		title="Clique para ampliar" /></a></td>
                  <?php
    $row_rsFotos = mysql_fetch_assoc($rsFotos);
    if (!isset($nested_rsFotos)) {
      $nested_rsFotos= 1;
    }
    if (isset($row_rsFotos) && is_array($row_rsFotos) && $nested_rsFotos++ % 4==0) {
      echo "</tr><tr>";
    }
  } while ($row_rsFotos); //end horizontal looper version 3
?>
              </tr>
          </table></td>
        </tr>
        <?php } // Show if recordset not empty ?>
      <?php if ($totalRows_rsFotos == 0) { // Show if recordset empty ?>
        <tr>
          <td bgcolor="#896C25">&nbsp;</td>
          <td align="center" bgcolor="#896C25" class="style4">Este álbum ainda não tem fotos!</td>
          <td bgcolor="#896C25">&nbsp;</td>
        </tr>
        <?php } // Show if recordset empty ?>

      <tr bgcolor="#896C25">
        <td height="65" colspan="3" align="center">&nbsp;
          <table border="0">
            <tr>
              <td><?php if ($pageNum_rsFotos > 0) { // Show if not first page ?>
                    <a href="<?php printf("%s?pageNum_rsFotos=%d%s", $currentPage, 0, $queryString_rsFotos); ?>"><img src="First.gif" border="0" /></a>
                    <?php } // Show if not first page ?>
              </td>
              <td><?php if ($pageNum_rsFotos > 0) { // Show if not first page ?>
                    <a href="<?php printf("%s?pageNum_rsFotos=%d%s", $currentPage, max(0, $pageNum_rsFotos - 1), $queryString_rsFotos); ?>"><img src="Previous.gif" border="0" /></a>
                    <?php } // Show if not first page ?>
              </td>
              <td><?php if ($pageNum_rsFotos < $totalPages_rsFotos) { // Show if not last page ?>
                    <a href="<?php printf("%s?pageNum_rsFotos=%d%s", $currentPage, min($totalPages_rsFotos, $pageNum_rsFotos + 1), $queryString_rsFotos); ?>"><img src="Next.gif" border="0" /></a>
                    <?php } // Show if not last page ?>
              </td>
              <td><?php if ($pageNum_rsFotos < $totalPages_rsFotos) { // Show if not last page ?>
                    <a href="<?php printf("%s?pageNum_rsFotos=%d%s", $currentPage, $totalPages_rsFotos, $queryString_rsFotos); ?>"><img src="Last.gif" border="0" /></a>
                    <?php } // Show if not last page ?>
              </td>
            </tr>
          </table></td>
      </tr>
    </table></td>
    <td width="10">&nbsp;</td><br />
    <td width="116" align="center" valign="top"><p>
      <?php do { ?>
          <a href="fotos.php?IDAlbum=<?php echo $row_rsAlbuns['IDAlbum']; ?>"><img src="imagens/capa/<?php echo $row_rsAlbuns['Capa']; ?>" border="0" class="highslide-image" /></a><br />
        <span class="style4"><?php echo $row_rsAlbuns['Desc']; ?><br />
          </span><br />

        <?php } while ($row_rsAlbuns = mysql_fetch_assoc($rsAlbuns)); ?>
        </p>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td width="10">&nbsp;</td>
    <td width="116" align="center"><table border="0">
      <tr>
        <td><?php if ($pageNum_rsAlbuns > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_rsAlbuns=%d%s", $currentPage, 0, $queryString_rsAlbuns); ?>"><img src="First.gif" border="0" /></a>
            <?php } // Show if not first page ?>
        </td>
        <td><?php if ($pageNum_rsAlbuns > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_rsAlbuns=%d%s", $currentPage, max(0, $pageNum_rsAlbuns - 1), $queryString_rsAlbuns); ?>"><img src="Previous.gif" border="0" /></a>
            <?php } // Show if not first page ?>
        </td>
        <td><?php if ($pageNum_rsAlbuns < $totalPages_rsAlbuns) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_rsAlbuns=%d%s", $currentPage, min($totalPages_rsAlbuns, $pageNum_rsAlbuns + 1), $queryString_rsAlbuns); ?>"><img src="Next.gif" border="0" /></a>
            <?php } // Show if not last page ?>
        </td>
        <td><?php if ($pageNum_rsAlbuns < $totalPages_rsAlbuns) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_rsAlbuns=%d%s", $currentPage, $totalPages_rsAlbuns, $queryString_rsAlbuns); ?>"><img src="Last.gif" border="0" /></a>
            <?php } // Show if not last page ?>
        </td>
      </tr>
    </table>
       
<table border="0" class="highslide-caption">
        <tr>
          <td><?php if ($pageNum_rsAlbuns > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_rsAlbuns=%d%s", $currentPage, 0, $queryString_rsAlbuns); ?>">First</a>
              <?php } // Show if not first page ?>          </td>
          <td><?php if ($pageNum_rsAlbuns > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_rsAlbuns=%d%s", $currentPage, max(0, $pageNum_rsAlbuns - 1), $queryString_rsAlbuns); ?>">Previous</a>
              <?php } // Show if not first page ?>          </td>
          <td><?php if ($pageNum_rsAlbuns < $totalPages_rsAlbuns) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_rsAlbuns=%d%s", $currentPage, min($totalPages_rsAlbuns, $pageNum_rsAlbuns + 1), $queryString_rsAlbuns); ?>">Next</a>
              <?php } // Show if not last page ?>          </td>
          <td><?php if ($pageNum_rsAlbuns < $totalPages_rsAlbuns) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_rsAlbuns=%d%s", $currentPage, $totalPages_rsAlbuns, $queryString_rsAlbuns); ?>">Last</a>
              <?php } // Show if not last page ?>          </td>
        </tr>
      </table></td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsFotos);

mysql_free_result($rsAlbum);

mysql_free_result($rsAlbuns);
?>
