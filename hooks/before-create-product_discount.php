<?php

if(isset($_GET['filter']) && isset($_GET['filter']['product_id']))
{
    $data['product_id'] = $_GET['filter']['product_id'];
}