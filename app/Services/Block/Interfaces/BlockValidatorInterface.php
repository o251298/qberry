<?php

namespace App\Services\Block\Interfaces;

interface BlockValidatorInterface
{
    public function needBlocks() : int;
    public function setTemperature() : void;
    public function checkDate(): void ;
    public function checkRequiredFields(): void ;
}
