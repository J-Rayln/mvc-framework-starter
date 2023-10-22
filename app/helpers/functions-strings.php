<?php
/**
 * Helper functions that deal with string manipulation.
 */

/**
 * Removes all leading and trailing slashes from the given string.
 *
 * @param string $string
 * @return string
 */
function unslashit(string $string): string
{
    return trim($string, '/\\');
}

/**
 * Ensures the final character in the given string is a forward slash.
 *
 * @param string $string
 * @return string
 */
function trailingslashit(string $string): string
{
    return rtrim($string, '/\\') . '/';
}