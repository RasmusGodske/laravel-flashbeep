<?php

use RasmusGodske\FlashBeep\FlashService;
use RasmusGodske\FlashBeep\FlashMessage\FlashMessageInterface;
use RasmusGodske\FlashBeep\FlashMessage\AbstractFlashMessage;

function flashMessageObj(FlashMessageInterface $message): FlashService
{
    $flashService = app(FlashService::class);
    $flashService->addMessage($message);

    return $flashService;
}

function flashMessage(string $type, array $params = []): FlashService
{
    $presets = config('flashbeep.presets', []);

    if (!array_key_exists($type, $presets)) {
      throw new InvalidArgumentException("Flash message type '{$type}' is not configured. Check your flashbeep.php config file.");
    }

    $className = $presets[$type];
    $reflectionClass = new ReflectionClass($className);
    $constructor = $reflectionClass->getConstructor();
    $args = [];
    if ($constructor) {
        $paramRefs = $constructor->getParameters();
        $expectedParams = [];
        foreach ($paramRefs as $param) {
            $expectedParams[$param->getName()] = $param;
        }

        // Determine if $params is purely associative or purely indexed
        $isAssoc = count(array_filter(array_keys($params), 'is_string')) > 0;
        $isIndexed = count(array_filter(array_keys($params), 'is_int')) > 0;

        // Ensure $params is not mixed and handle error for unexpected parameters
        if ($isAssoc && $isIndexed) {
            throw new InvalidArgumentException("Mixed types of parameters (both named and positional) are not allowed.");
        } elseif ($isAssoc) {
            foreach ($params as $name => $value) {
                if (!array_key_exists($name, $expectedParams)) {
                    throw new InvalidArgumentException("Unexpected parameter '{$name}' is not expected for constructor of {$className}.");
                }
            }
        } else {
            if (count($params) > count($paramRefs)) {
                throw new InvalidArgumentException("Too many arguments provided for the constructor of {$className}. Expected " . count($paramRefs) . " arguments, got " . count($params) . ".");
            }
        }

        foreach ($paramRefs as $index => $param) {
            $name = $param->getName();

            if ($isAssoc) {
                if (array_key_exists($name, $params)) {
                    $args[$name] = $params[$name];
                } elseif ($param->isDefaultValueAvailable()) {
                    $args[$name] = $param->getDefaultValue();
                } else {
                    throw new InvalidArgumentException("Missing mandatory parameter '{$name}' for constructor of {$className}.");
                }
            } else {
                // Handle as positional parameters
                if (array_key_exists($index, $params)) {
                    $args[] = $params[$index];
                } elseif ($param->isDefaultValueAvailable()) {
                    $args[] = $param->getDefaultValue();
                } else {
                    throw new InvalidArgumentException("Missing mandatory parameter '{$name}' at position {$index} for constructor of {$className}.");
                }
            }
        }
    }
    $message = $reflectionClass->newInstanceArgs($args);

    return flashMessageObj($message);
}



function getFlashMessages(): array
{
    $flashService = app(FlashService::class);
    return $flashService->getCurrentMessages();
}

