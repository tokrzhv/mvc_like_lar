<?php

namespace App\RMVC\Route;

class RouteDispatcher
{
    private string $requestUri = '/';

    private array $paramMap = [];

    private array $paramRequestMap = [];

    private RouteConfiguration $routeConfiguration;

    /**
     * RouteDispatcher constructor.
     * @param RouteConfiguration $routeConfiguration
     */
    public function __construct(RouteConfiguration $routeConfiguration)
    {
        $this->routeConfiguration = $routeConfiguration;
    }

    public function process()
    {
        // 1.   If isset String of request we should to clear and save it
        //  1.1    Need to clear route string
        $this->saveRequestUri();

        // 2.   Route string to array and store in new array position of parameter and his name
        $this->setParamMap();

        // 3.   Request string to arr + check it this array has a position like the parameter position
        //  3.1     If the position is here -> turn request string to regular expression
        $this->makeRegexRequest();

        // 4.   Launch the Controller and action
        $this->run();


    }

    private function saveRequestUri()
    {
        if ($_SERVER['REQUEST_URI'] !== '/') {
            $this->requestUri = $this->clean($_SERVER['REQUEST_URI']);
            $this->routeConfiguration->route = $this->clean($this->routeConfiguration->route);
        }
    }

    private function clean($str): string
    {
        return preg_replace('/(^\/)|(\/$)/', '', $str);
    }

    private function setParamMap()
    {
        $routeArray = explode('/', $this->routeConfiguration->route);

        foreach ($routeArray as $paramKey => $param) {
            if (preg_match('/{.*}/', $param)) {
                $this->paramMap[$paramKey] = preg_replace('/(^{)|(}$)/', '', $param);
            }
        }
    }

    private function makeRegexRequest()
    {
        $requestUriArray = explode('/', $this->requestUri);

        foreach ($this->paramMap as $paramKey => $param) {
            if (!isset($requestUriArray[$paramKey])) {
                return;
            }
            $this->paramRequestMap[$param] = $requestUriArray[$paramKey];

            $requestUriArray[$paramKey] = '{.*}';

        }
        $this->requestUri = implode('/', $requestUriArray);
        $this->prepareRegex();

    }

    private function prepareRegex()
    {
        $this->requestUri = str_replace('/', '\/', $this->requestUri);
    }

    private function run()
    {
        if (preg_match("/$this->requestUri/", $this->routeConfiguration->route)){
            $this->render();
        }
    }

    private function render()
    {
        $ClassName = $this->routeConfiguration->controller;
        $action = $this->routeConfiguration->action;

        print((new $ClassName)->$action(...$this->paramRequestMap));

        die();
    }

}