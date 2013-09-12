<?php

namespace bDone\Repository;

use Insolis\Repository;

class Admin extends Repository
{
    public function isValid($username, $password)
    {
        $user = $this->db->fetchAssoc(sprintf("SELECT * FROM %s WHERE username = ? LIMIT 1;", $this->getTableName()), array($username));

        return $user && password_verify($password, $user["password"]);
    }

    public function getTableName()
    {
        return "projektneve_admin";
    }
}
