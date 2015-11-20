<?php

    return [

        'plugin' => [
            'name'              => 'October Backend SSO Login',
            'description'       => 'Single Sign-on login for backend',
            'description_log'   => 'View all authentication attempts with Single Sign-on',
            'description_title' => 'Authentication attempts',
            'permissions'       => 'Single Sign-on login for backend'
        ],

        'settings' => [
            'google' => [
                'google_client_id'     => 'Client ID',
                'google_client_secret' => 'Client secret',
                'google_redirect_uri'  => 'Authorized redirect URI'
            ]
        ],

        'logs' => [
            'created_at' => 'Date & Time',
            'provider'   => 'Provider',
            'result'     => 'Result',
            'user_id'    => 'Logged User',
            'email'      => 'Used E-mail',
            'ip'         => 'IP address'
        ],

        'errors' => [
            'google' => [
                'generic'                    => 'October Backend SSO Login - Google Login not configured:',
                'google_client_id_blank'     => ' missing "Client ID" value',
                'google_client_secret_blank' => ' missing "Client secret" value',
                'google_redirect_uri_blank'  => ' missing "Authorized redirect URI" value',
                'invalid_user'               => 'A user was not found with the given credentials'
            ]
        ],

    ];

?>