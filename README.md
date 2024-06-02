# FlashBeep

FlashBeep is a flexible flash messaging system for Laravel applications. It allows easy integration and customization of flash messages across your application, supporting dynamic instantiation, named parameters, and predefined message types.

FlashBeep makes it easy to create flash messages using the `flashMessage` helper function. The function takes a message object and stores it in the session for display in your views.


## Features

- **Dynamic Message Creation**: Create flash messages on-the-fly using named or positional parameters.
- **Configurable**: Define and customize flash message types via configuration.
- **Extendable**: Easily extend with custom message types.

## Installation

Use Composer to install FlashBeep into your PHP project:

```bash
composer require rasmusgodske/flashbeep
```

# Configuration
After installing the package, comes with a default configuration file. You can publish the configuration file using the following command:

```bash
php artisan vendor:publish --provider="RasmusGodske\FlashBeep\FlashServiceProvider"
```

Edit the `config/flash_messages.php` to set up your message types and session keys:

```php
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
```

To add a custom message type see the [Creating Custom Message Types](#creating-custom-message-types) section.

# Usage

You can use the **flashMessage** function to create and flash a message instantly.

Here are some examples of how you can create and flash messages in your controllers:

```php
public function store(Request $request)
{
  // Using named parameters
  flashMessage('info', [
      'summary' => 'Welcome',
      'detail' => 'Thanks for visiting our site!',
  ]);

  // Using positional parameters
  flashMessage('success', [
    'Operation successful',
    'Your request was processed successfully.'
  ]);

  // Directly passing an instance of a FlashMessageInterface
  flashMessageObj(new InfoFlash('Welcome', 'Thanks for visiting our site!'));

  return redirect()->route('home');
}
```

## Displaying Messages
To display the flashed messages in your views, you can use the **getFlashMessages()**.

getFlashMessages() simply returns an array of FlashMessageInterface objects.
As seen here:
```php

// Using the default preset MessageFlashMessage
class InfoFlash extends AbstractFlashMessage
{
    public function __construct(string $summary, ?string $detail=null)
    {
        $this->addAttribute('severity', 'info');
        $this->addAttribute('summary', $summary);
        $this->addAttribute('detail', $detail);
    }
}

// Add a message to the session
flashMessageObj(new InfoFlash('Welcome', 'Thanks for visiting our site!'));

// Get the messages from the session
$messages = getFlashMessages();

// Messages would be equal to:
$messages = [
  [
    "severity" => "info"
    "summary" => "Welcome",
    "detail" => "Thanks for visiting our site!",
  ]
]
```



### Using with Laravel Blade
Here is an example of how you can display the flashed messages in a Laravel Blade view:
> [!CAUTION]
> This has not been tested yet.

```blade
@if ($messages = getFlashMessages())
    @foreach ($messages as $message)
        <div class="alert alert-{{ $message['severity'] }}">
            <strong>{{ $message['summary'] }}</strong>
            <p>{{ $message['detail'] }}</p>
        </div>
    @endforeach
@endif
```

### Using with InertiaJS
Here is an example of how you can pass the flash messages to inertia using the [HandleInertiaRequests Middleware](https://inertiajs.com/shared-data).

```php
class HandleInertiaRequests extends Middleware
{
    public function share(Request $request)
    {
        return array_merge(parent::share($request), [
            'flashMessages' => getFlashMessages(),
        ]);
    }
}
```


# Creating Custom Message Types

The easiest way to create a custom message type is to extend the `AbstractFlashMessage` class and add your your attributes using `$this->setAttribute('key', 'value')`. Here is an example of a custom message type:

```php
<?php

namespace App\FlashMessages;

use RasmusGodske\FlashBeep\FlashMessage\AbstractFlashMessage;

class MyInfoFlash extends AbstractFlashMessage
{
    public function __construct(string $summary, ?string $detail=null)
    {
        $this->addAttribute('severity', 'info');
        $this->addAttribute('summary', $summary);
        $this->addAttribute('detail', $detail);
    }
}
```

To make your FlashMessage available to use with the `flashMessage` function, you need to add it to the `config/flash_messages.php` file:

```php
return [
    'session_key' => 'flash_messages',
    'presets' => [
        'info' => App\FlashMessages\MyInfoFlash::class, // <-- Add your custom message type here
    ],
];
```

Now you can use your custom message type like this:

```php
public function store(Request $request)
{
  flashMessage('info', [
      'summary' => 'Welcome',
      'detail' => 'Thanks for visiting our site!',
  ]);

  return redirect()->route('home');
}
```

Or without adding it to the configuration file:

```php
public function store(Request $request)
{
  flashMessageObj(new MyInfoFlash('Welcome', 'Thanks for visiting our site!'));

  return redirect()->route('home');
}
```