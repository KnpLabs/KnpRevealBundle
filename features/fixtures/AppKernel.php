<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        return array(
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle,
            new \Symfony\Bundle\TwigBundle\TwigBundle,
            new \Knp\RevealBundle\KnpRevealBundle,
            new \App\App
        );
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(function($container) {
            $container->loadFromExtension('framework', array(
                'test' => null,
                'session' => array(
                    'storage_id' => 'session.storage.mock_file',
                ),
                'secret' => '%kernel.secret%',
                'csrf_protection' => null,
                'form' => null,
                'router' => array('resource' => __DIR__.'/App/Resources/config/routing.yml'),
                'validation' => array('enable_annotations' => true),
                'templating' => array(
                    'engines' => array('twig'),
                ),
            ));
        });
    }

    protected function getKernelParameters()
    {
        $parameters = parent::getKernelParameters();
        $parameters['kernel.secret'] = 'secret!';

        return $parameters;
    }

    public function getCacheDir()
    {
        return $this->rootDir.'/tmp/cache/'.$this->name.$this->environment;
    }

    public function getLogDir()
    {
        return $this->rootDir.'/tmp/logs';
    }
}
