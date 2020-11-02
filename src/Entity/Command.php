<?php

namespace App\Entity;

use App\Entity\Product;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommandRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CommandRepository::class)
 */
class Command
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("commands")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("commands")
     */
    private $adresse_livrais;

    /**
     * @ORM\Column(type="integer")
     * @Groups("commands")
     */
    private $zipcode;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("commands")
     */
    private $ville;

    /**
     * @ORM\Column(type="float")
     * @Groups("commands")
     */
    private $prix_total;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("commands")
     */
    private $command_at;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("commands")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="commands")
     * @Groups("commands")
     */
    private $user_id;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="command")
     * @Groups("commands")
     */
    private $product;

    public function __construct()
    {
        $this->product = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdresseLivrais(): ?string
    {
        return $this->adresse_livrais;
    }

    public function setAdresseLivrais(string $adresse_livrais): self
    {
        $this->adresse_livrais = $adresse_livrais;

        return $this;
    }

    public function getZipcode(): ?int
    {
        return $this->zipcode;
    }

    public function setZipcode(int $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getPrixTotal(): ?float
    {
        return $this->prix_total;
    }

    public function setPrixTotal(float $prix_total): self
    {
        $this->prix_total = $prix_total;

        return $this;
    }

    public function getCommandAt(): ?\DateTimeInterface
    {
        return $this->command_at;
    }

    public function setCommandAt(\DateTimeInterface $command_at): self
    {
        $this->command_at = $command_at;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProduct(): Collection
    {
        return $this->product;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->product->contains($product)) {
            $this->product[] = $product;
            $product->setCommand($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->product->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getCommand() === $this) {
                $product->setCommand(null);
            }
        }

        return $this;
    }
}
