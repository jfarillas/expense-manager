<?php

namespace App\Traits;

use Exception;

trait BenchTrait
{
    private $benches = [];

    public function __construct() { }

    public function start($name)
    {
        if(!isset($this->benches[$name]))
        {
            $this->benches[$name] = [];
        }

        $this->benches[$name]['start'] = [
            'microtime' => microtime(true)
            /* Other information*/
        ];
    }

    public function end($name)
    {
        if(!isset($this->benches[$name]))
        {
            throw new Exception('You must first declare a benchmark for '.$name);
        }

        $this->benches[$name]['end'] = [
            'microtime' => microtime(true)
            /* Other information*/
        ];
    }

    public function calculate($name)
    {
        if(!isset($this->benches[$name]))
        {
            throw new Exception('You must first declare a benchmark for '.$name);
        }

        if(!isset($this->benches[$name]['end']))
        {
            throw new Exception('You must first call an end call for '.$name);
        }

        $compute = $this->benches[$name]['end']['microtime'] - $this->benches[$name]['start']['microtime'];

        return $compute.'ms';
    }
}