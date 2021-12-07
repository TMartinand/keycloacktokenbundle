<?php

declare(strict_types=1);

namespace App\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class DecodeTokenUserTest extends WebTestCase
{
    /**
     * @var string
     */
    private $token;

    /**
     * @var AbstractBrowser
     */
    private $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->token  = "eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICItMklhVUs0ckZiYVhHQXh3NzY0dWE5WHNRbTUtY0RzbEhIbnJaTTUydTFzIn0.eyJleHAiOjE2Mzc4NDMwNTIsImlhdCI6MTYzNzg0Mjc1MiwiYXV0aF90aW1lIjoxNjM3ODQyNzUxLCJqdGkiOiJkZGE1MmFhOC03ZWVhLTRiZmMtOTdjNC1jMzVkMjgwOWJlNzMiLCJpc3MiOiJodHRwczovL2tleWNsb2FrLWFwcGludGVybmUuYW1pbHRvbmUuY29tL2F1dGgvcmVhbG1zL2FtaWx0b25lX2h1c3RvbnBlcnNvX3RtYXJ0aW5hbmQiLCJhdWQiOiJhY2NvdW50Iiwic3ViIjoiZWI4ZTQ1OTgtNmY3Ni00MmJmLThhY2ItNmQ0Yzg0MDBmOTI0IiwidHlwIjoiQmVhcmVyIiwiYXpwIjoiaHVzdG9ucGVyc28ta29uZyIsInNlc3Npb25fc3RhdGUiOiIxM2NlYmJlYS1hNTk4LTQxYjctOGFkNC1lYmM2ODhiN2MzM2IiLCJhY3IiOiIxIiwiYWxsb3dlZC1vcmlnaW5zIjpbXSwicmVhbG1fYWNjZXNzIjp7InJvbGVzIjpbIm9mZmxpbmVfYWNjZXNzIiwidW1hX2F1dGhvcml6YXRpb24iXX0sInJlc291cmNlX2FjY2VzcyI6eyJhY2NvdW50Ijp7InJvbGVzIjpbIm1hbmFnZS1hY2NvdW50IiwibWFuYWdlLWFjY291bnQtbGlua3MiLCJ2aWV3LXByb2ZpbGUiXX19LCJzY29wZSI6Im9wZW5pZCBlbWFpbCBwcm9maWxlIiwiZW1haWxfdmVyaWZpZWQiOmZhbHNlLCJuYW1lIjoidGVzdCB0ZXN0IHRlc3QiLCJwcmVmZXJyZWRfdXNlcm5hbWUiOiJ0ZXN0IiwiSWQiOiJlYjhlNDU5OC02Zjc2LTQyYmYtOGFjYi02ZDRjODQwMGY5MjQiLCJnaXZlbl9uYW1lIjoidGVzdCIsImZhbWlseV9uYW1lIjoidGVzdCB0ZXN0IiwiZW1haWwiOiJ0ZXN0QHRlc3QuY29tIn0.KRH6jyU-7kvu2pTWHVJqKkKeKjpb0JI9ekCZtsexr0oZBZQUPFFMtw5jd1jffGeU8vlVZvkeM-077HN0OGMepAk1SqyXJqrAJcyljwQO5MAIhZCi4xPRwUEezCU1IJnhri1UfQZFM3JXMzRBOzjuDtsfW4omlFsbJNn87p8WRi8a_5itRT6V55eOf2ZeMvuLiewbV0YIwH42p5sgPdPwCLduNFC7TOSs6fSHZ0BOjq_bktVzRAfOcQXRTEQWYzm2hzeuvsf6hCfRo5hAOoZb6rMuwXeA6YcPbz_QAMQoOlsgMXAlv8qVUXc3bCyRABdq0HWlmbJGrdxYItwOEP-tPg";
    }

    public function testBearerNotWriteCorrectly(): void
    {
        $this->client->request("GET", "/home", [], [], ["HTTP_Authorization" => "Beare ".$this->token]);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testBearerTokenEmpty(): void
    {
        $this->client->request("GET", "/home", [], [], ["HTTP_Authorization" => "Bearer "]);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testBearerTokenBadData(): void
    {
        $this->token = "eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICItMklhVUs0ckZiYVhHQXh3NzY0dWE5WHNRbTUtY0RzbEhIbnJaTTUydTFzIn0.eyJleHAW1haWwiOiJ0ZXN0QHRlc3QuY29tIn0.KRH6jyU-7kvu2pTWHVJqKkKeKjpb0JI9ekCZtsexr0oZBZQUPFFMtw5jd1jffGeU8vlVZvkeM-077HN0OGMepAk1SqyXJqrAJcyljwQO5MAIhZCi4xPRwUEezCU1IJnhri1UfQZFM3JXMzRBOzjuDtsfW4omlFsbJNn87p8WRi8a_5itRT6V55eOf2ZeMvuLiewbV0YIwH42p5sgPdPwCLduNFC7TOSs6fSHZ0BOjq_bktVzRAfOcQXRTEQWYzm2hzeuvsf6hCfRo5hAOoZb6rMuwXeA6YcPbz_QAMQoOlsgMXAlv8qVUXc3bCyRABdq0HWlmbJGrdxYItwOEP-tPg";
        $this->client->request("GET", "/home", [], [], ["HTTP_Authorization" => "Bearer ".$this->token]);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }
    
    public function testBearerTokenWorking(): void
    {
        $this->client->request("GET", "/home", [], [], ["HTTP_Authorization" => "Bearer ".$this->token]);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}
