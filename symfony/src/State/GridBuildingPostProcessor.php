<?php
namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\GridBuildingInput;
use App\Entity\GridBuilding;
use App\Entity\User;
use App\Repository\BuildingsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

final class GridBuildingPostProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private BuildingsRepository $buildingsRepository,
        private Security $security
    ) {}
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if (!$data instanceof GridBuildingInput) {
            throw new \InvalidArgumentException('Invalid input type.');
        }

        /** @var User $user */
        $user = $this->security->getUser();
        if (!$user) {
            throw new \RuntimeException('Unauthenticated user.');
        }

        $gridBuilding = new GridBuilding();
        $gridBuilding->setXPos($data->xPos);
        $gridBuilding->setYPos($data->yPos);
        $gridBuilding->setGrid($user->getGrid());

        if ($data->buildingId) {
            $building = $this->buildingsRepository->find($data->buildingId);
            if (!$building) {
                throw new \RuntimeException("Building not found with ID {$data->buildingId}");
            }
            $gridBuilding->setBuilding($building);
        }

        $this->em->persist($gridBuilding);
        $this->em->flush();

        return $gridBuilding;
    }
}