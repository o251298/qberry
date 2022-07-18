<?php

namespace App\Services\NoSQL;

interface NoSQLStoreInterface
{
    public function save(string $key, string $val) : void;
    public function get(string $key) : string;
    public function exists(string $key) : bool;
}
