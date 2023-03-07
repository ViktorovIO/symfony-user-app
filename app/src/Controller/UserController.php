<?php

namespace App\Controller;

use App\Entity\User;
use App\Exception\UserSaveException;
use App\Form\UserType;
use App\Service\UserService;
use App\Transformer\UserTransformer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserController extends AbstractController
{
    private UserTransformer $userTransformer;
    private UserService $userService;

    public function __construct(UserTransformer $userTransformer, UserService $userService)
    {
        $this->userTransformer = $userTransformer;
        $this->userService = $userService;
    }

    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $searchQuery = $this->makeSearchQuery($request);
        $users = count($searchQuery) > 0
            ? $this->userService->searchByQuery($searchQuery)
            : $this->userService->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->userService->create($this->userTransformer->reverseTransform($user));
            } catch (UserSaveException $exception) {
                return $this->json(['message' => $exception->getMessage()]);
            }

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->userService->update($this->userTransformer->reverseTransform($user));
            } catch (UserSaveException $exception) {
                return $this->json(['message' => $exception->getMessage()]);
            }

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $this->userService->delete($this->userTransformer->reverseTransform($user));
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    private function makeSearchQuery(Request $request): array
    {
        $searchQuery = [];
        if (!empty($request->query->get('last_name'))) {
            $searchQuery['last_name'] = $request->query->get('last_name');
        }

        if (!empty($request->query->get('first_name'))) {
            $searchQuery['first_name'] = $request->query->get('first_name');
        }

        if (!empty($request->query->get('surname'))) {
            $searchQuery['surname'] = $request->query->get('surname');
        }

        if (!empty($request->query->get('phone'))) {
            $searchQuery['phone_list'] = $request->query->get('phone');
        }

        if (!empty($request->query->get('phone_count'))) {
            $searchQuery['phone_count'] = $request->query->get('phone_count');
        }

        return $searchQuery;
    }
}
