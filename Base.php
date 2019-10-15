<?php

namespace piyushdolar\facebook;

require_once __DIR__ . '/src/autoload.php';

use yii\base\InvalidConfigException;

class Base extends \yii\base\Widget
{
	public $key;
	public $secret;
	public $version;
	public $callback;
	public $fb;

	public function init(){
		if(!isset($this->key) || empty($this->key)){
        	throw new InvalidConfigException(self::class . ": Facebook Key is missing!");
		}else if(!isset($this->secret) || empty($this->secret)){
        	throw new InvalidConfigException(self::class . ": Facebook secret key is missing.!");
		}else if(!isset($this->version) || empty($this->version)){
        	throw new InvalidConfigException(self::class . ": Facebook version is missing!");
		}else if(!isset($this->callback) || empty($this->callback)){
        	throw new InvalidConfigException(self::class . ": Facebook callback URL is missing!");
		}else{
			$this->fb = new \Facebook\Facebook([
			  'app_id' => $this->key,
			  'app_secret' => $this->secret,
			  'default_graph_version' => $this->version
			  //'default_access_token' => '{access-token}', // optional
			]);			
		}
        parent::init();
    }    

    public function loginUrl(){
    	$helper = $this->fb->getRedirectLoginHelper(); 
		$permissions = ['email']; // Optional permissions
		$loginUrl = $helper->getLoginUrl($this->callback, $permissions);		 
		return $loginUrl;
    }
}
