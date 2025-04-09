<?php

namespace App\Traits;

trait LazyVarsTrait
{
    /** lazy vars storage */
    protected array $_lazyVars = [];

    /**
     * @param string $key
     * @param callable $callback
     * @param bool $refresh - ar atnaujinti reiksme
     * @return mixed|null
     */
    protected function getLazyProperty(string $key, callable $callback, bool $refresh = false){
        if(!array_key_exists($key, $this->_lazyVars) || $refresh){
            $this->_lazyVars[$key] = call_user_func_array($callback, []);
        }
        return $this->_lazyVars[$key];
    }
    protected function setLazyProperty(string $key, mixed $value)
    {
        $this->_lazyVars[$key] = $value;
    }
    public function clearLazyProperties():void{
        $this->_lazyVars = [];
    }
}
