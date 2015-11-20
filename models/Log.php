<?php

    namespace Martin\SSOLogin\Models;

    use Model;

    class Log extends Model {

        public $table = 'martin_ssologin_logs';

        public $belongsTo = [
            'user' => ['Backend\Models\User']
        ];

    }

?>