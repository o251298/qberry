<?php

namespace App\Services\Block\v2;

use App\Models\Block;
use App\Services\Block\Interfaces\BlockBuilder as BlockBuilderInterface;
use App\Services\Timezone\TimezoneCreator;
use Illuminate\Support\Collection;
use App\Services\Block\BlockBuilder as BlockBuilderV1;

class BlockBuilder extends BlockBuilderV1
{
    public function blocks(string $start, string $end): BlockBuilderInterface
    {
        $timezone  = $this->model->location->timezone;
        $fridge_id = [];
        foreach ($this->model->fridges as $fridge) {
            $fridge_id[] = $fridge->id;
        }
        $blocks              = Block::getBlockTest(TimezoneCreator::createServerDate($timezone, $start), TimezoneCreator::createServerDate($timezone, $end), $fridge_id);
        $this->model->blocks = $blocks->get();
        return $this;
    }
}
