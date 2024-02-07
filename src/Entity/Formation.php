<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'formations')]
    private Collection $apprenants;

    #[ORM\OneToMany(targetEntity: Matiere::class, mappedBy: 'formation')]
    private Collection $matieres;

    public function __construct()
    {
        $this->apprenants = new ArrayCollection();
        $this->matieres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getApprenants(): Collection
    {
        return $this->apprenants;
    }

    public function addApprenant(User $apprenant): static
    {
        if (!$this->apprenants->contains($apprenant)) {
            $this->apprenants->add($apprenant);
        }

        return $this;
    }

    public function removeApprenant(User $apprenant): static
    {
        $this->apprenants->removeElement($apprenant);

        return $this;
    }

    /**
     * @return Collection<int, Matiere>
     */
    public function getMatieres(): Collection
    {
        return $this->matieres;
    }

    public function addMatiere(Matiere $matiere): static
    {
        if (!$this->matieres->contains($matiere)) {
            $this->matieres->add($matiere);
            $matiere->setFormation($this);
        }

        return $this;
    }

    public function removeMatiere(Matiere $matiere): static
    {
        if ($this->matieres->removeElement($matiere)) {
            // set the owning side to null (unless already changed)
            if ($matiere->getFormation() === $this) {
                $matiere->setFormation(null);
            }
        }

        return $this;
    }
}
