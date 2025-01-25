<?php
include_once('init/init.php');

// Include the header file which contains the HTML <head> section and opening <body> tag
include('includes/header.inc.php');
include('includes/navbar.inc.php');

// Check if the 'page' parameter is set in the URL and if it is a valid page
if (isset($_GET['page'])) {
  $page = $_GET['page']; // If valid, set $page to the value of the 'page' parameter
  $after_login_pages = ['dashboard'];
  $before_login_pages = ['login', 'register'];
  if ($page === 'logout' || (in_array($page, $before_login_pages) && !LoggedInUser()) || (in_array($page, $after_login_pages) && LoggedInUser())) {
    include('pages/' . $page . '.php');
  } else {
    header('Location: ./');
  }
  // if (in_array($page, $before_login_pages) && LoggedInUser() === false) {
  //   include('pages/' . $page . '.php');
  // } else if (in_array($page, $after_login_pages) && LoggedInUser()) {
  //   include('pages/' . $page . '.php');

} else {
  include('pages/home.php'); // If not valid or not set, default to 'home' page
}
// Include the footer file which contains the closing </body> and </html> tags
include('includes/footer.inc.php');
$db->close();
