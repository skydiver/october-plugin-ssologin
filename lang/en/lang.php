<?php

    return [

        'plugin' => [
            'name'        => 'October Backend SSO Login',
            'description' => 'Single Sign-on login for backend',
            'permissions' => 'Single Sign-on login for backend'
        ],

        'settings' => [
            'google' => [
                'google_client_id'     => 'Client ID',
                'google_client_secret' => 'Client secret',
                'google_redirect_uri'  => 'Authorized redirect URI'
            ]
        ],

        'errors' => [
            'google' => [
                'generic'             => 'October Backend SSO Login - Google Login not configured:',
                'client_id_blank'     => ' missing "Client ID" value',
                'client_secret_blank' => ' missing "Client secret" value',
                'redirect_uri_blank'  => ' missing "Authorized redirect URI" value',
                'invalid_user'        => 'A user was not found with the given credentials'
            ]
        ],

    ];

?>