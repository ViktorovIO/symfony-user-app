<?php

namespace App\Controller;

use App\Exception\UserSaveException;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="api_")
 */
#[Route('/api', name: 'api_')]
class RegistrationController extends AbstractController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    #[Route('/register', methods: ['POST'])]
    public function index(Request $request): JsonResponse
    {
        $decodedRequest = json_decode($request->getContent());
        $email = $decodedRequest->email;
        $password = $decodedRequest->password;

        try {
            $this->userService->save($email, $password);
        } catch (UserSaveException $exception) {
            return $this->json(['message' => $exception->getMessage()]);
        }


        return $this->json(['message' => 'Registered Successfully']);
    }
}
