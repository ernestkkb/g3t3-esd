<?php
require './fb-init.php';
?>

<?php require "fb-init.php";?>

<?php if (isset($_SESSION['access_token'])): ?>
    
<?php else: ?>
    <a href="<?php echo $login_url;?>">Login With Facebook</a>
<?php endif; ?>

<?php
if (isset($_SESSION['access_token'])) {

    try {
      $fb->setDefaultAccesstoken($_SESSION['access_token']);
      $resources = $fb->get('/me?locale=en_US&fields=name,email');
      $user = $resources->getGraphUser();

      echo "
      <h3> Hello, {$user->getField('name')} <h3> <br>
      {$user->getField('email')}
      {$user->getField('id')}
      ";
?>
    <br>
    <a href="./logout.php">Logout </a> <!--This is to destroy the session-->
    
<?php
    } catch (Exception $e) {
      echo $e->getTraceAsString();
    }
  }
?>