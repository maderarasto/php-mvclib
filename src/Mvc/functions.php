<?php

if (!function_exists('normalize_uri')) {
    function normalize_uri(string $uri): string
    {
        if (empty($uri)) {
            return '';
        }

        return trim($uri, '/');
    }
}