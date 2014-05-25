<?php
namespace Knp\RevealBundle\Controller;

use Symfony\Component\Finder\Finder;

trait SlideTrait
{
    abstract function getSlidesDirectory();

    public function slidesAction()
    {
        $slidesDirectory = $this->getSlidesDirectory();
        if (! is_dir($slidesDirectory)) {
            $slidesDirectory = $this->container->get('kernel')->locateResource($slidesDirectory);
        }

        $slides = [];
        $finder = new Finder();
        $finder->sortByName();
        foreach ($finder->files()->in($slidesDirectory) as $file) {
            $templatePath = $file->getRealPath();
            $slides[] = $this->getTemplateNameToInclude(basename($templatePath));
        }

        return $this->render('KnpRevealBundle:Slide:slides.html.twig', [
            'slides' => $slides,
            'layoutTemplate' => $this->container->getParameter('knp_reveal.layout_template')
        ]);
    }

    /**
     *  Get template name to include in layout view
     *
     * @param string $templateName
     */
    public function getTemplateNameToInclude($templateName)
    {
        $slidesDirectory = $this->getSlidesDirectory();
        if (preg_match('/^@([^\/]+)\/Resources\/views\/(.+)$/', $slidesDirectory, $matches)) {
            return sprintf('%s:%s:%s', $matches[1], $matches[2], $templateName);
        }
    }
}