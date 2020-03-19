<?php

declare(strict_types=1);

namespace flotzilla\Logger\Handler;

interface HandlerInterface
{
    /**
     * @param array $record
     * @return bool
     */
    public function handle(array $record): bool;
}
