<?php

namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
class ReservationControllerTest extends WebTestCase
{
    private $client;
    private $bearerToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3MjA1NjAxOTAsImV4cCI6MTcyMDU2Mzc5MCwicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoidGFpYm1laGRpMTVAZ21haWwuY29tIn0.qVH_N_y6lK71J572xiYcYM_8LOc13ty0kmrIt0wxQyeOJTphck_uK1-_dHnhQCmXjvywK6zvWgNwaQ5q7JlGnk0xYkYeCIErB_ZelI2OB5dIrhkAgqmtPMaRTUFJHu2bH1DmFyW3h8-dRyLDAhlVyHrwA2Ld0kEovscRNXfX2bGmYBFE5jyF1txxLTGjfw138TIJuY2ihOEOWUfb3OBx2g-ZQlfG3BlzxC4Fm8ki0nuQmD3G5q-pBEKuJp8yXVtHYftizEukPvg_ASJnjGcCzKEczUG02eT8FmG_1VW8_8kxrR2hU5fHXZG6sN4yPCaHXVfYzPXk-eo2h2pUFpz8Lw';

    protected $reservationController;
    protected $serializerMock;
    protected $reservationServiceMock;
    protected $containerMock;

    protected function setUp(): void
    {
        $this->client = static::createClient();

    }

    public function test_Create_Reservation_InvalidData()
    {
        $data = [
            'userId' => 1,
            'carId' => 1,
            'startTime' => '2023-10-01T10:00:00'
            // Missing endTime
        ];

        $this->client->request(
            'POST',
            '/api/reservations',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->bearerToken
            ],
            json_encode($data)
        );

        $response = $this->client->getResponse();
        $responseContent = $response->getContent();

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertStringContainsString('Invalid data', $responseContent);
    }
}

