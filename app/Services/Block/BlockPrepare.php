<?php

namespace App\Services\Block;

use App\Services\Block\Interfaces\BlockValidatorInterface;
use App\Services\NoSQL\NoSQLStoreInterface;
use App\Services\NoSQL\RedisStore;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use App\Services\Block\v2\BlockBuilder;
use App\Services\Block\Interfaces\BlockPrepareInterface;

class BlockPrepare implements BlockPrepareInterface
{
    public BlockValidatorInterface $data;
    public Collection $collection;
    public string $hash;
    protected NoSQLStoreInterface $store;
    protected Hash $hashGenerator;

    /**
     * @throws \App\Exceptions\BlockValidationException
     */
    public function __construct(protected string $dateStart, protected string $dateEnd, protected int $volume, protected int $temperature, protected int $location)
    {
        $this->hashGenerator = new Hash();
        $this->store         = new RedisStore();
        $this->data          = $this->setData($dateStart, $dateEnd, $volume, $temperature);
        $this->collection    = $this->getCollections();
        $this->saveData();
    }

    /**
     * @throws \App\Exceptions\BlockValidationException
     */
    public function setData(string $dateStart, string $dateEnd, int $volume, int $temperature): BlockValidatorInterface
    {
        return new BlockValidator($dateStart, $dateEnd, $volume, $temperature);
    }

    public function getCollections(): Collection
    {
        return (new BlockBuilder())->location($this->location)
            ->fridges('temperature', $this->data->bottomTemp, $this->data->upperTemp)
            ->blocks($this->data->dataStart, $this->data->dataEnd)
            ->collection($this->data->needBlocks());
    }

    public function generateHash(): void
    {
        $serializeCollection = serialize($this->collection->all());
        $this->hash          = $this->hashGenerator::make($serializeCollection);

    }

    public function saveData(): void
    {
        $this->generateHash();
        $this->store->save($this->hash, serialize($this->collection->all()));
        $this->store->save($this->hash . "&time", json_encode(['start' => $this->data->dataStart, 'end' => $this->data->dataEnd]));
    }

}
