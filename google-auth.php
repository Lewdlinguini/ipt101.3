<?php
require_once 'vendor/autoload.php';

$client = new Google\Client();
$client->setAuthConfig('cakes.json');
$client->addScope(Google\Service\PeopleService::USERINFO_PROFILE);
$client->addScope(Google\Service\PeopleService::USERINFO_EMAIL);
$client->setRedirectUri('http://localhost/ipt101.3/google-auth-callback.php');

if (!isset($_GET['code'])) {
    $auth_url = $client->createAuthUrl();
    header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
} else {
    $client->authenticate($_GET['code']);
    $_SESSION['access_token'] = $client->getAccessToken();
    header('Location: index.php'); // Redirect to your desired page after successful login
}
