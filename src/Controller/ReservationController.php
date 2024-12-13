<?php
// src/Controller/ReservationController.php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ReservationController extends AbstractController
{
    #[Route('/reservation/create', name: 'create_reservation', methods: ['POST'])]
    public function createReservation(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['date']) || !isset($data['timeSlot']) || !isset($data['eventName']) || !isset($data['userId'])) {
            return new Response('Invalid data: Missing required fields.', Response::HTTP_BAD_REQUEST);
        }

        $user = $entityManager->getRepository(User::class)->find($data['userId']);
        if (!$user) {
            return new Response('User not found.', Response::HTTP_NOT_FOUND);
        }

        $reservationDate = new \DateTime($data['date']);
        $reservationTimeSlot = new \DateTime($data['timeSlot']);
        $now = new \DateTime();

        // Vérifier si la réservation est faite au moins 24 heures à l'avance
        $interval = $now->diff($reservationDate);
        if ($interval->days < 1 || ($interval->days == 1 && $interval->h < 24)) {
            return new Response('Les réservations doivent être faites au moins 24 heures à l avance.', Response::HTTP_BAD_REQUEST);
        }

        // Vérifier si la plage horaire est déjà réservée pour la même date
        $existingReservation = $entityManager->getRepository(Reservation::class)->findOneBy([
            'date' => $reservationDate,
            'timeSlot' => $reservationTimeSlot,
        ]);

        if ($existingReservation) {
            return new Response('Ce créneau horaire est déjà réservé à la date sélectionnée.', Response::HTTP_CONFLICT);
        }

        $reservation = new Reservation();
        $reservation->setDate($reservationDate);
        $reservation->setTimeSlot($reservationTimeSlot);
        $reservation->setEventName($data['eventName']);
        $reservation->setRelation($user);

        $entityManager->persist($reservation);
        $entityManager->flush();

        return new Response(sprintf('Reservation created for event %s.', $data['eventName']), Response::HTTP_CREATED);
    }

    #[Route('/reservations', name: 'get_reservations', methods: ['GET'])]
    public function getReservations(EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        $reservations = $entityManager->getRepository(Reservation::class)->findAll();

        $jsonContent = $serializer->serialize($reservations, 'json', ['groups' => 'reservation:read']);

        return new Response($jsonContent, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/reservation/{id}', name: 'get_reservation', methods: ['GET'])]
    public function getReservation(int $id, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        $reservation = $entityManager->getRepository(Reservation::class)->find($id);

        if (!$reservation) {
            throw $this->createNotFoundException(
                'No reservation found for id ' . $id
            );
        }

        $jsonContent = $serializer->serialize($reservation, 'json', ['groups' => 'reservation:read']);

        return new Response($jsonContent, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }


    #[Route('/reservation/{id}', name: 'delete_reservation', methods: ['DELETE'])]
    public function deleteReservation(int $id, EntityManagerInterface $entityManager): Response
    {
        $reservation = $entityManager->getRepository(Reservation::class)->find($id);

        if (!$reservation) {
            throw $this->createNotFoundException(
                'Il n y a pas de reservation avec cet id' . $id
            );
        }

        $entityManager->remove($reservation);
        $entityManager->flush();

        return new Response(sprintf('Reservation supprimé.', $id), Response::HTTP_OK);
    }
}
