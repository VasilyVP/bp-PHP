<?php
namespace middleware;

class Lib
{
    public function __construct()
    {
        
    }

    static public function calc(int $first, int $second)
    {
        return $first * $second;
    }
}