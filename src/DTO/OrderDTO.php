<?php

namespace App\DTO;

/** 
 * Example of usage:
 *  createdAt DESC
 *  price ASC
 * */ 
class OrderDTO
{
    public string $field;
    public string $direction;

    public function __construct(string $field, string $direction = 'ASC')
    {
        $this->field = $field;
        $this->direction = strtoupper($direction);
    }
}