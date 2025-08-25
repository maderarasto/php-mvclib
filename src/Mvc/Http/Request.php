<?php

namespace Mvc\Http;

use Mvc\Routing\Route;

class Request
{
    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';
    public const METHOD_PUT = 'PUT';
    public const METHOD_PATCH = 'PATCH';
    public const METHOD_DELETE = 'DELETE';

    /**
     * HTTP method.
     * @var string
     */
    protected string $method;

    /**
     * Request URI.
     * @var string
     */
    protected string $uri;

    /**
     * Request headers.
     * @var array
     */
    protected array $headers;

    /**
     * Request cookies.
     * @var array
     */
    protected array $cookies;

    /**
     * Request query parameters.
     * @var array
     */
    protected array $query;

    /**
     * Request POST parameters.
     * @var array
     */
    protected array $post;

    /**
     * Request JSON parameters.
     * @var array
     */
    protected array $json;

    /**
     * Request files.
     * @var array<UploadedFile>
     */
    protected array $files;

    protected Route $route;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = urldecode($_SERVER['REQUEST_URI']);
        $this->headers = getallheaders();
        $this->cookies = $_COOKIE;
        $this->query = $_GET;
        $this->post = $_POST;
        $this->json = json_decode(file_get_contents('php://input'), true) ?? [];
        $this->files = array_combine(array_keys($_FILES), array_map(function ($file) {
            return new UploadedFile($file['tmp_name'], $file['name'], $file['type'], $file['error']);
        }, $_FILES));
    }

    /**
     * Gets the HTTP method.
     * @return string
     */
    public function method(): string
    {
        return $this->method;
    }

    /**
     * Gets the request URI.
     * @return string
     */
    public function uri(): string
    {
        return $this->uri;
    }

    public function route(): Route
    {
        return $this->route;
    }

    public function setRoute(Route $route)
    {
        $this->route = $route;
    }

    /**
     * Checks if a header exists in the request.
     * @param string $name
     * @return bool
     */
    public function hasHeader(string $name): bool
    {
        return isset($this->headers[$name]);
    }

    /**
     * Gets a header value from the request.
     *
     * @param string $name
     * @param mixed|null $default
     * @return string
     */
    public function header(string $name, mixed $default = null): string
    {
        return $this->hasHeader($name) ? $this->headers[$name] : $default;
    }

    /**
     * Checks if a cookie exists in the request.
     *
     * @param string $name
     * @return bool
     */
    public function hasCookie(string $name): bool
    {
        return isset($this->cookies[$name]);
    }

    /**
     * Gets a cookie value from the request.
     * @param string $name
     * @param mixed|null $default
     * @return string
     */
    public function cookie(string $name, mixed $default = null): string
    {
        return $this->hasCookie($name) ? $this->cookies[$name] : $default;
    }

    /**
     * Gets a query parameter value from the request.
     * @param string $name
     * @param mixed|null $default
     * @return string
     */
    public function query(string $name, mixed $default = null): string
    {
        return $this->query[$name] ?? $default;
    }

    /**
     * Gets a POST parameter value from the request.
     * @param string $name
     * @param mixed|null $default
     * @return string
     */
    public function post(string $name, mixed $default = null): string
    {
        return $this->post[$name] ?? $default;
    }

    /**
     * Gets a JSON parameter value from the request.
     * @param string $name
     * @param mixed|null $default
     * @return string
     */
    public function json(string $name, mixed $default = null): string
    {
        return $this->json[$name] ?? $default;
    }

    /**
     * Gets a parameter value from the request.
     * @param string $name
     * @param mixed|null $default
     * @return string
     */
    public function input(string $name, mixed $default = null): string
    {
        return $this->json($name) ?? $this->post($name) ?? $this->query($name) ?? $default;
    }

    /**
     * Gets an uploaded file from the request.
     * @param string $name
     * @param mixed|null $default
     * @return UploadedFile
     */
    public function file(string $name, mixed $default = null): UploadedFile
    {
        return $this->files[$name] ?? $default;
    }

    /**
     * Gets all request parameters.
     * @return array
     */
    public function all(): array
    {
        return array_merge($this->query, $this->post, $this->json);
    }

    /**
     * Gets all request parameters except the specified keys.
     * @param array $keys
     * @return array
     */
    public function except(array $keys): array
    {
        return array_diff_key($this->all(), array_flip($keys));
    }

    /**
     * Gets only the specified request parameters.
     * @param array $keys
     * @return array
     */
    public function only(array $keys): array
    {
        return array_intersect_key($this->all(), array_flip($keys));
    }
}