<?php

namespace RasmusGodske\FlashBeep\PresetMessages;

use RasmusGodske\FlashBeep\FlashMessage\AbstractFlashMessage;

class DangerFlash extends AbstractFlashMessage
{
    public function __construct(string $summary, ?string $detail=null)
    {
        $this->addAttribute('severity', 'danger');
        $this->addAttribute('summary', $summary);
        $this->addAttribute('detail', $detail);
    }
}
