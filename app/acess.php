<?php

return [
    "users" => [
        "list"       => ["admin"],
        "employees"  => ["admin"],
        "save"       => ["admin"]
    ],
    "images" => [
        "add" => ["simple","admin","organizator"]
    ],
    "catalog" => [
        "catsEdit"       => ["admin"],
        "discountsTable" => ["admin"],
        "provider"       => ["admin"],
        "purchaseTable"  => ["admin",
            "organizator" => ["user" => $_SESSION['user']['id']]
        ]
    ],
    "main" => [
        "editFaq"          => ["admin"],
        "listDelivery"     => ["admin"],
        "editPageDelivery" => ["admin"],
        "editPagePay"      => ["admin"]
    ],
    "notice" => [
        "editAccount" => ["admin"]
    ]
];

