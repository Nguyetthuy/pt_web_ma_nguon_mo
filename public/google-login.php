<?php
$config = require __DIR__ . '/../config/config.php';
$googleConfig = $config['google'];

$clientID = $googleConfig['client_id'];
$redirectURI = $googleConfig['redirect_uri'];

$loginURL = "https://accounts.google.com/o/oauth2/auth?response_type=code"
    . "&client_id=" . $clientID
    . "&redirect_uri=" . urlencode($redirectURI)
    . "&scope=email%20profile";

header("Location: $loginURL");
exit;
