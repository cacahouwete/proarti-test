<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Reward;
use App\Validation\Constraints\RewardEntityExists;

final class DTOCreateProject
{
    /**
     * @Assert\Type("string")
     * @Assert\NotBlank()
     */
    public $projectName;

    /**
     * @Assert\Type("integer")
     * @RewardEntityExists(class=Reward::class)
     */
    public $rewardId;
}
