<?php
	$files = scandir('up/jsonMobile', SCANDIR_SORT_DESCENDING);
	$newestFile = $files[0];

	if ($__GET['version'] && $__GET['version'] == intval($__GET['version']))
	{
		// Check version
		$latestVersion = substr($newestFile, '7', 14);
		
		if ($__GET['version'] != $latestVersion)
		{
			header('Content-Type: application/json');
			echo file_get_contents('up/jsonMobile/' . $newestFile);
		}
		else
		{
			echo 'nothing new';
		}
	}
	else
	{
		header('Content-Type: application/json');
		echo file_get_contents('up/jsonMobile/' . $newestFile);
	}
?>
