<?php

namespace App\Interfaces\entities;

use App\Entity\Donation;

interface DonationEntityInterface
{
    public function persist(Donation $donation): void;

    public function persistAndFlush(Donation $donation): void;

}