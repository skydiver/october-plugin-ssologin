<?php

    namespace Martin\SSOLogin\Controllers;

    use Martin\SSOLogin\Models\Settings as Settings;
    use Martin\SSOLogin\Models\Log      as Log;

    use Backend\Classes\Controller;
    use Backend, BackendAuth, Config, Flash, Input, Lang, Request, Session, ValidationException;

    use Backend\Models\AccessLog;
    use Backend\Models\User;
    use System\Classes\UpdateManager;

    use Google_Client, Google_Service_Oauth2;

    class Google extends Controller {

        protected $publicActions = ['index'];

        public function __construct() {
            parent::__construct();
        }

        public function index() {

            # CHECK SETTINGS ARE DEFINED
            $this->checkSettings(['google_client_id', 'google_client_secret']);

            # CREATE GOOGLE CLIENT
            $client = new Google_Client();
            $client->setClientId(Settings::get('google_client_id'));
            $client->setClientSecret(Settings::get('google_client_secret'));
            $client->setRedirectUri(Backend::url('martin/ssologin/google'));
            $client->setScopes('email');

            # HANDLE LOGOUTS
            if(Input::has('logout')) {
                Session::forget('access_token');
                return;
            }

            # AUTHENTICATE GOOGLE USER
            if(Input::has('code')) {
                $client->authenticate(Input::get('code'));
                Session::put('access_token', $client->getAccessToken());
            }

            # SET ACCESS TOKEN OR GET A NEW ONE
            if(Session::has('access_token')) {
                $client->setAccessToken( Session::get('access_token') );
            } else {
                $authUrl = $client->createAuthUrl();
                // Redirect::to() doesn't work here. Send header manually.
                header("Location: $authUrl");
                exit;
            }

            # PARSE USER DETAILS
            if($client->getAccessToken()) {
                $token_data = $client->verifyIdToken();
                Session::put('access_token', $client->getAccessToken());
            }

            # FORGET ACCESS TOKEN
            Session::forget('access_token');

            # CHECK MAIL EXISTS
            if(!isset($token_data['email'])) {

                # RECORD FAILED LOGIN
                $log = new Log;
                $log->provider = 'Google';
                $log->result   = 'failed';
                $log->email    = $email;
                $log->ip       = Request::getClientIp();
                $log->save();

                Flash::error(trans('martin.ssologin::lang.errors.google.invalid_user'));
                return Backend::redirect('backend/auth/signin');
            }

            # FIND USER BY EMAIL
            $email = $token_data['email'];
            $user  = User::where('email', $email)->first();

            # IF NO USER, GET BACK TO LOGIN SCREEN
            if(!$user) {

                # RECORD FAILED LOGIN
                $log = new Log;
                $log->provider = 'Google';
                $log->result   = 'failed';
                $log->email    = $email;
                $log->ip       = Request::getClientIp();
                $log->save();

                Flash::error(trans('martin.ssologin::lang.errors.google.invalid_user'));
                return Backend::redirect('backend/auth/signin');
            }

            # LOGIN USER ON BACKEND
            BackendAuth::login($user, true);

            # RECORD SUCCESSFUL LOGIN
            $log = new Log;
            $log->provider = 'Google';
            $log->result   = 'successful';
            $log->user_id  = $user->id;
            $log->email    = $email;
            $log->ip       = Request::getClientIp();
            $log->save();

            // Load version updates
            UpdateManager::instance()->update();

            // Log the sign in event
            AccessLog::add($user);

            // Redirect to the intended page after successful sign in
            return Backend::redirectIntended('backend');

        }


        #########################################################################################
        #   CHECK PLUGIN SETTINGS EXISTS
        #########################################################################################
        private function checkSettings($settings=array()) {
            foreach($settings as $setting) {
                if(Settings::get($setting) == '') {
                    throw new ValidationException([
                        'code' => Lang::get('martin.ssologin::lang.errors.google.generic') . Lang::get('martin.ssologin::lang.errors.google.'.$setting.'_blank')
                    ]);
                }
            }
        }

    }

?>
