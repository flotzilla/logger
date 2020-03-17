<?php

declare(strict_types=1);

namespace flotzilla\Logger\Handler;

interface HandlerInterface
{
    public function handle(array $record): bool;
}
