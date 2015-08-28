<?php

namespace IntentRestAPI\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Recu
 * @ORM\Entity
 * @ORM\Table(name="Recu")
 */
class Recu extends Piece
{

    /**
     * @var string
     *
     * @ORM\Column(name="fieldFake", type="string", length=255)
     */
    private $fieldFake;

    /**
     * Set fieldFake
     *
     * @param string $fieldFake
     * @return Recu
     */
    public function setFieldFake($fieldFake)
    {
        $this->fieldFake = $fieldFake;

        return $this;
    }

    /**
     * Get fieldFake
     *
     * @return string 
     */
    public function getFieldFake()
    {
        return $this->fieldFake;
    }
}
