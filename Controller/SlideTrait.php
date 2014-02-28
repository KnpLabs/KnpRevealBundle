<?php

namespace Knp\RevealBundle\Controller;

use Symfony\Component\Finder\Finder;

trait SlideTrait
{
    abstract function getSlidesDirectory();

    public function slidesAction()
    {
        $slidesDirectory = $this->getSlidesDirectory();
        if (!is_dir($slidesDirectory)) {
            $slidesDirectory = $this->container->get('kernel')
                ->locateResource($slidesDirectory)
            ;
        }

        $slides = [];
        $finder = new Finder();
        $finder->sortByName();
        foreach ($finder->files()->in($slidesDirectory) as $file) {
            $templatePath = $file->getRealPath();
            $slides[] = $this->getTemplateName($templatePath);
        }

        return $this->render('KnpRevealBundle:Slide:slides.html.twig', [
            'slides' => $slides,
            'layoutTemplate' => $this->container->getParameter('knp_reveal.layout_template')
        ]);
    }

    public function getTemplateName($templatePath)
    {
        $srcPath = strtr($this->get('kernel')->getRootDir(), array('app' => 'src'));
        $templatePath = strtr($templatePath, array($srcPath => ''));

        if (preg_match('/^\/([^\/]+)\/?([^\/]+)\/Resources\/views\/(.+)\/([^\/]+)$/', $templatePath, $matches)) {
            return sprintf('%s%s:%s:%s', $matches[1], $matches[2], $matches[3], $matches[4]);
        }
    }
}