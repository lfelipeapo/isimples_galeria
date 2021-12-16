<?php
// --- Pure PHP Upload Add On Pack ----------------------------------------------
// Copyright 2003 (c) George Petrov, Patrick Woldberg, www.DMXzone.com
//
// Version: 1.0.3
// ------------------------------------------------------------------------------

// Delete the file if the corresponding database record is being deleted
class deleteFileBeforeRecord
{
	var $version = '1.0.3';
	var $debugger = false;
	
	var $naming;
	var $suffix;
	var $field;
	var $path;
	var $pathThumb;
	var $sqldata;
	
	function deleteFileBeforeRecord() {
		global $DMX_debug;
		$this->fieldName = array();
		$this->sqldata = array();
		$this->debugger = $DMX_debug;
		$this->debug("<br/><font color=\"#009900\"><b>Delete File Before Record version ".$this->version."</b></font><br/><br/>");
	}
	
	// Check if version is uptodate
	function checkVersion($version) {
		if ($version < $this->version) {
			$this->error('version');
		}
	}
	
	function deleteFile() {
		global $HTTP_SERVER_VARS;

		$this->debug("PHP version(<font color=\"#990000\">".phpversion()."</font>)<br/>");
		$this->debug("naming(<font color=\"#990000\">".$this->naming."</font>)<br/>");
		$this->debug("suffix(<font color=\"#990000\">".$this->suffix."</font>)<br/>");
		$this->debug("path(<font color=\"#990000\">".$this->path."</font>)<br/>");
		$this->debug("pathThumb(<font color=\"#990000\">".$this->pathThumb."</font>)<br/>");
		
		foreach ($this->sqldata as $field) {
			// Get filename
			$fileName = $field;
			$this->debug("fileName = <font color=\"#000099\"><b>".$fileName."</b></font><br/>");

			// Reconstruct thumbnail name
			$pos = strrpos($fileName, "/");
			$name = substr($fileName, $pos, strrpos($fileName, ".")-$pos);
			if ($this->naming == "prefix") {
				$thumbnail = $this->suffix.$name.".jpg";
			} else {
				$thumbnail = $name.$this->suffix.".jpg";
			}
			$this->debug("thumbnail = <font color=\"#000099\"><b>".$thumbnail."</b></font><br/>");
			
			if ($this->path <> '') {
				// If there is an path given the find file at given path
				$this->debug("Check if <b>".$this->path."/".$fileName."</b> exists<br/>");
				if (file_exists($this->path.'/'.$fileName)) {
					$this->debug("Deleting <b>".$this->path."/".$fileName."</b><br/>");
					if (!unlink($this->path.'/'.$fileName)) {
						$this->error('delete', $fileName);
					}
				}
				if ($this->pathThumb == "") {
					$this->pathThumb = $this->path;
				}
				// Delete thumbnail if it exists
				$this->debug("Check if <b>".$this->pathThumb."/".$thumbnail."</b> exists<br/>");
				if (file_exists($this->pathThumb."/".$thumbnail)) {
					$this->debug("Deleting <b>".$this->pathThumb."/".$thumbnail."</b><br/>");
					if (!unlink($this->pathThumb."/".$thumbnail)) {
						$this->error('delete', $thumbnail);
					}
				}
			} else {
				$this->debug("<b>No path was given, using path from DB</b><br/>");
				// Create the absolute path to the file
				$absPath = $HTTP_SERVER_VARS['PATH_TRANSLATED'];
				$absPath = ereg_replace("[\\]", "/", $absPath);
				$absPath = ereg_replace("//", "/", $absPath);
				$absPath = ereg_replace($HTTP_SERVER_VARS['PHP_SELF'], "", $absPath);
				$absPath .= '/'.substr($fileName, 1, strrpos($fileName, '/'));
				$this->debug("absPath = <font color=\"#000099\"><b>".$absPath."</b></font><br/>");
				
				// Get filename without the path
				$fileName = substr($fileName, strrpos($fileName, '/')+1);
				$this->debug("fileName = <font color=\"#000099\"><b>".$fileName."</b></font><br/>");
				// Delete file if file exists
				$this->debug("Check if <b>".$absPath.$fileName."</b> exists<br/>");
				if (file_exists($absPath.$fileName)) {
					$this->debug("Deleting <b>".$absPath.$fileName."</b><br/>");
					if (!unlink($absPath.$fileName)) {
						$this->error('delete', $fileName);
					}
				}
				if ($this->pathThumb <> "") {
					$absPath = $this->pathThumb;
				}
				// Delete thumbnail if it exists
				$this->debug("Check if <b>".$absPath.$thumbnail."</b> exists<br/>");
				if (file_exists($absPath.$thumbnail)) {
					$this->debug("Deleting <b>".$absPath.$thumbnail."</b><br/>");
					if (!unlink($absPath.$thumbnail)) {
						$this->error('delete', $thumbnail);
					}
				}
			}
		}
	}
	
	// Debugger
	function debug($info) {
		if ($this->debugger) {
			echo "<font face=\"verdana\" size=\"2\">".$info."</font>";
		}
	}

	function error($error, $extra) {
		// Display error
		echo "<b>Error deleting file</b><br/><br/>";

		switch ($error) {
		// Error renaming the file
		case 'delete':
			echo "The delete function generated an error on file ".$extra."<br/>";
			break;
		}
		
		// Allow to go back and stop the script
		echo "Please correct and <a href=\"javascript:history.back(1)\">try again</a>";
		$this->upload->failUpload();
		exit;
	}
}
?>