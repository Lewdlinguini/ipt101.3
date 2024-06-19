<?php
session_start();
require_once 'vendor/autoload.php';

// Include the database connection file
require_once 'db_conn.php';

$client = new Google\Client();
$client->setAuthConfig('cakes.json');
$client->setRedirectUri('http://localhost/ipt101.3/google-auth-callback.php');
$client->fetchAccessTokenWithAuthCode($_GET['code']);
$_SESSION['access_token'] = $client->getAccessToken();

// Use the existing database connection
global $conn;

// Use the access token to fetch user information
$service = new Google\Service\PeopleService($client);
$me = $service->people->get('people/me', ['personFields' => 'names,emailAddresses']);

// Extract user information
$userData = $me->getNames();
$email = $me->getEmailAddresses()[0]->getValue();
$firstName = $userData[0]->getGivenName();
$lastName = $userData[0]->getFamilyName();

// Check if the user already exists in the database
$stmt = $conn->prepare("SELECT * FROM `user` WHERE `Email` = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // User exists, update their information if needed
    $stmt = $conn->prepare("UPDATE `user` SET `First_name` = ?, `Lastname` = ? WHERE `Email` = ?");
    $stmt->bind_param("sss", $firstName, $lastName, $email);
    $stmt->execute();
} else {
    // User does not exist, insert new record
    $stmt = $conn->prepare("INSERT INTO `user` (`username`, `First_name`, `Lastname`, `Email`) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $email, $firstName, $lastName, $email);
    $stmt->execute();
}

$stmt->close();

// Redirect to your desired page after successful login
header('Location: index.php');
?>