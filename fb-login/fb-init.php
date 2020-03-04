<?php
require_once __DIR__ . '/vendor/autoload.php'; // change path as needed

if(!session_id()) {
  session_start();
}

$fb = new \Facebook\Facebook([
  'app_id' => '1260712480786665',
  'app_secret' => '335e5b94a75711f8e01baf852a992fff',
  'default_graph_version' => 'v2.10',
  //'default_access_token' => '{access-token}', // optional
]);

// Use one of the helper classes to get a Facebook\Authentication\AccessToken entity.
//   $helper = $fb->getRedirectLoginHelper();
//   $helper = $fb->getJavaScriptHelper();
//   $helper = $fb->getCanvasHelper();
//   $helper = $fb->getPageTabHelper();

$helper = $fb->getRedirectLoginHelper();

if (isset($_GET['state'])) {
  $helper->getPersistentDataHandler()->set('state', $_GET['state']);
}

$login_url = $helper->getLoginUrl("http://localhost/g3t3/fb-login/login.php");

# Uncomment this if you want to see the login_url
// print_r($login_url);

try {
  $accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

if (isset($accessToken)) {
  // Logged in!
  $_SESSION['access_token'] = (string) $accessToken;
  header("Location:./homepage.php");

  // Now you can redirect to another page and use the
  // access token from $_SESSION['access_token']
} elseif ($helper->getError()) {
  // The user denied the request
  header("Location:./login.php");
  exit;
}

// now we will get users first name, email, last name

