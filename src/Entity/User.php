<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
# use qui permet d'ajouter des validateurs dans le remplissage de nos champs
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(
    fields:"email",
    message: "L'e-mail que vous avez saisi est déjà utilisé !"
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $role;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Email()]
    private $email;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Length(
        min:8,
        minMessage: "Votre mot de passe n'est pas assez long ! Il doit être supérieur à 8 caractères !",
    )]
    private $password;

    #[ORM\Column(type: 'boolean')]
    private $is_verified;

    #[Assert\EqualTo(propertyPath: "password", message:"Vous n'avez pas écrit le même mot de passe")]
    public $confirm_password;

    #[ORM\Column(type: 'string', length: 255)]
    private $firstname;

    #[ORM\Column(type: 'string', length: 255)]
    private $lastname;

    #[ORM\Column(type: 'string', length: 255)]
    private $token;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getIsVerified(): ?bool
    {
        return $this->is_verified;
    }

    public function setIsVerified(bool $is_verified): self
    {
        $this->is_verified = $is_verified;

        return $this;
    }

    public function eraseCredentials() {}

    /**
     * @return string
     */
    public function getRoles(): array
    {
        return [$this->role];
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        // le $this->email permet d'indiquer l'email de la personne connecté
        return (string) $this->email;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }
}
