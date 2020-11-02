<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("productsall")
     * @Groups("commands")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("productsall")
     * @Groups("commands")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("productsall")
     * @Groups("commands")
     *
     */
    private $photo;

    /**
     * @ORM\Column(type="text")
     * @Groups("productsall")
     * @Groups("commands")
     *
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     * @Groups("productsall")
     * @Groups("commands")
     *
     */
    private $prix;

    /**
     * @ORM\Column(type="integer")
     * @Groups("productsall") 
     * @Groups("commands")
     *
     */
    private $quantite;

    /**
     * @ORM\ManyToOne(targetEntity=Command::class, inversedBy="product")
     * 
     */
    private $command;
    public function toArray():?Array
    { 
        $product = array();
        array_push($product,["id"=>$this->id,"nom"=>$this->nom,"photo"=>$this->photo,"description"=>$this->description,"prix"=>$this->prix,"quantite"=>$this->quantite]);
        return $product;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

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

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getCommand(): ?Command
    {
        return $this->command;
    }

    public function setCommand(?Command $command): self
    {
        $this->command = $command;

        return $this;
    }
}
