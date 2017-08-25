<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Product.
 *
 * @ORM\Table(name="tblProductData", uniqueConstraints={@ORM\UniqueConstraint(name="strProductCode", columns={"strProductCode"})})
 * @ORM\Entity
 * @UniqueEntity("strProductCode")
 * @Assert\Expression(
 *     "this.getCost() >= 5 or this.getStock() >= 10",
 *     message="Cost should be more or equals 5 and Stock should be more or equals 10", groups={"costAndStockConstraint"}
 * )
 */
class Product
{
    /**
     * @var string
     *
     * @ORM\Column(name="strProductName", type="string", length=255)
     */
    private $name;
    /**
     * @var string
     *
     * @ORM\Column(name="strProductCode", type="string", length=255)
     */
    private $code;
    /**
     * @var int
     *
     * @ORM\Column(name="intStock", type="integer", options={"unsigned"=true})
     *
     * @Assert\GreaterThan(10)
     */
    private $stock;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dtmAdded", type="datetime")
     */
    private $added;
    /**
     * @var float
     *
     * @ORM\Column(name="dcCost", type="decimal", precision=10, scale=2, options={"unsigned"=true})
     *
     * @Assert\GreaterThan(5)
     *
     * @Assert\LessThan(1000)
     */
    private $cost;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dtmDiscontinued", type="datetime")
     */
    private $discontinued;
    /**
     * @var string
     *
     * @ORM\Column(name="strProductDesc", type="string", length=512)
     */
    private $description;
    /**
     * @var int
     *
     * @ORM\Column(name="intProductDataId", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    public function __construct()
    {
        $this->added = new \DateTime();
    }
    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName($name) : Product
    {
        $this->name = $name;

        return $this;
    }
    /**
     * Get name.
     *
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }
    /**
     * Set code.
     *
     * @param string $code
     *
     * @return Product
     */
    public function setCode($code) : Product
    {
        $this->code = $code;

        return $this;
    }
    /**
     * Get code.
     *
     * @return string
     */
    public function getCode() : string
    {
        return $this->code;
    }
    /**
     * Set stock.
     *
     * @param int $stock
     *
     * @return Product
     */
    public function setStock($stock) : Product
    {
        $this->stock = $stock;

        return $this;
    }
    /**
     * Get stock.
     *
     * @return int
     */
    public function getStock() : int
    {
        return $this->stock;
    }
    /**
     * Set added.
     *
     * @param \DateTime $added
     *
     * @return Product
     */
    public function setAdded($added) : Product
    {
        $this->added = $added;

        return $this;
    }
    /**
     * Get added.
     *
     * @return \DateTime
     */
    public function getAdded() : \DateTime
    {
        return $this->added;
    }
    /**
     * Set cost.
     *
     * @param float $cost
     *
     * @return Product
     */
    public function setCost($cost) : Product
    {
        $this->cost = $cost;

        return $this;
    }
    /**
     * Get cost.
     *
     * @return float
     */
    public function getCost() : float
    {
        return $this->cost;
    }
    /**
     * Set discontinued.
     *
     * @param \DateTime $discontinued
     *
     * @return Product
     */
    public function setDiscontinued($discontinued) : Product
    {
        $this->discontinued = $discontinued;

        return $this;
    }
    /**
     * Get discontinued.
     *
     * @return \DateTime
     */
    public function getDiscontinued() : \DateTime
    {
        return $this->discontinued;
    }
    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description) : Product
    {
        $this->description = $description;

        return $this;
    }
    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription() : string
    {
        return $this->description;
    }
    /**
     * Get id.
     *
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }
}
