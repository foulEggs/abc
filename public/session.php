<?php
session_start();
$_SESSION['openid'] = '123';
var_dump($_SESSION);