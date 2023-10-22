<?php

/**
 * Returns an environment variable for a given $key else if $key is empty,
 * returns the specified $default, if any.
 *
 * @param string          $key
 * @param string|int|null $default
 * @return mixed|null
 */
function env(string $key, string|int|null $default = null): mixed
{
    return !empty($_ENV[$key]) ? $_ENV[$key] : $default;
}