<?php

namespace App\Entity;

use App\Enum\Status;
use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'products')]
        #[ORM\JoinColumn(nullable: false)] // Ensures a product always has a category
        private ?Category $category = null;

    #[Assert\NotBlank(message: "Please enter title")]
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[Assert\NotBlank(message: "Please enter price")]
    #[ORM\Column(length: 255)]
    private ?string $price = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Please select sub category")]
    private $subcat_id = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "Please enter description")]
    private ?string $description = null;

    #[ORM\Column(type: 'string', enumType:Status::class)]
    private $status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private  $created_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    
    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(?string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;
        return $this;
    }
    public function getSubCatId()
    {
        return $this->subcat_id;
    }

    public function setSubCatId( $subcat_id): static
    {
        $this->subcat_id = $subcat_id;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

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

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setCreatedAt($created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

 
}
