<?php

namespace App\Modules\Cart\Application\Usecases\Exceptions;

class ProductNotFoundException extends \Exception {
    public function __construct() 
    {
        parent::__construct('Product Not Found');
    }
}