<?php
$homePage = $_SERVER['HTTP_HOST'];
$homePage = "http://".$homePage;
header("Location: $homePage");
exit;