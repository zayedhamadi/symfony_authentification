<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiUserDashboardController extends AbstractController
{
    #[Route('/api/user-dashboard', name: 'app_api_user_dashboard')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'JWT authentication is successful! Welcome to dashboard!',
            'path' => 'src/Controller/ApiUserDashboardController.php',
        ]);
    }
}