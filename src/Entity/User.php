<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *     fields={"Email"},
 *     message="Un autre utilisateur s'est deja inscrit avec cette adress mail"
 * )
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
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="nom non valide")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="prenom non valide")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(message="email non valid !")
     */
    private $Email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hash;
    /**
     * @Assert\EqualTo(propertyPath="hash", message="vous n'avez pas correctement confirmer votre mot de pass")
     */
    public $passwordConfirm;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="10", minMessage="votre intro doit faire au moins 10 caractères")
     */
    private $intro;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="100", minMessage="votre intro doit faire au moins avoire 100 caractères")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="author")
     */
    private $products;


    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function initializeSlug(){
        if(empty($this->slug))
        {
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->firstName ." ".$this->lastName);
        }
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): self
    {
        $this->Email = $Email;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getIntro(): ?string
    {
        return $this->intro;
    }

    public function setIntro(string $intro): self
    {
        $this->intro = $intro;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getFullName(){
        return "{$this->getFirstName()} {$this->getLastName()}";
    }
    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setAuthor($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getAuthor() === $this) {
                $product->setAuthor(null);
            }
        }

        return $this;
    }
    public function getRoles()
    {
        return ['ROLE_USER'];
    }
    public function getPassword()
    {
        // TODO: Implement getPassword() method.
        return $this->hash;
    }
    public function getUsername()
    {
        // TODO: Implement getUsername() method.
        return $this->Email;
    }
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}
