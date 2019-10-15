<?php

namespace piyushdolar\facebook;

require_once __DIR__ . '/src/autoload.php';

use yii\base\InvalidConfigException;

class Fb extends \yii\base\Widget
{
	public $debug = false;
	public $key;
	public $secret;
	public $version;
	public $callback;
	public $fb;
	public $response;

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
    
    public function _response($response){
    	if($this->debug){
    		$result = $response->getDecodedBody();
    		$result['debug'] = $response;
    		return $result;
    	}else{
    		return $response->getDecodedBody();
    	}
    }

    public function loginUrl($permissions = ['public_profile','email']){
    	$helper = $this->fb->getRedirectLoginHelper(); 
		$loginUrl = $helper->getLoginUrl($this->callback, $permissions);		 
		return $loginUrl;
    }

    public function setAccessToken(){
    	$helper = $this->fb->getRedirectLoginHelper();
    	try {
			$accessToken = $helper->getAccessToken();
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
			throw new FacebookResponseException(self::class . $e->getMessage());
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
			throw new FacebookResponseException(self::class . $e->getMessage());
		}

		if (!isset($accessToken)) {
			if ($helper->getError()) {
				header('HTTP/1.0 401 Unauthorized');
			    echo "Error: " . $helper->getError() . "\n";
			    echo "Error Code: " . $helper->getErrorCode() . "\n";
			    echo "Error Reason: " . $helper->getErrorReason() . "\n";
			    echo "Error Description: " . $helper->getErrorDescription() . "\n";
		  	} else {
			    header('HTTP/1.0 400 Bad Request');
			    echo 'Bad request';
		  	}
		}

		$oAuth2Client = $this->fb->getOAuth2Client();
		$tokenMetadata = $oAuth2Client->debugToken($accessToken);
		$tokenMetadata->validateAppId($this->key);
		$tokenMetadata->validateExpiration();

		if (!$accessToken->isLongLived()) {
		  	try {
		    	$accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
		 	} catch (Facebook\Exceptions\FacebookSDKException $e) {
				throw new FacebookResponseException(self::class . $e->getMessage());
		  	}		 	
		}

		$accessToken = (string) $accessToken;
		\Yii::$app->session->set('fbAccessToken', $accessToken);
		return $accessToken;
    }

    public function debug($token){
    	return $this->fb->get('/me/permissions?debug=all', $token);
    }

    public function getUserProfile($parameter=['id,name'],$token){
    	$parameter = http_build_query(['fields'=>implode(',',$parameter)]);    	
    	$response = $this->fb->get('/me?'.$parameter, $token);
    	return $this->_response($response);
    }

    public function getUserPosts($parameter=[],$token){
    	$parameter = http_build_query(['fields'=>implode(',',$parameter)]);    	
    	$response = $this->fb->get('/me/posts?'.$parameter, $token);
    	return $this->_response($response);
    }

    public function getUserTotalFriends($token){    	
    	$response = $this->fb->get('/me/friends', $token);
    	return $this->_response($response);
    }

}
