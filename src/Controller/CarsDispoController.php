<?php

namespace App\Controller;

use App\Entity\Car;
use FOS\RestBundle\View\View;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api')]
class CarsDispoController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Rest\Get('/cars/{id}', name: 'car_detail')]
    public function index($id): View
    {
        $car = $this->entityManager->getRepository(Car::class)->find($id);
        if (!$car) {
            return View::create(null, Response::HTTP_NOT_FOUND);
        }
        $data = [
            "name" => $car->getName(),
            "model" => $car->getModel(),
            "year" => $car->getYear(),
        ];

        return View::create($data, Response::HTTP_OK);
    }

    #[Rest\Post('/cars', name: 'cars_dispo')]
    public function availableCars(Request $request, SerializerInterface $serializer): View
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['startTime']) || !isset($data['endTime'])) {
            return View::create("Invalid data", Response::HTTP_BAD_REQUEST);
        }
        $startTime = new \DateTime(strip_tags($data['startTime']));
        $endTime = new \DateTime(strip_tags($data['endTime']));

        $availableCars = $this->entityManager->getRepository(Car::class)->findAvailableCars($startTime, $endTime);

        $serialize_reservation = $serializer->serialize($availableCars, 'json', [
            'groups' => ['car_group'],
        ]);
        $serialize_reservation = json_encode(json_decode($serialize_reservation), true);
        return View::create(json_decode($serialize_reservation), Response::HTTP_OK);
    }
}
