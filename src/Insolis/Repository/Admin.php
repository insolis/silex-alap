<?php

namespace Insolis\Repository;

use Insolis\Repository;

class Admin extends Repository
{
    public function getTableName()
    {
        return "projektneve_admin";
    }

    public function isValid($username, $password)
    {
        $row = $this->db->fetchAssoc(sprintf("SELECT * FROM %s WHERE username = ? LIMIT 1;", $this->getTableName()), array($username));
        
        return $row && password_verify($password, $row["password"]);
    }
}
