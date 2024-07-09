<?php

namespace App\Controller;

use FOS\RestBundle\View\View;
use App\Service\ReservationCrudService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api')]
class ReservationController extends AbstractController
{
    private SerializerInterface $serializer;
    private ReservationCrudService $reservationService;

    public function __construct( SerializerInterface $serializer, ReservationCrudService $reservationService)
    {
        $this->serializer = $serializer;
        $this->reservationService = $reservationService;
    }

    #[Rest\Post('/reservations', name: 'car_reservation')]
    public function index(Request $request): View
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['carId']) || !isset($data['startTime']) || !isset($data['endTime'])) {
            return View::create("Invalid data", Response::HTTP_BAD_REQUEST);
        }

        $userId = $this->getUser()->getId();
        $carId = strip_tags($data['carId']);
        $startTime = new \DateTime(strip_tags($data['startTime']));
        $endTime = new \DateTime(strip_tags($data['endTime']));

        if (!$userId || !$carId || !$startTime || !$endTime) {
            return View::create("Invalid data", Response::HTTP_BAD_REQUEST);
        }

        if ($startTime >= $endTime) {
            return View::create("Start time cannot be greater than end time", Response::HTTP_BAD_REQUEST);
        }

        // Minimun is 30 minute
        $diff = $startTime->diff($endTime);
        if ($diff->h == 0 && $diff->i < 30) {
            return View::create("Reservation must be at least 30 minutes", Response::HTTP_BAD_REQUEST);
        }

        [$success, $message]  = $this->reservationService->createReservation($startTime, $endTime, (int)$carId, (int) $userId);

        if ($success) {
            $reservationJson = $this->serializer->serialize($message, 'json', [
                'groups' => ['reservation_group'],
            ]);
            $reservationsJson = json_decode($reservationJson, true);
            return View::create($reservationsJson, Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
        }

        return View::create($message, Response::HTTP_BAD_REQUEST);
    }

    #[Rest\Get('/reservations', name: 'user_reservations')]
    public function getUserReservations(): View
    {
        $currentUserId = $this->getUser()->getId();
        [$success, $message] = $this->reservationService->getReservation($currentUserId);

        if ($success) {
            $reservationsJson = $this->serializer->serialize($message, 'json', [
                'groups' => ['reservation_group'],
            ]);
            $reservationsJson = json_decode($reservationsJson, true);
            return View::create($reservationsJson, Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
        }

        return View::create($message, Response::HTTP_BAD_REQUEST);
    }

    #[Rest\Put('/reservations/{id}', name: 'update_reservation')]
    public function updateReservation(Request $request, int $id): View
    {
        $data = json_decode($request->getContent(), true);
        $currentUserId = $this->getUser()->getId();

        if (!isset($data['startTime']) || !isset($data['endTime']) || !isset($data['carId'])) {
            return View::create("Invalid data", Response::HTTP_BAD_REQUEST);
        }

        $carId = $data['carId'];
        $startTime = new \DateTime(strip_tags($data['startTime']));
        $endTime = new \DateTime(strip_tags($data['endTime']));

        if ($startTime >= $endTime) {
            return View::create("Start time cannot be greater than end time", Response::HTTP_BAD_REQUEST);
        }

        $reservationId = $id;

        [$success, $message] = $this->reservationService->updateReservation($reservationId, $carId, $startTime, $endTime, $currentUserId);

        if ($success) {
            return View::create($message, Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
        }
        return View::create($message, Response::HTTP_BAD_REQUEST);
    }

    #[Rest\Delete('/reservations/{id}', name: 'cancel_reservation', methods: ['DELETE'])]
    public function cancelReservation(int $id): View
    {
        $currentUserId = $this->getUser()->getId();

        [$success, $message] = $this->reservationService->cancelReservation($id, $currentUserId);

        if ($success) {
            return View::create($message, Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
        }
        return View::create($message, Response::HTTP_BAD_REQUEST);
    }
}
