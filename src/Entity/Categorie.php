<?php
namespace App\Entity;


use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    /**
     * @var Collection<int, Chiken>
     */
    #[ORM\OneToMany(targetEntity: Chiken::class, mappedBy: 'idcategorie')]
    private Collection $chikens;

    /**
     * @var Collection<int, Sandwitch>
     */
    #[ORM\OneToMany(targetEntity: Sandwitch::class, mappedBy: 'idcategorie')]
    private Collection $sandwitches;

    public function __construct()
    {
        $this->chikens = new ArrayCollection();
        $this->sandwitches = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection<int, Chiken>
     */
    public function getChikens(): Collection
    {
        return $this->chikens;
    }

    public function addChiken(Chiken $chiken): static
    {
        if (!$this->chikens->contains($chiken)) {
            $this->chikens->add($chiken);
            $chiken->setIdcategorie($this);
        }

        return $this;
    }

    public function removeChiken(Chiken $chiken): static
    {
        if ($this->chikens->removeElement($chiken)) {
            // set the owning side to null (unless already changed)
            if ($chiken->getIdcategorie() === $this) {
                $chiken->setIdcategorie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Sandwitch>
     */
    public function getSandwitches(): Collection
    {
        return $this->sandwitches;
    }

    public function addSandwitch(Sandwitch $sandwitch): static
    {
        if (!$this->sandwitches->contains($sandwitch)) {
            $this->sandwitches->add($sandwitch);
            $sandwitch->setIdcategorie($this);
        }

        return $this;
    }

    public function removeSandwitch(Sandwitch $sandwitch): static
    {
        if ($this->sandwitches->removeElement($sandwitch)) {
            // set the owning side to null (unless already changed)
            if ($sandwitch->getIdcategorie() === $this) {
                $sandwitch->setIdcategorie(null);
            }
        }

        return $this;
    }
}
