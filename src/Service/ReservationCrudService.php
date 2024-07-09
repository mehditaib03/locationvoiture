<?php

namespace App\Service;

use App\Entity\Car;
use App\Entity\User;
use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;

class ReservationCrudService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    private function getRepository(string $entityClass)
    {
        return $this->entityManager->getRepository($entityClass);
    }

    // Create Reservation
    public function createReservation($startTime, $endTime, $carId, $userId)
    {

        $carRepository = $this->getRepository(Car::class);
        $userRepository = $this->getRepository(User::class);
        $reservationRepository = $this->getRepository(Reservation::class);

        $car = $carRepository->find($carId);
        $user = $userRepository->find($userId);

        if (!$car || !$user) {
            return [false, "Car or User not found"];
        };

        $existingReservation = $reservationRepository->findExistingReservation($carId, $startTime, $endTime);
        if ($existingReservation) {
            return [false, "This car is already reserved for the requested time"];
        }

        try {
            $reservation = new Reservation();
            $reservation->setUser($user);
            $reservation->setCar($car);
            $reservation->setStartTime($startTime);
            $reservation->setEndTime($endTime);

            $this->entityManager->persist($reservation);
            $this->entityManager->flush();
            return [true, $reservation];
        } catch (\Throwable $th) {
            return [false, "Car or User not found"];
        }
    }

    // Get Reservation
    public function getReservation($currentUserId)
    {
        $user = $this->getRepository(User::class)->find($currentUserId);
        if (!$user) {
            return [false, "User not found"];
        }

        $reservations = $this->entityManager->getRepository(Reservation::class)->findBy(['user' => $currentUserId]);

        if (empty($reservations)) {
            return [false, "No reservations found for this user"];
        }

        return [true, $reservations];
    }

    // Update Reservation
    public function updateReservation($reservationId, $carId, $startTime, $endTime, $currentUserId)
    {
        $reservation = $this->getRepository(Reservation::class)->find($reservationId);
        if (!$reservation) {
            return [false, "Reservation not found"];
        }

        $reservation_user_id = $reservation->getUser()->getId();
        if ($reservation_user_id !== $currentUserId) {
            return [false, "Unauthorized to modify this reservation"];
        }
        try {
            $car = $this->entityManager->getRepository(Car::class)->find($carId);
            $reservation->setCar($car);
            $reservation->setStartTime($startTime);
            $reservation->setEndTime($endTime);
            $this->entityManager->flush();
            $message = "Reservation updated successfully";
            return [true, $message];
        } catch (\Throwable $th) {
            return [false, "An error occurred"];
        }
    }

    // Cancel Reservation
    public function cancelReservation($reservationId, $currentUserId)
    {
        $reservation = $this->getRepository(Reservation::class)->find($reservationId);
        if (!$reservation) {
            return [false, "Reservation not found"];
        }

        $reservation_user_id = $reservation->getUser()->getId();
        if ($reservation_user_id !== $currentUserId) {
            return [false, "Unauthorized to delete this reservation"];
        }

        try {
            $this->entityManager->remove($reservation);
            $this->entityManager->flush();
            return [true, "Reservation cancelled successfully"];
        } catch (\Throwable $th) {
            return [false, "An error occurred"];
        }
    }
}
