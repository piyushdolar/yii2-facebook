Yii2 Facebook Extension
=======================
Yii2 Facebook Extension is easy to implement to your Yii2 script that's what for it made. this extension is made from v4.0 of facebook graph api.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist piyushdolar/yii2-facebook "*"
```

or add

```
"piyushdolar/yii2-facebook": "*"
```

to the require section of your `composer.json` file.


Usage
-----

> **Note:**
This extension is based on Facebook Graph SDK v4.0

## Configuration:
Once the extension is installed, simply use it in your code by :

##### 1. Add this code in web.php in config folder.

	<?php
        	'facebook' => [
				'class'     => 'piyushdolar\facebook\fb',
				'key'       => 'YOUR_APP_KEY',
				'secret'    => 'YOUR_APP_SECRET',
				'version'   => 'v4.0',
				'callback'  => 'http://localhost/socialconnect/web/facebook/callback'
			],
	?>


##### 2. Initialzie extension with your controller by adding this line into your controller.

	<?php 
		use \piyushdolar\facebook\Fb;  
	?>

## Functions and usage:
#### Login URL:
Get Login URL by calling below function `(be default public_profile will return)` and Pass any facebook parameter to loginUrl function.

	<?php 
		\Yii::$app->facebook->loginUrl(['email','public_profile','user_birthday'])
	?>


#### Get User Profile:
Get User profile by calling below function pass any facebook parameter like `name,birthday, first_name,last_name,link` as array as defined below.
> $this->accessToken = it should replaced with your actual access token.

	<?php 
		\Yii::$app->facebook->getUserProfile(['name','birthday','gender'],$this->accessToken)
	?>

#### Get User Feed/Posts:
Get User feeds/posts by calling below function pass any facebook parameter like `['message','link','full_picture','created_time','is_published','is_hidden','attachments']` as array as defined below.
> $this->accessToken = it should replaced with your actual access token.

	<?php 
		\Yii::$app->facebook->getUserProfile(['message','link','full_picture','created_time'],$this->accessToken)
	?>

#### Get User Total Friends:
Get User total friend count by calling below function, You don't need pass anything to this function.
> $this->accessToken = it should replaced with your actual access token.

	<?php 
		\Yii::$app->facebook->getUserTotalFriends($this->accessToken);
	?>
