<?php 

namespace App\DTO;

/** 
 * DTO for pagination, filtering and ordering.
 * It can be used to encapsulate the parameters for pagination, filtering and ordering in a single object.
 * This can be useful when you want to pass these parameters around in your application, for example from a controller to a service or repository.
 * It can also help to keep your code clean and organized by grouping related parameters together.
 * 
 * Example of usage:
 *  $paginationDTO = new PaginationDTO();
 *  $paginationDTO->page = 2;
 *  $paginationDTO->limit = 10;
 *  $paginationDTO->filters[] = new FilterDTO('price', '>', 100);
 *  $paginationDTO->orders[] = new OrderDTO('createdAt', 'DESC');
 * 
 */
class PaginationDTO
{
    /** @var FilterDTO[] */
    public array $filters = [];

    /** @var OrderDTO[] */
    public array $orders = [];

    public int $page = 1;

    public int $limit = 20;

    public function getOffset(): int
    {
        return ($this->page - 1) * $this->limit;
    }
}