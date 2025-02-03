<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Psr\Log\LoggerInterface;

final class ApiRegistrationController extends AbstractController
{
    #[Route('/api/registration', name: 'app_api_registration', methods: ['POST'])]
    public function index(
        ManagerRegistry $doctrine,
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        LoggerInterface $logger
    ): JsonResponse {
        try {
            $em = $doctrine->getManager();
            $decoded = json_decode($request->getContent(), false);

            if (!$decoded || !isset($decoded->email, $decoded->password, $decoded->username)) {
                return $this->json(['error' => 'Invalid JSON data'], 400);
            }

            $email = filter_var($decoded->email, FILTER_VALIDATE_EMAIL);
            $username = trim($decoded->username);
            $plaintextPassword = $decoded->password;

            if (!$email || empty($username) || empty($plaintextPassword)) {
                return $this->json(['error' => 'Invalid input data'], 400);
            }

            $existingUser = $em->getRepository(User::class)->findOneBy(['email' => $email]);
            if ($existingUser) {
                return $this->json(['error' => 'Email already in use'], 409);
            }

            $user = new User();
            $hashedPassword = $passwordHasher->hashPassword($user, $plaintextPassword);
            $user->setPassword($hashedPassword);
            $user->setEmail($email);
            $user->setUsername($username);

            $em->persist($user);
            $em->flush();

            return $this->json(['message' => 'Registered successfully!'], 201);
        } catch (Exception $e) {
            $logger->error('User registration failed: ' . $e->getMessage());
            return $this->json(['error' => 'Internal server error'], 500);
        }
    }
}
