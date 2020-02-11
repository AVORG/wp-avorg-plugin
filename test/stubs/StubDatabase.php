<?php

namespace Avorg;

use natlib\Stub;

class StubDatabase extends Database
{
    use Stub;

    public function updateDatabase()
    {
        return $this->handleCall(__FUNCTION__, func_get_args());
    }

    public function updateSchema()
    {
        return $this->handleCall(__FUNCTION__, func_get_args());
    }

    public function incrementWeight($url, $title)
    {
        return $this->handleCall(__FUNCTION__, func_get_args());
    }

    public function decayWeights()
    {
        return $this->handleCall(__FUNCTION__, func_get_args());
    }

    public function getWeightRow($url, $title)
    {
        return $this->handleCall(__FUNCTION__, func_get_args());
    }

    public function getWeightRows()
    {
        return $this->handleCall(__FUNCTION__, func_get_args());
    }

    public function searchWeightRows($term)
    {
        return $this->handleCall(__FUNCTION__, func_get_args());
    }

    public function insertWeightRow($url, $title, $category)
    {
        return $this->handleCall(__FUNCTION__, func_get_args());
    }
}