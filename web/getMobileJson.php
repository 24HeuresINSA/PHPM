<?php
$files = scandir('up/jsonMobile', SCANDIR_SORT_DESCENDING);
$newestFile = $files[0];

if ($_GET['version'] && $_GET['version'] == intval($_GET['version']))
{
// Check version
    $latestVersion = substr($newestFile, '7', 14);

    if ($_GET['version'] != $latestVersion)
    {
        header('Content-Type: application/json');
        echo file_get_contents('up/jsonMobile/' . $newestFile);
    }
    else
    {
        header('HTTP/1.0 204 No Content');
    }
}
else
{
    header('Content-Type: application/json');
    echo file_get_contents('up/jsonMobile/' . $newestFile);
}
?>
