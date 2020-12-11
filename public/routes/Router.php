<?php

namespace routes;

/** Initialization request variables
 *  \FastRoute init
 */
class Router
{
    static private $object;
    private string $httpMethod;
    private string $uri;
    private ?array $queryParameters = null;

    /** Initialize URI and query parameters */
    private function __construct()
    {
        $this->httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        // Get cleared uir and query parameters
        if (false !== $pos = strpos($uri, '?')) {
            parse_str(substr($uri, $pos + 1), $this->queryParameters);
            $uri = substr($uri, 0, $pos);
        }

        $this->uri = rawurldecode($uri);
    }

    /**
     * Object initialization in a single copy
     */
    static public function create()
    {
        if (is_null(self::$object)) self::$object = new self();

        return self::$object;
    }

    /**
     * Dispatcher with all routes classes
     */
    private function getDispatcher()
    {
        return \FastRoute\simpleDispatcher(function (\FastRoute\RouteCollector $r) {
            /** Routes classes should be here */
            new \routes\Pages($r);
        });
    }

    /** Returns [state, handler, vars] */
    public function getRouteInfo()
    {
        return $this->getDispatcher()->dispatch($this->httpMethod, $this->uri);
    }

    public function getHttpMethod()
    {
        return $this->httpMethod;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getQueryParameters()
    {
        return $this->queryParameters;
    }
}
