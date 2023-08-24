<?php

namespace System;

class Router
{
    private array $handlers = [];
    private $notFoundHandler;
    private const METHOD_POST = 'POST';
    private const METHOD_GET = 'GET';
    private const METHOD_DELETE = 'DELETE';
    private $middlewareIndex = 0;
    private $middlewareNextIndex = false;

    private function addHandler(string $method, string $path, $middlewares, $handler): void
    {

        $this->handlers[$method][] = [
            'path' => $path,
            'method' => $method,
            'handler' => $handler,
            'middlewares' => $middlewares
        ];
    }

    public function get(string $path, $firstHandler, $secondHandler = null): void
    {

        $middlewares = $firstHandler;
        $handler = $secondHandler;

        if (empty($secondHandler)) {
            $handler = $firstHandler;
            $middlewares = [];
        }
        $this->addHandler(self::METHOD_GET, $path, $middlewares, $handler);
    }

    public function post(string $path, $firstHandler, $secondHandler = null): void
    {

        $middlewares = $firstHandler;
        $handler = $secondHandler;

        if (empty($secondHandler)) {
            $handler = $firstHandler;
            $middlewares = [];
        }
        $this->addHandler(self::METHOD_POST, $path, $middlewares, $handler);
    }
    public function delete(string $path, $firstHandler, $secondHandler = null): void
    {

        $middlewares = $firstHandler;
        $handler = $secondHandler;

        if (empty($secondHandler)) {
            $handler = $firstHandler;
            $middlewares = [];
        }
        $this->addHandler(self::METHOD_DELETE, $path, $middlewares, $handler);
    }

    public function any($handler): void
    {
        $this->notFoundHandler = $handler;
    }

    public function runMiddleware($handlers)
    {

        $this->middlewareNextIndex = false;
        call_user_func_array($handlers[$this->middlewareIndex], [function () {
            $this->middlewareNextIndex = true;
        }]);

        if ($this->middlewareNextIndex) {
            $this->middlewareIndex += 1;
            if ($this->middlewareIndex < count($handlers)) {
                return $this->runMiddleware($handlers);
            } else {
                return true;
            }
        } else {
            die;
        }
    }

    public function run()
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI']);
        $requestPath = $requestUri['path'];
        $method = $_SERVER['REQUEST_METHOD'];

        $callback = null;
        $params = [];
        $cbMiddlewares = [];
        $middlewares = [];

        $matched = false;

        foreach ($this->handlers[$method] as $handler) {
            if ($matched) {
                continue;
            }

            $path = $handler['path'];

            $path = str_replace("\\", "\\\\", $path);


            $explodePath = explode("/", $path);

            $arrayPath = [];
            foreach ($explodePath as $value) {
                if ($value != "") {
                    $arrayPath[] = $value;
                }
            }

            $explodeReqPath = explode("/", $requestPath);


            $arrayReqPath = [];
            foreach ($explodeReqPath as $value) {
                if ($value != "") {
                    $arrayReqPath[] = $value;
                }
            }

            $newParams = [];

            if ($requestPath == "/" && $requestPath === $path) {
                $matched = true;
            } else {

                $skip = false;
                $any = false;

                foreach ($arrayReqPath as $key => $value) {

                    if ($skip) {
                        continue;
                    }

                    if (!empty($arrayPath[$key]) || $any) {

                        if (!$any && $arrayPath[$key] != $value) {

                            if (substr($arrayPath[$key], 0, 1) == ":") {
                                if ($arrayPath[$key] == ":any") {
                                    $any = true;
                                } else {
                                    $newParams[substr($arrayPath[$key], 1, strlen($arrayPath[$key]))] = $value;
                                }
                            } else {
                                $skip = true;
                            }
                        }
                    } else {
                        $skip = true;
                    }

                    if (count($arrayReqPath) - 1 == $key && !$any) {
                        if (!empty($arrayPath[$key + 1]) && $arrayPath[$key + 1] != "") {
                            $skip = true;
                        }
                    }
                }


                if (!$skip && $method === $handler['method']) {
                    if (count($arrayReqPath) != 0 && count($arrayPath) != 0) {
                        $matched = true;
                    }
                }
            }



            if ($matched) {
                $callback = $handler['handler'];
                $params = $newParams;
                $middlewares = $handler['middlewares'];
            }
        }


        foreach ($middlewares as $middleware) {
            $className = $middleware;
            $handler = new $className;

            $cbMiddlewares[] = [$handler, "handle"];
        }

        if (!$callback) {
            if (!empty($this->notFoundHandler)) {
                $callback = $this->notFoundHandler;
            }
        }


        if (is_array($callback)) {
            $className = array_shift($callback);
            $handler = new $className;

            $method = array_shift($callback);
            $callback = [$handler, $method];
        }



        if (count($cbMiddlewares) > 0) {

            $this->runMiddleware($cbMiddlewares);
        }

        call_user_func_array($callback, [(object) $params]);
    }
}
