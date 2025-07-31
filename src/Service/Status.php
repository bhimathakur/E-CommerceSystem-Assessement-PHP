<?php

namespace App\Service;

enum Status: string
{
    case ACTIVE = 'Active';
    case INACTIVE = 'Inactive';


    public static function getStatusFromString($status)
    {
        if ($status == 'Active') {
            return self::INACTIVE;
        }
        return self::ACTIVE;

        //  $data = match($status)
        // {
        //     self::ACTIVE => self::ACTIVE,
        //     self::INACTIVE => self::INACTIVE,
        //     default =>  throw new Exception('Not found') 
        // };
        // var_dump($data); exit;

    }
}
