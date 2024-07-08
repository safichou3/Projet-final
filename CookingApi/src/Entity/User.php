<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user_no_pass'])]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank(message: "Votre E-mail est obligatoire")]
    #[Assert\Email(
        message: 'The email {{ value }} is not a valid email.',
    )]
    #[Groups(['user_no_pass'])]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups(['user_no_pass'])]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    #[Groups(['user_no_pass'])]
    private ?bool $isChef = null;

    #[ORM\OneToMany(targetEntity: Menu::class, mappedBy: 'chef', orphanRemoval: true)]
    private Collection $menus;

    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $orders;

    public function __construct()
    {
        $this->menus = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->roles = ['ROLE_USER']; // Ajout automatique du rôle ROLE_USER
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        return array_unique($this->roles);
    }

    public function setRoles(array $roles): static
    {
        if (!in_array('ROLE_USER', $roles, true)) {
            $roles[] = 'ROLE_USER';
        }
        $this->roles = array_unique($roles);

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials(): void
    {
        // Clear any temporary, sensitive data here
    }

    public function isChef(): ?bool
    {
        return $this->isChef;
    }

    public function setIsChef(bool $isChef): static
    {
        $this->isChef = $isChef;

        return $this;
    }

    public function getMenus(): Collection
    {
        return $this->menus;
    }

    public function addMenu(Menu $menu): static
    {
        if (!$this->menus->contains($menu)) {
            $this->menus->add($menu);
            $menu->setChef($this);
        }

        return $this;
    }

    public function removeMenu(Menu $menu): static
    {
        if ($this->menus->removeElement($menu)) {
            if ($menu->getChef() === $this) {
                $menu->setChef(null);
            }
        }

        return $this;
    }

    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): static
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setUser($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            if ($order->getUser() === $this) {
                $order->setUser(null);
            }
        }

        return $this;
    }
}

