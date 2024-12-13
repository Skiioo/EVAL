<?php
// src/Controller/UserController.php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(User::class)->findAll();

        return $this->json($users);
    }

    #[Route('/user/create', name: 'app_user_create', methods: ['POST'])]
    public function createUser(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['name']) || !isset($data['email']) || !isset($data['password']) || !isset($data['phoneNumber']) || !isset($data['roles'])) {
            return new Response('Invalid data', Response::HTTP_BAD_REQUEST);
        }

        $user = new User();
        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);
        $user->setPhoneNumber($data['phoneNumber']);
        $user->setRoles($data['roles']);
        $user->setRelations($data['relations']);


        $entityManager->persist($user);
        $entityManager->flush();

        return new Response(sprintf('Utilisateur créé', $data['name']), Response::HTTP_CREATED);
    }

    #[Route('/user/{id}', name: 'app_user_id', methods: ['GET'])]
    public function findUserById(int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                'Pas d utilisateur trouver pour l id ' . $id
            );
        }

        return $this->json([
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'phoneNumber' => $user->getPhoneNumber(),
            'roles' => $user->getRoles(),
            'relations' => $user->getRelations(),

        ]);
    }

    #[Route('/user/{id}', name: 'app_user_delete', methods: ['DELETE'])]
    public function deleteUser(int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for id ' . $id
            );
        }

        $entityManager->remove($user);
        $entityManager->flush();

        return new Response(sprintf('Utilisater supprimé', $id), Response::HTTP_OK);
    }
}
