<?php

namespace App\Services\Block;

use App\Models\Block;
use Illuminate\Support\Carbon;
use App\Exceptions\BlockValidationException;

class BlockValidator
{
    public $bottomTemp;
    public $upperTemp;
    public $diff;
    public const deviation = 2;

    /**
     * @throws BlockValidationException
     */
    public function __construct(public string $dataStart, public string $dataEnd, public int $volume, public int $temperature)
    {
        $this->required();
        $this->setTemperature();
        $this->checkDate();
    }

    /**
     * @throws BlockValidationException
     */
    public function needBlocks(): int
    {
        if ($this->volume === 0) throw new BlockValidationException("Block volume must be greater than 0");
        return (int)ceil($this->volume / Block::VOLUME_STANDART);
    }

    /**
     * @throws BlockValidationException
     */
    public function setTemperature(): void
    {
        $this->bottomTemp = $this->temperature - static::deviation;
        $this->upperTemp  = $this->temperature + static::deviation;
        if ($this->upperTemp >= 0) throw new BlockValidationException("Fridge temperature must be less than 0");
    }

    /**
     * @throws BlockValidationException
     */
    public function checkDate(): void
    {
        $start = Carbon::create($this->dataStart);
        $end   = Carbon::create($this->dataEnd);
        $this->diff  = $start->diff($end);
        if ($end < $start)
        {
            throw new BlockValidationException("start > end");
        }
        if ($this->diff->y != 0 || $this->diff->m != 0 || ($this->diff->days > 24)) {
            throw new BlockValidationException("The shelf life of products should be in the range of 24 days");
        }
        $this->dataStart = $start->format('Y-m-d H:i:s');
        $this->dataEnd   = $end->format('Y-m-d H:i:s');
    }

    /**
     * @throws BlockValidationException
     */
    public function required(): void
    {
        if (empty($this->dataStart) || empty($this->dataEnd) || empty($this->volume) || empty($this->temperature)) throw new BlockValidationException("Пропущен объязательный параметр");
    }
}
