<?php

namespace RasmusGodske\FlashBeep;

use Illuminate\Support\Facades\Config;
use RasmusGodske\FlashBeep\FlashMessage\FlashMessageInterface;
use Illuminate\Support\Facades\Session;

class FlashService
{

    public function addMessage(FlashMessageInterface $message): void
    {
        $messages = $this->getCurrentMessages();
        $messages[] = $message->toArray();
        Session::flash(Config::get('flash_messages.session_key', 'flash_messages'), $messages);
    }

    public function getCurrentMessages(): array
    {
        return Session::get(Config::get('flash_messages.session_key', 'flash_messages'), []);
    }
}