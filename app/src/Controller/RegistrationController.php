<?php

namespace App\Controller;

use App\Entity\User;
use App\Exception\UserSaveException;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api", name="api_")
 */
#[Route('/api', name: 'api_')]
class RegistrationController extends AbstractController
{
    private UserService $userService;
    private ValidatorInterface $validator;

    public function __construct(UserService $userService, ValidatorInterface $validator)
    {
        $this->userService = $userService;
        $this->validator = $validator;
    }

    #[Route('/register', methods: ['POST'])]
    public function index(Request $request): JsonResponse
    {
        $decodedRequest = json_decode($request->getContent());
        $email = $decodedRequest->email;
        $password = $decodedRequest->password;
        $user = new User(null, $email, $password);

        try {
            $errors = $this->validator->validate($user);
            if (count($errors) > 0) {
                return $this->json(['message' => (string) $errors]);
            }

            $this->userService->save($email, $password);
        } catch (UserSaveException $exception) {
            return $this->json(['message' => $exception->getMessage()]);
        }


        return $this->json(['message' => 'Registered Successfully']);
    }
}
