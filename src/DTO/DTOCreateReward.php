<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class DTOCreateReward
{
    /**
     * @Assert\Type("string")
     * @Assert\NotBlank
     */
    public $name;

    /**
     * @Assert\Type("integer")
     * @Assert\NotBlank
     */
    public $quantity;

    /**
     * @Assert\Type("integer")
     * @Assert\NotNull
     */
    public $donationId;

}