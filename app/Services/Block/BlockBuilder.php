<?php

namespace App\Services\Block;
use App\Models\Block;
use App\Models\Location;
use App\Services\Block\Interfaces\BlockBuilder as BlockBuilderInterface;
use Illuminate\Support\Collection;
use App\Exceptions\BlockCollectionException;

class BlockBuilder implements BlockBuilderInterface
{
    protected BlocksCollection $model;

    protected function reset() : void
    {
        $this->model = new BlocksCollection();
    }

    /**
     * @throws BlockCollectionException
     */
    public function location(int $id) : BlockBuilderInterface
    {
        $this->reset();
        $this->model->location = Location::find($id);
        if (!$this->model->location) throw new BlockCollectionException("Location by id: {$id} does not exist");
        return $this;
    }

    /**
     * @throws BlockCollectionException
     */
    public function fridges(string $condition, int $bottom, int $upper) : BlockBuilderInterface
    {
        $this->model->fridges = $this->model->location->getFridges()->whereBetween('temperature', [$bottom, $upper])->get();
        if (!$this->model->fridges) throw new BlockCollectionException("The fridges at this location has not yet been created");
        return $this;
    }

    public function blocks(string $start, string $end) : BlockBuilderInterface
    {
        foreach ($this->model->fridges as $fridge)
        {
            foreach ($fridge->getBlocks()->orderBy('fridge_id', 'ASC')->get() as $item)
            {
                if ($item->status($start, $end) == Block::FREE_BLOCK) $arrayObj[] = $item;
            }
        }
        $this->model->blocks = new Collection($arrayObj);
        return $this;
    }

    /**
     * @throws BlockCollectionException
     */
    public function collection($limit) : Collection
    {
        $count = count($this->model->blocks);
        if ($limit > $count) throw new BlockCollectionException("Sorry, no blocks were found matching your query. You need {$limit} blocks, {$count} is available");
        $this->model->collection = $this->model->blocks->skip(0)->take($limit);
        return $this->model->collection;
    }
}
