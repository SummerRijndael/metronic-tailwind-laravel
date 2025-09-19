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

// Profile page
Breadcrumbs::for('myprofile', function (BreadcrumbTrail $trail) {
    $trail->parent('myaccount');
    $trail->push('User Profile', route('myprofile'));
});

// Profile settings page
Breadcrumbs::for('profile_settings', function (BreadcrumbTrail $trail) {
    $trail->parent('myaccount');
    $trail->push('User Settings', route('profile_settings'));
});

// My Account page
Breadcrumbs::for('user_mngr', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Users Management', '#');
});

// Users list page
Breadcrumbs::for('userslist', function (BreadcrumbTrail $trail) {
    $trail->parent('user_mngr');
    $trail->push('Users List', route('userslist'));
});

// Menu generator page
Breadcrumbs::for('menugen', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Menu Generator', 'menugen');
});

Breadcrumbs::for('test', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('test page', 'test');
});