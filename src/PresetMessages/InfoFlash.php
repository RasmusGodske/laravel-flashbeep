<?php

namespace RasmusGodske\FlashBeep\PresetMessages;

use RasmusGodske\FlashBeep\FlashMessage\AbstractFlashMessage;

class InfoFlash extends AbstractFlashMessage
{
    public function __construct(string $summary, ?string $detail=null)
    {
        $this->addAttribute('severity', 'info');
        $this->addAttribute('summary', $summary);
        $this->addAttribute('detail', $detail);
    }
}
