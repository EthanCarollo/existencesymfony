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

        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw new \RuntimeException('Unauthenticated user.');
        }

        $building = $data->buildingId ? $this->buildingsRepository->find($data->buildingId) : null;
        if (!$building) {
            throw new \RuntimeException("Building not found with ID {$data->buildingId}");
        }

        $userGrid = $user->getGrid();
        $existing = $userGrid->getGridBuildings();

        $x = $data->xPos;
        $y = $data->yPos;
        $w = $building->getWidth();
        $l = $building->getLength();

        foreach ($existing as $gb) {
            $ex = $gb->getXPos();
            $ey = $gb->getYPos();
            $ew = $gb->getBuilding()->getWidth();
            $el = $gb->getBuilding()->getLength();

            if ($x < $ex + $ew && $x + $w > $ex && $y < $ey + $el && $y + $l > $ey) {
                throw new \RuntimeException('Building overlaps with an existing one.');
            }
        }

        $gridBuilding = (new GridBuilding())
            ->setXPos($x)
            ->setYPos($y)
            ->setBuilding($building)
            ->setGrid($userGrid);

        $this->em->persist($gridBuilding);
        $this->em->flush();

        return $gridBuilding;
    }
}
