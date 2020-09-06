<?php

$container->set('db_settings', function() {
    return (object)[
        "DB_HOST" => "localhost",
        "DB_NAME" => "qr_turn",
        "DB_USER" => "qrturnuadm",
        "DB_PASS" => "7d8l@oD5",
        "DB_CHAR" => "utf8mb4"
    ];
});
