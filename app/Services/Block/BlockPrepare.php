<?php

namespace App\Services\Block;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use App\Services\Block\v2\BlockBuilder;
class BlockPrepare
{
    public BlockValidator $data;
    public Collection $collection;
    public string $hash;
    /**
     * @throws \App\Exceptions\BlockValidationException
     */
    public function __construct(protected string $dateStart, protected string $dateEnd, protected int $volume, protected int $temperature, protected int $location)
    {
        $this->data = $this->setData($dateStart, $dateEnd, $volume, $temperature);
        $this->collection = $this->getCollections();
        $this->redisSaveData();
    }
    /**
     * @throws \App\Exceptions\BlockValidationException
     */
    protected function setData($dateStart, $dateEnd, $volume, $temperature) : BlockValidator
    {
        return new BlockValidator($dateStart, $dateEnd, $volume, $temperature);
    }

    protected function getCollections() : Collection
    {
        return (new BlockBuilder())->location($this->location)
            ->fridges('temperature', $this->data->bottomTemp, $this->data->upperTemp)
            ->blocks($this->data->dataStart, $this->data->dataEnd)
            ->collection($this->data->needBlocks());
    }

    protected function generateHash() : void
    {
        $serializeCollection  = serialize($this->collection->all());
        $this->hash = Hash::make($serializeCollection);
    }

    protected function redisSaveData() : void
    {
        $this->generateHash();
        Redis::set($this->hash, serialize($this->collection->all()));
        Redis::set($this->hash . "&time", json_encode(['start' => $this->data->dataStart, 'end' => $this->data->dataEnd]));
    }

}
