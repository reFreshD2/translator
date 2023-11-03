<?php

declare(strict_types=1);

namespace app\Translation\Application\Admin\Language\Create;

interface Port
{
    public function handle(Command $command): void;
}
