<?php

namespace App\Services\Block\Interfaces;

use App\Services\Block\Interfaces\BlockBuilder as BlockBuilderInterface;
use Illuminate\Support\Collection;

interface BlockBuilder
{
    public function location(int $id) : BlockBuilderInterface;
    public function fridges(string $condition, int $bottom, int $upper) : BlockBuilderInterface;
    public function blocks(string $condition, int $status) : BlockBuilderInterface;
    public function collection($limit) : Collection;
}
