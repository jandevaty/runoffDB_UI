<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MeasurementRepository")
 */
class Measurement extends BaseEntity {
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $descriptionCZ;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $descriptionEN;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $noteCZ;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $noteEN;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Run", inversedBy="measurements")
     */
    private $runs;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Record", mappedBy="measurement", cascade={"persist","remove"}, orphanRemoval=true)
     */
    private $records;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\SoilSample", inversedBy="measurements")
     */
    private $soilSamples;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Phenomenon", inversedBy="measurements")
     * @ORM\JoinColumn(nullable=true, referencedColumnName="id", onDelete="SET NULL")
     */
    private $phenomenon;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Plot", inversedBy="measurements")
     */
    private $plot;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Locality", inversedBy="measurements")
     * @ORM\JoinColumn(nullable=true, referencedColumnName="id", onDelete="SET NULL")
     */
    private $locality;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="measurements")
     */
    private $user;

    public function __toString(): string {
        return $this->getDescription();
    }

    public function getNote(): ?string {
        return $this->getLocale() == 'en' ? $this->getNoteEN() : $this->getNoteCZ();
    }

    public function getDescription(): ?string {
        return $this->getLocale() == 'en' ? $this->getDescriptionEN() : $this->getDescriptionCZ();
    }

    public function __construct() {
        $this->records = new ArrayCollection();
        $this->runs = new ArrayCollection();
        $this->soilSamples = new ArrayCollection();
    }

    public function getId(): ?int {
        return $this->id;
    }


    public function getDescriptionCZ(): ?string {
        return $this->descriptionCZ;
    }

    public function setDescriptionCZ(?string $descriptionCZ): self {
        $this->descriptionCZ = $descriptionCZ;

        return $this;
    }

    public function getDescriptionEN(): ?string {
        return $this->descriptionEN;
    }

    public function setDescriptionEN(?string $descriptionEN): self {
        $this->descriptionEN = $descriptionEN;

        return $this;
    }

    public function getNoteCZ(): ?string {
        return $this->noteCZ;
    }

    public function setNoteCZ(?string $noteCZ): self {
        $this->noteCZ = $noteCZ;

        return $this;
    }

    public function getNoteEN(): ?string {
        return $this->noteEN;
    }

    public function setNoteEN(?string $noteEN): self {
        $this->noteEN = $noteEN;

        return $this;
    }

    /**
     * @return Collection|Record[]
     */
    public function getRecords(): Collection {
        return $this->records;
    }

    public function addRecord(Record $record): self {
        if (!$this->records->contains($record)) {
            $this->records[] = $record;
            $record->setMeasurement($this);
        }

        return $this;
    }

    public function removeRecord(Record $record): self {
        if ($this->records->contains($record)) {
            $this->records->removeElement($record);
            // set the owning side to null (unless already changed)
            if ($record->getMeasurement() === $this) {
                $record->setMeasurement(null);
            }
        }

        return $this;
    }

    public function belongsToRun(int $run_id): bool {
        foreach ($this->getRuns() as $run) {
            if ($run_id == $run->getId()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return Collection|Run[]
     */
    public function getRuns(): Collection {
        return $this->runs;
    }

    public function addRun(Run $run): self {
        if (!$this->runs->contains($run)) {
            $this->runs[] = $run;
            $this->setDate($run->getDatetime());
            $this->setLocality($run->getSequence()->getLocality());
            $this->setPlot($run->getPlot());
        }
        return $this;
    }

    public function removeRun(Run $run): self {
        if ($this->runs->contains($run)) {
            $this->runs->removeElement($run);
        }

        return $this;
    }

    /**
     * @return Collection|SoilSample[]
     */
    public function getSoilSamples(): Collection {
        return $this->soilSamples;
    }

    public function addSoilSample(SoilSample $soilSample): self {
        if (!$this->soilSamples->contains($soilSample)) {
            $this->soilSamples[] = $soilSample;
            $this->setDate($soilSample->getDateSampled());
            $this->setLocality($soilSample->getLocality());
            $this->setPlot($soilSample->getPlot());
        }

        return $this;
    }

    public function removeSoilSample(SoilSample $soilSample): self {
        if ($this->soilSamples->contains($soilSample)) {
            $this->soilSamples->removeElement($soilSample);
        }

        return $this;
    }

    public function getPhenomenon(): ?Phenomenon {
        return $this->phenomenon;
    }

    public function setPhenomenon(?Phenomenon $phenomenon): self {
        $this->phenomenon = $phenomenon;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface {
        return $this->date;
    }

    public function getFormatedDate(string $format = "d.m.Y"): string {
        return $this->date ? $this->getDate()->format($format) : '';
    }

    public function setDate(?\DateTimeInterface $date): self {
        $this->date = $date;

        return $this;
    }

    public function getPlot(): ?Plot {
        return $this->plot;
    }

    public function setPlot(?Plot $plot): self {
        $this->plot = $plot;

        return $this;
    }

    public function getLocality(): ?Locality {
        return $this->locality;
    }

    public function setLocality(?Locality $locality): self {
        $this->locality = $locality;

        return $this;
    }

    public function getOrganization(): ?Organization {
        if ($this->getUser()) {
            return $this->getUser()->getOrganization();
        }
        return null;
    }

    public function getUser(): ?User {
        return $this->user;
    }

    public function setUser(?User $user): self {
        $this->user = $user;

        return $this;
    }
}
