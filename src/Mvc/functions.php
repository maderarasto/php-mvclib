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

if (!function_exists('capitalize')) {
    function capitalize(string $text)
    {
        return strtoupper($text[0]) . substr($text, 1);
    }
}