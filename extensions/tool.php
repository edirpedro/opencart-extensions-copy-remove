<?php

/*
*  OpenCart Extensions Copy & Remove
*
*  @description: This tool makes easier to install and remove files from an OpenCart extension.
*  @created: 15/08/14
*  @author: Edir Pedro
*  @website: http://edirpedro.com.br
*/

Class Tool {

	// Folder where to find the extensions
	private $extensions = '/extensions'; 

	// OpenCart installation
	private $opencart = '/';
	
	function __construct() {
		$this->extensions = dirname(__FILE__);
		$this->opencart = dirname(dirname(__FILE__));		
	}

	// Copy extension files to OpenCart installation
	function install($extension) {
		$extension = $this->extensions . $extension;
		$files = $this->get_files($extension);
		
		foreach($files as $file) {
			$dest = str_replace($extension, $this->opencart, $file);
			$dir = dirname($dest);
			
			if(!is_dir($dir))
				mkdir($dir, 0777, true);
			
			if(@copy($file, $dest))
				echo str_replace($this->extensions, '', $file) . '<br>';
			else
				echo '<mark>' . str_replace($this->extensions, '', $file) . '</mark><br>';
		}
	}
	
	// Remove extension files from OpenCart installation
	function remove($extension) {
		$extension = $this->extensions . $extension;
		$files = $this->get_files($extension);
		
		foreach($files as $file) {
			$dest = str_replace($extension, $this->opencart, $file);
			$dir = dirname($dest);
						
			if(@unlink($dest))
				echo str_replace($this->opencart, '', $dest) . '<br>';
			else
				echo '<mark>' . str_replace($this->opencart, '', $dest) . '</mark><br>';
			
			$dir_status = glob($dir.'/*');
			if(empty($dir_status))
				@rmdir($dir);	
		}
	}
	
	// Compare the files in extension folder with OpenCart installation
	function check($extension) {
		$extension = $this->extensions . $extension;
		$files = $this->get_files($extension);
		
		foreach($files as $file) {
			$file = str_replace($extension, $this->opencart, $file);
			if(file_exists($file))
				echo '<mark>' . str_replace($this->opencart, '', $file) . '</mark><br>';
			else
				echo str_replace($this->opencart, '', $file) . '<br>';
		}
	}
	
	// Return a list of files inside directory extension
	function get_files($folder) {
		$output = array();
		foreach(scandir($folder) as $file) {
			if(substr($file, 0, 1) == '.')
				continue;
			
			if(is_dir("$folder/$file"))
				$output = array_merge($output, $this->get_files("$folder/$file"));
			else
				$output[] = "$folder/$file";
		}
		return $output;
	}

}

// Interface
$extensions = glob(dirname(__FILE__).'/*', GLOB_ONLYDIR);
$current_extension = isset($_POST['extension']) ? $_POST['extension'] : null;
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>OpenCart Extensions</title>
	
	<style>
	body {
		font: 12px Arial, helvetica, sans-serif;
	}
	</style>
</head>
<body>

<h1>OpenCart Extensions<br><small>EASY COPY & REMOVE</small></h1>

<form action="" method="post">
<label>Extension: 
	<select name="extension">
		<option></option>
		<?php
		foreach($extensions as $dir) : 
			$extension = str_replace(dirname(__FILE__), '', $dir);
		?>
		<option value="<?php echo $extension ?>" <?php echo $current_extension == $extension ? 'selected="selected"' : null ?>><?php echo $extension ?></option>
		<?php endforeach; ?>
	</select></label>
	<input type="submit" name="check" value="Check">
	<input type="submit" name="install" value="Install" onclick="javascript:return confirm('Are you sure to Install?');">
	<input type="submit" name="remove" value="Remove" onclick="javascript:return confirm('Are you sure to Remove?');">
</form>

<pre><code>
<?php
if(!empty($current_extension)) {
	$tool = new Tool();
	
	if(isset($_POST['check'])) {
		echo "Checking ...\n";
		echo "Files marked in yellow exists in your OpenCart installation and will be overwritten when install.\n\n";
		$tool->check($current_extension);
	
	} elseif(isset($_POST['install'])) {
		echo "Installing ...\n";
		echo "Files marked in yellow got an error.\n\n";
		$tool->install($current_extension);

	} elseif(isset($_POST['remove'])) {
		echo "Removing ...\n";
		echo "Files marked in yellow wasn't found to be removed in your OpenCart installation.\n\n";
		$tool->remove($current_extension);
	}
			
}
?>
</code></pre>

<cite><b>WARNING!</b> Don't keep this script in your site production.</cite>

</body>
</html>
