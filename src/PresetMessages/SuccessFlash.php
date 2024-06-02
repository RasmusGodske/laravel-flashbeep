<?php

namespace RasmusGodske\FlashBeep\PresetMessages;

use RasmusGodske\FlashBeep\FlashMessage\AbstractFlashMessage;

class SuccessFlash extends AbstractFlashMessage
{
    public function __construct(string $summary, ?string $detail=null)
    {
        $this->addAttribute('severity', 'success');
        $this->addAttribute('summary', $summary);
        $this->addAttribute('detail', $detail);
    }
}
