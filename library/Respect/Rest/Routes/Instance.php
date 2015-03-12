<?php

namespace Respect\Rest\Routes;

use ReflectionMethod;
use InvalidArgumentException;
use Respect\Rest\Routable;

class Instance extends AbstractRoute
{
    public $class = '';
    protected $instance = null;
    /** @var ReflectionMethod */
    protected $reflection;
    protected $methodInstance;

    public function __construct($method, $pattern, $instance, $methodInstance = null)
    {
        $this->instance = $instance;
        $this->class = get_class($instance);
        $this->methodInstance = $methodInstance;
        parent::__construct($method, $pattern);
    }

    public function getReflection($method)
    {
        if (empty($this->reflection))
             $this->reflection = new ReflectionMethod(
                    $this->instance, $this->methodInstance != null ? $this->methodInstance : $method
            );

        return $this->reflection;
    }

    public function runTarget($method, &$params)
    {
        if (!$this->instance instanceof Routable)
            throw new InvalidArgumentException(''); //TODO

        return $this->methodInstance != null ? call_user_func_array(
            array($this->instance, $this->methodInstance),
            $params
        ) : call_user_func_array(
            array($this->instance, $method),
            $params
        );
    }

}
