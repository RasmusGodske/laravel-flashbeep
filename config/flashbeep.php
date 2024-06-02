<?php

return [
  // The session key to access the flash messages eg. session('flash_messages')
  'session_key' => 'flash_messages',

  // Different presets for flash messages used with the flashMessage() helper
  'presets' => [
    'success' => RasmusGodske\FlashBeep\PresetMessages\SuccessFlash::class,
    'info' => RasmusGodske\FlashBeep\PresetMessages\InfoFlash::class,
    'warn' => RasmusGodske\FlashBeep\PresetMessages\WarnFlash::class,
    'danger' => RasmusGodske\FlashBeep\PresetMessages\DangerFlash::class,
  ]
];