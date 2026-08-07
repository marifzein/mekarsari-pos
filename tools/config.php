<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Database
    |--------------------------------------------------------------------------
    */

    'host'      => '127.0.0.1',
    'port'      => 3306,
    'database'  => 'kasir_pos_db',
    'username'  => 'adminpos',
    'password'  => 'samidi123',

    /*
    |--------------------------------------------------------------------------
    | mysqldump.exe
    |--------------------------------------------------------------------------
    */

     // lokasi mysqldump.exe
    'mysqldump' => 'D:\\xampp\\mysql\\bin\\mysqldump.exe',

    'timezone' => 'Asia/Jakarta',
    
    /*
    |--------------------------------------------------------------------------
    | Folder
    |--------------------------------------------------------------------------
    */

    'backup_path'  => __DIR__.'/../backup',

    'release_path' => __DIR__.'/../release',

    /*
    |--------------------------------------------------------------------------
    | Transaction Tables
    |--------------------------------------------------------------------------
    */

    'transaction_tables' => [

        'transaction_details',
        'transactions',

        'purchase_order_items',
        'purchase_orders',

        'stock_movements',

        'stock_opname_details',
        'stock_opnames',

        'cache',
        'cache_locks',

        'sessions',

        'jobs',
        'job_batches',
        'failed_jobs',

        'password_reset_tokens',

    ]

];