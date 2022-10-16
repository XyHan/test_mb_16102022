<?php

namespace MobilityWork\Model\Security;

class UserModel implements UserInterface
{
    private string $id;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return UserInterface
     */
    public function setId(string $id): UserInterface
    {
        $this->id = $id;
        return $this;
    }
}
