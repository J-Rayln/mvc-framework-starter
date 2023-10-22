<?php

namespace JonathanRayln\UdemyClone\Database;

class Migrations extends Database
{
    public function createUsersTable()
    {
        $this->query("CREATE TABLE IF NOT EXISTS `users` (
             `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
             `email` varchar(100) NOT NULL,
             `password` varchar(255) NOT NULL,
             `date_created` datetime NOT NULL DEFAULT current_timestamp(),
             PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
    }
}