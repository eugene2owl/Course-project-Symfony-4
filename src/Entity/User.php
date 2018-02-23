<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Result;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 *
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @return mixed
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;


    /**
     * @Assert\NotBlank()
     *  @ORM\Column(type="string", length=64)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $secondname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $thirdname;


    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     *
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail($email): void
    {
        $this->email = $email;
    }

    public function getcity(): string
    {
        return $this->city;
    }

    public function setcity($city): void
    {
        $this->city = $city;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername($username): void
    {
        $this->username = $username;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function setFirstname($username): void
    {
        $this->firstname = $username;
    }

    public function getSecondname(): string
    {
        return $this->secondname;
    }

    public function setSecondname($username): void
    {
        $this->secondname = $username;
    }

    public function getThirdname(): string
    {
        return $this->thirdname;
    }

    public function setThirdname($username): void
    {
        $this->thirdname = $username;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password): void
    {
        $this->plainPassword = $password;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword($password): void
    {
        $this->password = $password;
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
    }

    /**
     * @var ArrayCollection
     * @ORM\Column(type="array")
     */
    private $roles;

    public function __construct()
    {
        $this->roles = ["ROLE_USER"];
        $this->resultList = new ArrayCollection();
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @ORM\OneToMany(targetEntity="Result", mappedBy="user")
     * @ORM\JoinColumn(name="list_of_results", referencedColumnName="id")
     *
     */
    private $resultList;

    /**
     * @return Collection|Result[]
     */
    public function MYgetResultList(): Collection
    {
        return $this->resultList;
    }

    /**
     * @param mixed $result
     */
    public function setResultList(Result $result): void
    {
        $this->resultList->add($result);
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
         return serialize(array(
             $this->id,
             $this->username,
             $this->password,
             $this->plainPassword,
         ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }

    public function __toString(): string
    {
        return $this->username;
    }
}
