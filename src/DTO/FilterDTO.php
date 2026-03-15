<?php
namespace App\DTO;

/** 
 * Example of usage:
 *  price > 100
 *  name LIKE '%John%'
 * */ 
class FilterDTO
{
    public string $field;
    public string $operator;
    public mixed $value;

    public function __construct(string $field, string $operator, mixed $value)
    {
        $this->field = $field;
        $this->operator = $operator;
        $this->value = $value;
    }
}