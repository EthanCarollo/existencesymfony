<?php
namespace App\Dto;

use Symfony\Component\Serializer\Attribute\Groups;

class GridBuildingInput
{
    #[Groups(['grid_building:write'])]
    public ?int $xPos = null;

    #[Groups(['grid_building:write'])]
    public ?int $yPos = null;

    #[Groups(['grid_building:write'])]
    public ?int $buildingId = null;
}
