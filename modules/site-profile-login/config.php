<?php

return [
    '__name' => 'site-profile-login',
    '__version' => '0.0.1',
    '__git' => 'git@github.com:getmim/site-profile-login.git',
    '__license' => 'MIT',
    '__author' => [
        'name' => 'Iqbal Fauzi',
        'email' => 'iqbalfawz@gmail.com',
        'website' => 'https://iqbalfn.com/'
    ],
    '__files' => [
        'app/site-profile-login' => ['install','remove'],
        'modules/site-profile-login' => ['install','update','remove'],
        'theme/site/profile/auth/login.phtml' => ['install','remove']
    ],
    '__dependencies' => [
        'required' => [
            [
                'profile' => NULL
            ],
            [
                'profile-auth' => NULL
            ],
            [
                'lib-form' => NULL
            ]
        ],
        'optional' => []
    ],
    'autoload' => [
        'classes' => [
            'SiteProfileLogin\\Controller' => [
                'type' => 'file',
                'base' => 'app/site-profile-login/controller'
            ],
            'SiteProfileLogin\\Library' => [
                'type' => 'file',
                'base' => 'modules/site-profile-login/library'
            ]
        ],
        'files' => []
    ],
    'routes' => [
        'site' => [
            'siteProfileLogin' => [
                'path' => [
                    'value' => '/pme/login'
                ],
                'handler' => 'SiteProfileLogin\\Controller\\Auth::login',
                'method' => 'GET|POST'
            ],
            'siteProfileLogout' => [
                'path' => [
                    'value' => '/pme/logout'
                ],
                'handler' => 'SiteProfileLogin\\Controller\\Auth::logout'
            ]
        ]
    ],
    'libForm' => [
        'forms' => [
            'site.profile.login' => [
                'name' => [
                    'label' => 'Name',
                    'type' => 'text',
                    'nolabel' => TRUE,
                    'rules' => [
                        'required' => TRUE,
                        'empty' => FALSE
                    ]
                ],
                'password' => [
                    'label' => 'Password',
                    'nolabel' => TRUE,
                    'type' => 'password',
                    'meter' => FALSE,
                    'rules' => [
                        'required' => TRUE,
                        'empty' => FALSE
                    ]
                ]
            ]
        ]
    ]
];