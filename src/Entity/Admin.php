<?php

namespace App\Entity;



use Doctrine\ORM\Mapping\Entity;

#[Entity]
class Admin extends Utilisateur
{
    //Status: Un admin peut être "ABS", "ACTIF" ou "INACTIF"
    //Text
    #[Column(type: 'string', length: 255)]
    private $status;

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }
}
