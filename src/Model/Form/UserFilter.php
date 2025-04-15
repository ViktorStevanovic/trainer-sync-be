<?php

namespace App\Model\Form;

use App\Entity\UserType\UserType;

class UserFilter
{
    private ?UserType $userType = null;

    // private ?bool $status = null;

    /**
     * Get the value of userType
     */
    public function getUserType(): ?UserType
    {
        return $this->userType;
    }

    /**
     * Set the value of userType
     */
    public function setUserType(?UserType $userType): self
    {
        $this->userType = $userType;

        return $this;
    }

    // /**
    //  * Get the value of status
    //  */
    // public function getStatus(): ?bool
    // {
    //     return $this->status;
    // }

    // /**
    //  * Set the value of status
    //  */
    // public function setStatus(?bool $status): self
    // {
    //     $this->status = $status;

    //     return $this;
    // }
}
