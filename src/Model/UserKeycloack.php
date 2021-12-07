<?php 

declare(strict_types=1);

namespace Amiltone\KeycloackTokenBundle\Model;

class UserKeycloack
{

    /**
     * @var string
     */
    private $id;

    /** 
     * @var string
     */
    private $userName;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;
    
    public function __construct(string $id, string $userName, string $email, string $firstName, string $lastName)
    {
        $this->id = $id;
        $this->userName = $userName;
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
    
    public function getFirstName(): string
    {
        return $this->firstName;
    }
    
    public function getLastName(): string
    {
        return $this->lastName;
    }
}