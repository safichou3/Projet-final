<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

class ApiUserControllerTest extends WebTestCase
{
    private $client;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient([], ['HTTP_HOST' => 'localhost:8001']);
    }

    public function testLoginSuccess()
    {
        $this->client->request('POST', '/api/login_check', [], [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'username' => 'safia.medjahed@gmail.com',
                'password' => 'test'
            ])
        );
        $data = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('token', $data);
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    public function getToken()
    {
        $this->client->request('POST', '/api/login_check', [], [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'username' => 'safia.medjahed@gmail.com',
                'password' => 'test'
            ])
        );
        $data = json_decode($this->client->getResponse()->getContent(), true);
        return $data['token'];
    }

    public function testLoginFailed()
    {
        $this->client->request('POST', '/api/login_check', [], [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'username' => 'test@gmail.com',
                'password' => 'tist'
            ])
        );
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $this->client->getResponse()->getStatusCode());
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals(['code' => 401, 'message' => 'Invalid credentials.'], $data);
    }

    public function testGetUserSuccess()
    {
        $token = $this->getToken();
        $this->client->request('POST', '/api/user/getcurrent', [], [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer ' . $token
            ]
        );
        $this->assertResponseStatusCodeSame(200);
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('email', $data);
        $this->assertArrayHasKey('roles', $data);
        $this->assertResponseHeaderSame('content_type', 'application/json');
    }

    public function testGetUserFailed()
    {
        $token = $this->getToken();
        $this->client->request('POST', '/api/user/getcurrent', [], [],
            [
                'CONTENT_TYPE' => 'application/json',
            ]
        );
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $this->client->getResponse()->getStatusCode());
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals(['code' => 401, 'message' => 'JWT Token not found'], $data);
    }
}