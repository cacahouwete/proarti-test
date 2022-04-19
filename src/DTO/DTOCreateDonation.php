<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class DTOCreateDonation
{
    /**
     * @Assert\Type("integer")
     * @Assert\NotBlank
     */
    public $amount;

    /**
     * @Assert\Type("integer")
     * @Assert\NotNull
     */
    public $personId;

    /**
     * @Assert\Type("integer")
     * @Assert\NotNull
     */
    public $rewardId;
}
