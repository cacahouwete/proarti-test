<?php

namespace App\Interfaces\entities;

use App\Entity\Reward;

interface RewardEntityInterface
{
    public function findByName(string $rewardName): Reward;

    public function persist(Reward $reward): void;

    public function persistAndFlush(Reward $reward): void;

    public function findById(int $id): Reward;

}