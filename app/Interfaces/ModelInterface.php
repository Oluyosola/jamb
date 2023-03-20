<?php

namespace App\Interfaces;

interface ModelInterface
{
    public function index();
    public function store($request);
    public function update($request, $model);
}
