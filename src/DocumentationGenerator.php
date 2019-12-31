<?php


namespace Waxwink\DocGen;


use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use ReflectionClass;
use ReflectionMethod;

class DocumentationGenerator
{
    public function generate()
    {
        $routes = [];
        /** @var \Illuminate\Routing\Route $route */
        foreach (Route::getRoutes()->getIterator() as $route){
            $routes = $this->routes($route, $routes);

        }

        return $routes;

    }

    /**
     * @param \Illuminate\Routing\Route $route
     * @param array $routes
     * @return array
     * @throws \App\Exceptions\ResourceClassNotFoundException
     * @throws \ReflectionException
     */
    protected function routes(\Illuminate\Routing\Route $route, array $routes): array
    {
        $item = [];
        $item['uri'] = $route->uri;
        $item['methods'] = $route->methods;
        $action = $route->getAction();
        $item['middleware'] = (array_key_exists("middleware", $action)) ? $action["middleware"] : "";


        if (array_key_exists("controller", $action)) {
            $item = $this->setControllerParams($action, $item);

        }

        $routes[$route->getPrefix()][] = $item;
        return $routes;
    }

    /**
     * @param $action
     * @param array $item
     * @return array
     * @throws \App\Exceptions\ResourceClassNotFoundException
     * @throws \ReflectionException
     */
    protected function setControllerParams($action, array $item): array
    {
        list($class, $method) = $this->resolveControllerNameAndMethod($action);
        $item["controller"] = $class;
        $item["controller_method"] = $method;

        $item = $this->setOutputKeys($item, $class, $method);

        try {
            $item = $this->setRouteParams($item, $class, $method);
        } catch (Exception $exception) {
            //
        }
        return $item;
    }

    /**
     * @param $action
     * @return array
     */
    protected function resolveControllerNameAndMethod($action): array
    {
        $array = explode("@", $action["controller"]);
        $class = $array[0];
        $method = $array[1];
        return array($class, $method);
    }

    /**
     * @param array $item
     * @param $class
     * @param $method
     * @return array
     * @throws \ReflectionException
     */
    protected function setRouteParams(array $item, $class, $method): array
    {
        $ref = new ReflectionMethod($class, $method);
        $params = $ref->getParameters();
        $item["params"] = [];
        foreach ($params as $param) {
            $paramName = ($param->getType()) ? $param->getType()->getName() : null;
            $item["params"][] = ($paramName)??"";

            if (is_string($paramName)) {
                $item = $this->setRequestBody($item, $paramName);
            }


        }
        return $item;
    }

    /**
     * @param array $item
     * @param string $paramName
     * @return array
     */
    protected function setRequestBody(array $item, string $paramName): array
    {
        if (! class_exists($paramName)){
            return $item;
        }

        if (! array_key_exists(FormRequest::class,class_parents($paramName))){
            return $item;
        }

        $object = new $paramName();

        /** @var FormRequest $object */
        $item = $this->setFormRequestRules($item, $object);

        return $item;
    }

    /**
     * @param array $item
     * @param FormRequest $object
     * @return array
     */
    protected function setFormRequestRules(array $item, FormRequest $object): array
    {
        $item["request_body"] = $object->rules();
        return $item;
    }

    /**
     * @param array $item
     * @param $class
     * @param $method
     * @param $resource
     * @return array
     * @throws \App\Exceptions\ResourceClassNotFoundException
     * @throws \ReflectionException
     */
    protected function setOutputKeys(array $item, $class, $method)
    {
        try{
            $doc = ((new ReflectionClass($class))
                ->getMethod($method)->getDocComment());
        }catch (\ReflectionException $exception){
            return $item;
        }


        if (preg_match('/@DG-Resource (.*)/', $doc, $resource)) {
            $resourceClass = trim($resource[1]);
            $item["output_keys"] = (new \Waxwink\DocGen\Resource())->keys($resourceClass);
        }
        return $item;
    }

}