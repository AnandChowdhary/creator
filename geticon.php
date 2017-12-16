<?php
    require_once 'icons.php';
    $key  = "916fb3e836af4a0e8e24def326c37c43";
    $secret = "1";
    $theNounProject = (new TheNounProject($key, $secret));
    $icons = $theNounProject->getIconsByTerm(
        'happy',
        array('limit' => 10)
    );
    print_r($icons);
	exit(0);
?>