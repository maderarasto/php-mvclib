<?php

namespace Mvc\Facades;

use Mvc\Facade;
use Mvc\Http\UploadedFile;

/**
 * @method static string method()
 * @method static string uri()
 * @method static bool hasHeader(string $name)
 * @method static string header(string $name, mixed $default = null)
 * @method static bool hasCookie(string $name)
 * @method static string cookie(string $name, mixed $default = null)
 * @method static string query(string $name, mixed $default = null)
 * @method static string post(string $name, mixed $default = null)
 * @method static string json(string $name, mixed $default = null)
 * @method static string input(string $name, mixed $default = null)
 * @method static UploadedFile file(string $name, mixed $default = null)
 * @method static array all()
 * @method static array except(array $keys)
 * @method static array only(array $keys)
 */
class Request extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return 'request';
    }
}