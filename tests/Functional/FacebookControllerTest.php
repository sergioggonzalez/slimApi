<?php

namespace Tests\Functional;

class FacebookControllerTest extends BaseTestCase
{
    public function testGetUserOK()
    {
        $response = $this->runApp('GET', '/profile/facebook/1339894004');

        // Check the status code
        $this->assertEquals(200, $response->getStatusCode());

    }

    public function testGetUserNotFound()
    {
        $response = $this->runApp('GET', '/profile/facebook/232');

        // Check the status code
        $this->assertEquals(400, $response->getStatusCode());

        // Check the error message
        $data = json_decode($response->getBody(), true);
        $this->assertContains("Graph returned an error: Unsupported get request. Object with ID '232' does not exist", $data['errors'][0]);
    }

    public function testGetUserInvalidFormatType()
    {
        $response = $this->runApp('GET', '/profile/facebook/sarasa');

        // Check the status code
        $this->assertEquals(400, $response->getStatusCode());

        // Check the error message
        $data = json_decode($response->getBody(), true);
        $this->assertEquals("Facebook ID: Invalid Format Type", $data['errors'][0]);
    }
}
