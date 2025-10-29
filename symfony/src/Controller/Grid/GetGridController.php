<?php
namespace App\Controller\Grid;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class GetGridController extends AbstractController
{
    public function __invoke(): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse(['error' => 'User not authenticated'], 401);
        }

        $grid = $user->getGrid();

        if (!$grid) {
            return new JsonResponse(['error' => "Grid doesn't exist for this user."], 401);
        }

        return new JsonResponse([
            'size' => $grid->getSize()
        ]);
    }
}
