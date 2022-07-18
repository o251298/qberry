<?php

namespace App\Services\Block\Interfaces;

use Illuminate\Support\Collection;

interface BlockPrepareInterface
{
    public function setData(string $dateStart, string $dateEnd, int $volume, int $temperature) : BlockValidatorInterface;
    public function getCollections() : Collection;
    public function generateHash() : void;
    public function saveData() : void;
}
