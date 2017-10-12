<?php
namespace App\Controllers;
use Facebook;

class FacebookController
{

	// Facebook SDK class instance
	private $fb;

	public function __construct() {
		$this->fb = new Facebook\Facebook([
			'app_id' => '1446294395408564',
			'app_secret' => '5d438bf3b59d22548d8d2f4e11d80448',
			'default_graph_version' => 'v2.10',
		]);
		$this->setAccessToken();
	}

	/**
	 * Get Facebook user profile information
	 * @return HTTP response $response
	 */
	public function get($request, $response, $args){

		$result = array();
		$statusCode = 400;
		if(is_numeric($args['id'])){
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
			}
		} else {
			$result['errors'][] = "Facebook ID: Invalid Format Type";

		}

		return $response->withJson($result)->withStatus($statusCode);
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
