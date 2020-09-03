<?php

$container->set('db_settings', function() {
    return (object)[
        "DB_HOST" => "localhost",
        "DB_NAME" => "qr_turn",
        "DB_USER" => "manuel",
        "DB_PASS" => "Guti.1712*",
        "DB_CHAR" => "utf8mb4"
    ];
});
