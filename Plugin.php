<?php

    namespace Martin\SSOLogin;

    use Event, View;
    use System\Classes\PluginBase;
    use System\Classes\SettingsManager;

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
                'author'      => 'Martin M.',
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
                'logs' => [
                    'label'       => 'martin.ssologin::lang.plugin.name',
                    'description' => 'martin.ssologin::lang.plugin.description_log',
                    'icon'        => 'icon-key',
                    'url'         => \Backend::url('martin/ssologin/logs'),
                    'order'       => 800,
                    'permissions' => ['martin.ssologin.access'],
                    'category'    => SettingsManager::CATEGORY_LOGS,
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
