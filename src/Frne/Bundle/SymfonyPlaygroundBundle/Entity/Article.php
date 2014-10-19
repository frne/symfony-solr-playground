<?php

namespace Frne\Bundle\SymfonyPlaygroundBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FS\SolrBundle\Doctrine\Annotation as Solr;

/**
 * @ORM\Entity
 * @ORM\Table(name="article")
 *
 * @Solr\Document(repository="Frne\Bundle\SymfonyPlaygroundBundle\Entity\Repository\ArticleRepository")
 */
class Article
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
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     *
     * @Solr\Field(type="string")
     */
    protected $author;

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
     * @return Article
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
     * @return Article
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
     * Set author
     *
     * @param string $author
     * @return Article
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string 
     */
    public function getAuthor()
    {
        return $this->author;
    }
}
