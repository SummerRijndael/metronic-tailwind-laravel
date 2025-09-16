<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Dashboard
Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->push('Home', route('dashboard'));
});

// My Account page
Breadcrumbs::for('myaccount', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('My Account', '#');
});


// My Account page
Breadcrumbs::for('account_home', function (BreadcrumbTrail $trail) {
    $trail->parent('myaccount');
    $trail->push('Account Home', '#');
});

// Profile page
Breadcrumbs::for('myprofile', function (BreadcrumbTrail $trail) {
    $trail->parent('account_home');
    $trail->push('User Profile', route('myprofile'));
});

// Menu generator page
Breadcrumbs::for('menugen', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Menu Generator', 'menugen');
});