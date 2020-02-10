<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\TransactionRepository")
 */
class Transaction
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    /**
     * @ORM\Column(type="integer")
     */
    private $montant;

    /**
     * @ORM\Column(type="integer")
     */
    private $frais;

    /**
     * @ORM\Column(type="integer")
     */
    private $commissionSys;

    /**
     * @ORM\Column(type="integer")
     */
    private $commussionEtat;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $clientEmetteur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typePiece;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numPiece;

    /**
     * @ORM\Column(type="integer")
     */
    private $commussiontrans;

    /**
     * @ORM\Column(type="integer")
     */
    private $commissionrecept;

    /**
     * @ORM\Column(type="integer")
     */
    private $telephoneEmetteur;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     */
    private $telephoneRecepteur;

    /**
     * @ORM\Column(type="datetime")
     */
    private $daterecep;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="transaction")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getFrais(): ?int
    {
        return $this->frais;
    }

    public function setFrais(int $frais): self
    {
        $this->frais = $frais;

        return $this;
    }

    public function getCommissionSys(): ?int
    {
        return $this->commissionSys;
    }

    public function setCommissionSys(int $commissionSys): self
    {
        $this->commissionSys = $commissionSys;

        return $this;
    }

    public function getCommussionEtat(): ?int
    {
        return $this->commussionEtat;
    }

    public function setCommussionEtat(int $commussionEtat): self
    {
        $this->commussionEtat = $commussionEtat;

        return $this;
    }

    public function getClientEmetteur(): ?string
    {
        return $this->clientEmetteur;
    }

    public function setClientEmetteur(string $clientEmetteur): self
    {
        $this->clientEmetteur = $clientEmetteur;

        return $this;
    }

    public function getTypePiece(): ?string
    {
        return $this->typePiece;
    }

    public function setTypePiece(string $typePiece): self
    {
        $this->typePiece = $typePiece;

        return $this;
    }

    public function getNumPiece(): ?string
    {
        return $this->numPiece;
    }

    public function setNumPiece(string $numPiece): self
    {
        $this->numPiece = $numPiece;

        return $this;
    }

    public function getCommussiontrans(): ?int
    {
        return $this->commussiontrans;
    }

    public function setCommussiontrans(int $commussiontrans): self
    {
        $this->commussiontrans = $commussiontrans;

        return $this;
    }

    public function getCommissionrecept(): ?int
    {
        return $this->commissionrecept;
    }

    public function setCommissionrecept(int $commissionrecept): self
    {
        $this->commissionrecept = $commissionrecept;

        return $this;
    }

    public function getTelephoneEmetteur(): ?int
    {
        return $this->telephoneEmetteur;
    }

    public function setTelephoneEmetteur(int $telephoneEmetteur): self
    {
        $this->telephoneEmetteur = $telephoneEmetteur;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getTelephoneRecepteur(): ?int
    {
        return $this->telephoneRecepteur;
    }

    public function setTelephoneRecepteur(int $telephoneRecepteur): self
    {
        $this->telephoneRecepteur = $telephoneRecepteur;

        return $this;
    }

    public function getDaterecep(): ?\DateTimeInterface
    {
        return $this->daterecep;
    }

    public function setDaterecep(\DateTimeInterface $daterecep): self
    {
        $this->daterecep = $daterecep;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
