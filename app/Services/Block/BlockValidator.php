<?php

namespace App\Services\Block;

use App\Models\Block;
use App\Services\Block\Interfaces\BlockValidatorInterface;
use Illuminate\Support\Carbon;
use App\Exceptions\BlockValidationException;

class BlockValidator implements BlockValidatorInterface
{
    public string $bottomTemp;
    public string $upperTemp;
    public \DateInterval $diff;

    public const deviation = 2;

    /**
     * @throws BlockValidationException
     */
    public function __construct(public string $dataStart, public string $dataEnd, public int $volume, public int $temperature)
    {
        $this->checkRequiredFields();
        $this->setTemperature();
        $this->checkDate();
    }

    /**
     * @throws BlockValidationException
     */
    public function needBlocks(): int
    {
        // Проверка на кол-во блоков
        if ($this->volume === 0 || $this->volume < 0) throw new BlockValidationException("Block volume must be greater than 0");
        // Возвращаем кол-во блоков, для подсчета используется стандартный блок с объемом в 2м3
        return (int)ceil($this->volume / Block::VOLUME_STANDART);
    }

    /**
     * @throws BlockValidationException
     */
    public function setTemperature(): void
    {
        // Установка верхнего допустимого значения
        $this->bottomTemp = $this->temperature - static::deviation;
        // Установка нижнего допустимого значения
        $this->upperTemp = $this->temperature + static::deviation;
        if ($this->upperTemp >= 0) throw new BlockValidationException("Fridge temperature must be less than 0");
    }

    /**
     * @throws BlockValidationException
     */
    public function checkDate(): void
    {
        // Создаем обьект класса Карбон и передаем значения даты начала оренды и даты конца
        $start = Carbon::create($this->dataStart);
        $end   = Carbon::create($this->dataEnd);
        // Сравниваем даты для проверки интервала между ними, по умолчанию интервал не должен быть больше 24 дней
        $this->diff = $start->diff($end);
        if ($end < $start) {
            throw new BlockValidationException("The start date must be equal to or greater than the end date");
        }
        if ($this->diff->y != 0 || $this->diff->m != 0 || ($this->diff->days > 24)) {
            throw new BlockValidationException("The shelf life of products should be in the range of 24 days");
        }
        // Устанавливаем свойства оьбекта, для дальнейшей работы с ними
        $this->dataStart = $start->format('Y-m-d H:i:s');
        $this->dataEnd   = $end->format('Y-m-d H:i:s');
    }

    /**
     * @throws BlockValidationException
     */
    public function checkRequiredFields(): void
    {
        // проверка на заполнение обязательных полей
        if (empty($this->dataStart) || empty($this->dataEnd) || empty($this->volume) || empty($this->temperature)) throw new BlockValidationException("Required parameter missing");
    }
}
