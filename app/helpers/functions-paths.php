<?php
/**
 * Helper functions that deal with URLs and paths.
 */

/**
 * Returns the full URL to the given asset.
 *
 * @param string $path
 * @return string
 */
function asset_url(string $path): string
{
    return env('APP_URL') . 'assets/' . $path;
}

/**
 * Returns the full URL to the given path.
 *
 * @param string $path
 * @return string
 */
function url(string $path = ''): string
{
    return trailingslashit(env('APP_URL')) . unslashit($path);
}