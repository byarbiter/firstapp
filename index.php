<?php

// Include the initialization file
require_once('init/init.php');

// Include the header file
include('includes/header.inc.php');

// Include the navigation bar file
include('includes/navbar.inc.php');

// Check if the 'page' parameter is set in the URL
if (isset($_GET['page'])) {
    // Get the value of the 'page' parameter
    $page = $_GET['page']; // about

    // Define an array of admin pages
    $admin_pages = [
        'user/home',
        'user/create',
        'user/update',
        'user/delete',
        'category/home',
        'category/create',
        'category/update',
        'category/delete',
        'product/home',
        'product/create',
        'product/update',
        'product/delete',
        'stock/home',
        'stock/create',
        'stock/update',
        'stock/delete',
    ];

    // Define an array of user pages (currently empty)
    $user_pages = ['cart/home', 'cart/create', 'cart/delete'];

    // Define an array of pages accessible before login
    $before_login_pages = ['login', 'register'];

    // Define an array of pages accessible after login, including admin pages
    $after_login_pages = [
        'dashboard',
        ...$admin_pages,
        ...$user_pages // flat copy
    ];

    // Check if the page is 'logout', a before-login page when not logged in, or an after-login page when logged in
    if (
        $page === 'logout' ||
        (in_array($page, $before_login_pages) && !LoggedInUser()) ||
        (in_array($page, $after_login_pages) && LoggedInUser())
    ) {
        // If the page is an admin page and the user is not an admin, redirect to the home page
        if (in_array($page, $admin_pages) && !isAdmin()) {
            header("Location: ./");
        }

        // Include the requested page
        include('pages/' . $page . '.php');
    } else if (in_array($page, $after_login_pages) && !LoggedInUser()) {
        header("Location: ./?page=login");
    } else {
        // If the conditions are not met, redirect to the home page
        header("Location: ./");
    }
} else {
    // If no 'page' parameter is set, include the home page
    include('pages/home.php');
}

// Include the footer file
include('includes/footer.inc.php');

// Close the database connection
$db->close();
