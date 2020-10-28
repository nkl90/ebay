<?php
namespace App\Twig\Loader;

use App\Entity\Template;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Error\LoaderError;
use Twig_LoaderInterface;
use Twig\Loader\LoaderInterface;


class DatabaseLoader implements LoaderInterface
{
    protected $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->repo = $em->getRepository(Template::class);
    }

    public function getSourceContext($name)
    {
        if (false === $template = $this->getTemplate($name)) {
            throw new LoaderError(sprintf('Template "%s" does not exist.', $name));
        }

        return new Twig_Source($template->getSource(), $name);
    }

    public function exists($name)
    {
        return (bool)$this->getTemplate($name);
    }

    public function getCacheKey($name)
    {
        return $name;
    }

    public function isFresh($name, $time)
    {
        if (false === $template = $this->getTemplate($name)) {
            return false;
        }

        return $template->getLastUpdated()->getTimestamp() <= $time;
    }

    /**
     * @param $name
     * @return Template|null
     */
    protected function getTemplate($name)
    {
        return $this->repo->findOneBy(['filename' => $name]);  
    }
}