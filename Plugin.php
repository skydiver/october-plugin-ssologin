<?php

    namespace Martin\SSOLogin;

    use Event, View;
    use System\Classes\PluginBase;

    class Plugin extends PluginBase {

        public $elevated = true;

        public function boot() {

            \Backend\Controllers\Auth::extend(function($controller) {
                if(\Backend\Classes\BackendController::$action == 'signin') {
                    $controller->addCss('/plugins/martin/ssologin/assets/css/ssologin.css');
                }
            });

            Event::listen('backend.auth.extendSigninView', function($controller) {
                return View::make("martin.ssologin::login");
            });

        }

        public function pluginDetails() {
            return [
                'name'        => 'martin.ssologin::lang.plugin.name',
                'description' => 'martin.ssologin::lang.plugin.description',
                'author'      => 'Martin',
                'icon'        => 'icon-key'
            ];
        }

        public function registerSettings() {
            return [
                'settings' => [
                    'label'       => 'martin.ssologin::lang.plugin.name',
                    'description' => 'martin.ssologin::lang.plugin.description',
                    'icon'        => 'icon-key',
                    'class'       => '\Martin\SSOLogin\Models\Settings',
                    'order'       => 1,
                    'permissions' => ['martin.ssologin.access'],
                    'category'    => 'system::lang.system.categories.system'
                ],
            ];
        }

        public function registerPermissions() {
            return [
                'martin.ssologin.access'  => ['tab' => 'system::lang.permissions.name', 'label' => 'martin.ssologin::lang.plugin.permissions'],
            ];
        }

    }

?>