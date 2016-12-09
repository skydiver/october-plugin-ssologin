<?php

    namespace Martin\SSOLogin;

    use Event, View;
    use System\Classes\PluginBase;
    use System\Classes\SettingsManager;
    use System\Classes\CombineAssets;
    use Martin\SSOLogin\Models\Settings;

    class Plugin extends PluginBase {

        public $elevated = true;

        public function boot() {

            \Backend\Controllers\Auth::extend(function($controller) {
                if(\Backend\Classes\BackendController::$action == 'signin') {

                    if(Settings::get('google_button') == 'light') {
                        $CSS[] = 'ssologin-light.css';
                    } else {
                        $CSS[] = 'ssologin.css';
                    }

                    if(Settings::get('hide_login_fields') == 1) {
                        $CSS[] = 'hide-login.css';
                    }

                    $controller->addCss(CombineAssets::combine($CSS, plugins_path() . '/martin/ssologin/assets/css/'));

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
                    'order'       => 800,
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
