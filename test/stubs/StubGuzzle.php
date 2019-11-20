<?php

namespace Avorg;

use natlib\Stub;

class StubGuzzle extends Guzzle
{
    use Stub;

    public function handleNew(string $method, string $endpoint, array $options = [])
    {
        return $this->handleCall(__FUNCTION__, func_get_args());
    }

    public function handleOld(string $method, string $endpoint, array $options = [])
    {
        return $this->handleCall(__FUNCTION__, func_get_args());
    }
}