<?php

namespace Mvc\Routing;

use Mvc\Http\Request;

class Route
{
    /**
     * URI pattern.
     */
    protected string $uri;

    /**
     * URI prefix.
     * @var string
     */
    protected string $prefix;

    /**
     * HTTP methods.
     */
    protected array $methods;

    /**
     * Controller class.
     * @var string
     */
    protected string $controller;

    /**
     * Controller action or callable function.
     * @var string|callable
     */
    protected $action;

    /**
     * Bound parameters.
     * @var array
     */
    protected array $parameters;

    public function __construct(string $uri, array $methods)
    {
        $this->uri = $uri; // TODO: trim slashes and whitespace
        $this->methods = $methods;
        $this->prefix = '';
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function setUri(string $uri)
    {
        $this->uri = normalize_uri($uri);
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    public function setPrefix(string $prefix)
    {
        $this->$prefix = normalize_uri($prefix);
        $this->setUri($this->prefix . '/' . normalize_uri($this->uri));
    }

    /**
     * Checks if a route has bound parameters.
     *
     * @return bool
     */
    public function hasParameters(): bool
    {
        return !empty($this->parameters);
    }

    /**
     * Checks if a route has a specific parameter.
     *
     * @param $name
     * @return bool
     */
    public function hasParameter(string $name): bool
    {
        return isset($this->parameters[$name]);
    }

    /**
     * Gets a route parameter. If the parameter does not exist, the default value is returned.
     *
     * @param string $name
     * @param $default
     * @return mixed|null
     */
    public function getParameter(string $name, $default = null)
    {
        if (!$this->hasParameter($name)) {
            return $default;
        }

        return $this->parameters[$name];
    }

    /**
     * Sets a route parameter.
     *
     * @param string $name
     * @param $value
     */
    public function setParameter(string $name, $value)
    {
        $this->parameters[$name] = $value;
    }

    public function bindParameters(Request $request)
    {
        preg_match_all('/\{([a-zA-Z_][a-zA-Z0-9_]*)}/', $this->uri, $matches);
        [, $parameters] = array_pad($matches, 2, []);

        preg_match($this->getUriRegex(), $request->uri(), $matches);
        $matches = array_pad($matches, 1, null);
        array_shift($matches);

        $this->parameters = array_combine($parameters, $matches);
        $request->setRoute($this);
    }

    /**
     * Checks if a route is associated with a controller action.
     * @return bool
     */
    public function isControllerAction(): bool
    {
        return is_string($this->action);
    }

    /**
     * Gets the controller class associated with the route.
     *
     * @return string|null
     */
    public function getControllerClass(): ?string
    {
        return $this->isControllerAction() ? $this->controller : null;
    }

    /**
     * Gets the controller instance associated with the route.
     *
     * @return void
     */
    public function getController()
    {
        // TODO: if it's a controller action, get the controller class
    }

    /**
     * Sets the controller action or callable action
     *
     * @param array|callable $action
     */
    public function setAction($action)
    {
        if (empty($action)) {
            $this->clearControllerAction();
        }

        $this->action = $action;
    }

    /**
     * Clears action associated with the route.
     */
    public function clearControllerAction()
    {
        $this->controller = null;
        $this->action = null;
    }

    public function match(Request $request): bool
    {
        if (!in_array($request->method(), $this->methods)) {
            return false;
        }

        return preg_match($this->getUriRegex(), $request->uri());
    }

    public function getUriRegex(): string
    {
        $regex = preg_replace('/\//', '\/', $this->uri);
        $regex = preg_replace('/{[a-zA-Z_][a-zA-Z0-9_]*}/', '([^\/]*)', $regex);

        return '/' . $regex . '/';
    }

    public function run()
    {
        if (!$this->isControllerAction()) {
            call_user_func($this->action);
            return;
        }

        // TODO: run controller action
    }
}