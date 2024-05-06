<?php

use Core\Database;
use Core\Response;

$auth = auth();

if(empty($auth))
{
    header('location:'.routeTo('auth/login'));
    die;
}

return true;