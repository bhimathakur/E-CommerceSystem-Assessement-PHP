<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Enum\Status;
use App\Enum\UserType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Length;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: 'Please enter email')]
    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];


    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[Assert\NotBlank(message: 'Please enter first name')]
    #[ORM\Column(length: 255)]
    
    private ?string $first_name = null;

    #[Assert\NotBlank(message: 'Please enter last name')]
    #[ORM\Column(length: 255)]
    private ?string $last_name = null;

    #[Assert\NotBlank(message: 'Please enter zipcode')]

    #[Assert\Length(
        min: 4,
        max: 6,
        minMessage: 'The value must be at least {{ limit }} characters long.',
        maxMessage: 'The value cannot be longer than {{ limit }} characters.',
    )]
    #[ORM\Column]
    private ?int $zipcode = null;

    #[ORM\Column(length: 255, enumType:Status::class)]
    private  Status $status = Status::ACTIVE;

    #[ORM\Column]
    private ?\DateTime $created_at = null;

    #[Assert\NotBlank(message: 'Please enter mobile number')]
    #[Assert\Length(
        min: 10,
        max: 10,
        minMessage: 'The value must be at least {{ limit }} characters long.',
        maxMessage: 'The value cannot be longer than {{ limit }} characters.',
    )]
    #[ORM\Column(type:'integer', length:11)]
    private ?Int $mobile;

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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_ADMIN';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): static
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): static
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getZipcode(): ?int
    {
        return $this->zipcode;
    }

    public function setZipcode(int $zipcode): static
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTime $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }


    public function getMobile(): ?Int
    {
        return $this->mobile;
    }

    public function setMobile(Int $mobile): static
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * @return Collection<int, FlightBooking>
     */
    public function getFlightBookings(): Collection
    {
        return $this->flightBookings;
    }

    public function addFlightBooking(FlightBooking $flightBooking): static
    {
        if (!$this->flightBookings->contains($flightBooking)) {
            $this->flightBookings->add($flightBooking);
            $flightBooking->setUserId($this);
        }

        return $this;
    }

    public function removeFlightBooking(FlightBooking $flightBooking): static
    {
        if ($this->flightBookings->removeElement($flightBooking)) {
            // set the owning side to null (unless already changed)
            if ($flightBooking->getUserId() === $this) {
                $flightBooking->setUserId(null);
            }
        }

        return $this;
    }

    
}
