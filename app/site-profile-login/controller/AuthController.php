<?php
/**
 * AuthController
 * @package site-profile-login
 * @version 0.0.1
 */

namespace SiteProfileLogin\Controller;

use ProfileAuth\Model\ProfileSession as PSession;
use Profile\Model\Profile;

use LibForm\Library\Form;
use SiteProfileLogin\Library\Meta;

class AuthController extends \Site\Controller
{
	public function logoutAction(){
        $session = $this->profile->getSession();
        if($session){
        	PSession::remove(['id'=>$session->id]);

        	$config = $this->config->profileAuth;
        	$cookie_name = $config->cookie->name;
        	$this->res->addCookie($cookie_name, '', -1000);
        }

        $next = $this->router->to('siteHome');
        $this->res->redirect($next);
    }

    public function loginAction() {
        $next = $this->req->getQuery('next');
        if(!$next)
            $next = $this->router->to('siteHome');

        if($this->profile->isLogin())
            return $this->res->redirect($next);

        $form = new Form('site.profile.login');

        $params = [
            'error' => false,
            'form'  => $form,
            'meta' => Meta::single((object)[
            	'title' => 'Login',
            	'description' => 'Profile login page'
            ])
        ];

        if(!($valid = $form->validate())){
            $this->res->render('profile/auth/login', $params);
            return $this->res->send();
        }

        $profile = Profile::getOne([
        	'$or' => [
        		[ 'email' => $valid->name ],
        		[ 'phone' => $valid->name ],
        		[ 'name'  => $valid->name ]
        	]
        ]);

        if(!$profile || !password_verify($valid->password, $profile->password)){
            $params['error'] = true;
            $this->res->render('profile/auth/login', $params);
            return $this->res->send();
        }

        $session = [
        	'profile' => $profile->id,
        	'hash'    => null,
        	'expires' => date('Y-m-d H:i:s', strtotime('+7 days'))
        ];

        while(true){
        	$hash = base64_encode(password_hash(uniqid().'.'.uniqid(), PASSWORD_DEFAULT));
        	$hash = strrev($hash);
        	$hash = trim($hash, '=');

        	if(PSession::getOne(['hash'=>$hash]))
        		continue;

        	$session['hash'] = $hash;
        	break;
        }

        PSession::create($session);

        $config         = \Mim::$app->config->profileAuth;
        $cookie_name    = $config->cookie->name;
        $cookie_expires = 604800;
        if(!$this->req->getPost('remember'))
        	$cookie_expires = 0;

        $this->res->addCookie($cookie_name, $session['hash'], $cookie_expires);

        $this->res->redirect($next);
    }
}