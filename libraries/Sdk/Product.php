<?php

namespace Modules\Commerce\Libraries\Sdk;

use Core\Database;

class Product
{
    private $data = [];
    private $db   = null;
    
    public function __construct(array $clause = [])
    {
        $this->db   = new Database;
        $this->data = $this->db->single('products', $clause);
    }

    function getPrice()
    {
        $price = $this->data->price;

        // find product price
        // $productPrice
    }
}