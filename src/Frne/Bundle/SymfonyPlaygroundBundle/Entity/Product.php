<?php

namespace Frne\Bundle\SymfonyPlaygroundBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FS\SolrBundle\Doctrine\Annotation as Solr;

/**
 * @ORM\Entity
 * @ORM\Table(name="product")
 *
 * @Solr\Document(repository="Frne\Bundle\SymfonyPlaygroundBundle\Entity\Repository\Solr\ProductRepository")
 */
class Product
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Solr\Id
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=200)
     *
     * @Solr\Field(type="string")
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=1000)
     *
     * @Solr\Field(type="text")
     */
    protected $content;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="products")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    protected $category;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Product
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Product
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set category
     *
     * @param Category $category
     * @return Product
     */
    public function setCategory(Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }
}
