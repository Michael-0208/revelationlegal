<?php
// recursive directory scan

function recursiveScan($dir) {
	$tree = glob(rtrim($dir, '/') . '/*');
    if (is_array($tree)) {
        foreach($tree as $file) {
            if (is_dir($file)) {
                recursiveScan($file);
            }elseif (is_file($file)) {
				if( strpos(file_get_contents($file),"http://staging.revelationlegal.com/public") !== false) {  
					echo $file . '<br/>';
				}
            }
        }  
    }
}
$dir= __dir__;
recursiveScan(__dir__);
?>