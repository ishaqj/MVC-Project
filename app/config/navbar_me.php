<?php
/**
 * Config-file for navigation bar.
 *
 */
if($this->di->session->get('user')) {
return [

    // Use for styling the menu
    'class' => 'navbar navbar-default',
    'role' => 'navigation',
    'websiteName' => 'GOAL',
    'userName' => $this->di->session->get('user')[0]->username,
    'profileLink' => $this->di->session->get('user')[0]->id,
 
    // Here comes the menu strcture
    'items' => [


        // This is a menu item
        'Home'  => [
            'text'  => '<i class="fa fa-home"></i> Home',
            'url'   => '',
            'title' => 'Home'
        ],

        // This is a menu item
        'About'  => [
            'text'  => '<i class="fa fa-male"></i> About',
            'url'   => 'about',
            'title' => 'About'
        ],
 
        // This is a menu item
        'Questions' => [
            'text'  =>'<i class="fa fa-question"></i> Questions',
            'url'   =>'questions',
            'title' => 'Questions'
        ],

        // This is a menu item
        'Tags' => [
            'text'  =>'<i class="fa fa-tags"></i> Tags', 
            'url'   =>'tags',  
            'title' => 'Tags'
        ],

        // This is a menu item
        'users' => [
            'text'  =>'<i class="fa fa-users"></i> Users', 
            'url'   =>'users',  
            'title' => 'Användare'
        ],

                // This is a menu item
        'users' => [
            'text'  =>'<i class="fa fa-users"></i> Users', 
            'url'   =>'users',  
            'title' => 'Användare'
        ],
                // This is a menu item
        'ask' => [
            'text'  =>'<i class="fa fa-question-circle"></i> Ask Question', 
            'url'   =>'questions/add',  
            'title' => 'Ask Question'
        ],


                // This is a menu item
        'profile' => [
            'text'  =>'<i class="fa fa-user"></i> Profile', 
            'url'   =>'users/id/'.$this->di->session->get('user')[0]->id,  
            'title' => 'profile'
        ],

                // This is a menu item
        'logout' => [
            'text'  =>'<i class="fa fa-sign-out"></i> Logout', 
            'url'   =>'users/logout',  
            'title' => 'Logout'
        ],

    ],
 
    // Callback tracing the current selected menu item base on scriptname
    'callback' => function($url) {
        if ($url == $this->di->get('request')->getRoute()) {
            return true;
        }
    },

    // Callback to create the urls
    'create_url' => function($url) {
        return $this->di->get('url')->create($url);
    },
];

}

return [

    // Use for styling the menu
    'class' => 'navbar navbar-default',
    'role' => 'navigation',
    'websiteName' => 'GOAL',
    // Here comes the menu strcture
    'items' => [


        // This is a menu item
        'Home'  => [
            'text'  => '<i class="fa fa-home"></i> Home',
            'url'   => '',
            'title' => 'Home'
        ],

        // This is a menu item
        'About'  => [
            'text'  => '<i class="fa fa-male"></i> About',
            'url'   => 'about',
            'title' => 'About'
        ],
 
        // This is a menu item
        'Questions' => [
            'text'  =>'<i class="fa fa-question"></i> Questions',
            'url'   =>'questions',
            'title' => 'Questions'
        ],

        // This is a menu item
        'Tags' => [
            'text'  =>'<i class="fa fa-tags"></i> Tags', 
            'url'   =>'tags',  
            'title' => 'Tags'
        ],

        // This is a menu item
        'users' => [
            'text'  =>'<i class="fa fa-users"></i> Users', 
            'url'   =>'users',  
            'title' => 'Användare'
        ],

        // This is a menu item
        'login' => [
            'text'  =>'<i class="fa fa-sign-in"></i> Login', 
            'url'   =>'users/login',  
            'title' => 'Register'
        ],

                // This is a menu item
        'register' => [
            'text'  =>'<i class="fa fa-user"></i> Register', 
            'url'   =>'users/register',  
            'title' => 'Register'
        ],

    ],
 
    // Callback tracing the current selected menu item base on scriptname
    'callback' => function($url) {
        if ($url == $this->di->get('request')->getRoute()) {
            return true;
        }
    },

    // Callback to create the urls
    'create_url' => function($url) {
        return $this->di->get('url')->create($url);
    },
];
