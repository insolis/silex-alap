<?php

namespace Insolis\Repository;

use Knp\Repository;

class Admin extends Repository
{
    public function getTableName()
    {
        return "projektneve_admin";
    }

    public function isValid($username, $password)
    {
        $row = $this->db->fetchAssoc(sprintf("SELECT * FROM %s WHERE username = :username ", $this->getTableName()), array("username" => $username));
        
        return $row && password_verify($password, $row["password"]);
    }
}
