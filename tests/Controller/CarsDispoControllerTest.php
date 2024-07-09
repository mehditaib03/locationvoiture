<?php
// tests/Controller/CarsDispoControllerTest.php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CarsDispoControllerTest extends WebTestCase
{

    public function testIndexReturnsCarDetails(): void
    {
        $client = static::createClient();
        $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3MjA1NTY2MTIsImV4cCI6MTcyMDU2MDIxMiwicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoidGFpYm1laGRpMTVAZ21haWwuY29tIn0.4xlKiV7jqj7rtYT8jOdV3E9IG0FWiTePopRgScYXPIU_--cgtZ76XE0NOKVKnJkDr0b2nq4vuWsvws-D24Vrtq5NISj0md-0LqcXVF0QMWoVKXB_THcerCcjUz4ai0Nd1K_X8Ecy-yE9_657EnSr8e5Jv2eYMrId0stJI3L1ae-zMdLq-wML3Wsr1iU8J9P4gckg14RHGfh5ydWQQMHe_oO8gE-eo4b6OjXEbdp1uaDc9_tVNnokdKV-xEnuJegh5AZO1ck-lnc5XA4NcJjGMC4F8bxJqk1errL-QRRR5uH0mzrUaekVSCw7noVVsY-xYv4Ea6ztJnfOuHbtzlkgxA";

        $client->request('GET', '/api/cars/1', [], [], ['HTTP_AUTHORIZATION' => 'Bearer ' . $token]);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        $responseContent = $client->getResponse()->getContent();
        $responseData = json_decode($responseContent, true);

        $this->assertArrayHasKey('name', $responseData);
        $this->assertArrayHasKey('model', $responseData);
        $this->assertArrayHasKey('year', $responseData);

    }

    public function testIndexReturnsNotFoundForInvalidCar(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/cars/9999');

        $this->assertResponseStatusCodeSame(404);
    }
}