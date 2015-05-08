<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
            new JMS\AopBundle\JMSAopBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new Sonata\IntlBundle\SonataIntlBundle(),
            new AssoMaker\PHPMBundle\AssoMakerPHPMBundle(),
            new AssoMaker\BaseBundle\AssoMakerBaseBundle(),
        	new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new AssoMaker\ComptesPersoBundle\AssoMakerComptesPersoBundle(),
            new AssoMaker\SponsoBundle\AssoMakerSponsoBundle(),
            new AssoMaker\AnimBundle\AssoMakerAnimBundle(),
            new AssoMaker\PassSecuBundle\AssoMakerPassSecuBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new HWI\Bundle\OAuthBundle\HWIOAuthBundle(),
            new Spraed\PDFGeneratorBundle\SpraedPDFGeneratorBundle(),
            new Endroid\Bundle\GcmBundle\EndroidGcmBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
        	
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
