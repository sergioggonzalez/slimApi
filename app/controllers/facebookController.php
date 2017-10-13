<?php
namespace App\Controllers;
use Facebook;
use App\Services\CacheService;

class FacebookController
{
	const APP_ID = '1446294395408564';
	const APP_SECRET = '5d438bf3b59d22548d8d2f4e11d80448';
	const USE_REDIS = false;
	const CACHE_TTL = 1500;
	private $fb;

	public function __construct($c) {
		$this->fb = new Facebook\Facebook([
			'app_id' => self::APP_ID,
			'app_secret' => self::APP_SECRET,
			'default_graph_version' => 'v2.10',
		]);
		$this->setAccessToken();
		$this->cache = new CacheService($c->cache);
	}

	/**
	* Get Facebook user profile information
	* @return HTTP response $response
	*/
	public function get($request, $response, $args){

		$result = array();
		$statusCode = 400;
		$sourceHeader = "From Facebook API";

		if(is_numeric($args['id'])){
			if(self::USE_REDIS && $this->cache->getItemFromCache($args['id'])){
				$result = $this->cache->getItemFromCache($args['id']);
				$sourceHeader = "From Redis cache";
				$statusCode = 200;
			}else{
				try {
					$faceResponse = $this->fb->get('/' . $args['id'].'?fields=id,name,first_name,last_name,email,birthday,gender,hometown,picture');

				} catch(\Facebook\Exceptions\FacebookResponseException $e) {
					$result['errors'][] = 'Graph returned an error: ' . $e->getMessage();

				} catch(\Facebook\Exceptions\FacebookSDKException $e) {
					$result['errors'][] = 'Facebook SDK returned an error: ' . $e->getMessage();
				}

				if(!$result['errors']){
					$statusCode = 200;
					$user = $faceResponse->getGraphUser();
					$result = array(
						'id' => $user->getId(),
						'name' => $user->getName(),
						'first_name' => $user->getFirstName(),
						'last_name' => $user->getLastName(),
						'email' => $user->getEmail(),
						'birthday' => $user->getBirthday(),
						'gender' => $user->getGender(),
						'hometown' => $user->getHometown(),
						'picture' => $user->getPicture(),
					);
					//Save in cache
					if(self::USE_REDIS) $this->cache->saveItemInCache($args['id'], $result, self::CACHE_TTL);
				}
			}

		}else {
			$result['errors'][] = "Facebook ID: Invalid Format Type";
		}
		return $response->withJson($result)->withStatus($statusCode)->withHeader(
        'Source',
        $sourceHeader
    );
	}

	/**
	* Set Access Token
	* @return Facebook\Authentication\AccessToken
	*/
	private function setAccessToken()
	{
		$this->fb->setDefaultAccessToken($this->fb->getApp()->getAccessToken());
	}
}
