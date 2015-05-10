<?php

namespace AssoMaker\AnimBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateMobileJsonCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('am:anim:genmobjson')
            ->setDescription('Generate a JSON file for mobile purpose');
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('test');
        $em = $this->getContainer()->get('doctrine')->getManager();
        $config = $this->getContainer()->get('config.extension');

        // Animations
        $animations = $em->createQuery("SELECT a FROM AssoMakerAnimBundle:Animation a WHERE a.statut =2 and a.mobile = TRUE")->getResult();
        $animationsArray = array();

        foreach ($animations as $animation) {
            $animArray = array();
            $animArray['_id'] = $animation->getId();
            $animArray['name'] = $animation->getNom();
            $animArray['description'] = $animation->getDescriptionMobile();
            $animArray['localisation'] = array($animation->getLocX(), $animation->getLocY());
            $animArray['main_picture_url'] = $animation->getPhotosMobile()[0] ? urlencode('http://' . $config->getValue('mobile_baseurl') . '/up/animPicturesMobile/' . $animation->getPhotosMobile()[0]->getNom()) : null;
            $animArray['pictures'] = array();
            foreach ($animation->getPhotosMobile() as $key => $photo) {
                if ($key > 0)
                    $animArray['pictures'][] = urlencode('http://' . $config->getValue('mobile_baseurl') . '/up/animPicturesMobile/' . $photo->getNom());
            }
            
            $animArray['schedule'] = $animation->getHoraires();
            $animArray['category'] = $animation->getCategorieMobile() ? $animation->getCategorieMobile()->getId() : null;
            $animationsArray[] = $animArray;
        }
        
        // Catégories mobile
        $categories = $em->createQuery("SELECT c FROM AssoMakerAnimBundle:CategorieMobile c")->getResult();
        $categoriesArray = array();

        foreach ($categories as $categorie) {
            $catArray = array();
            $catArray['_id'] = $categorie->getId();
            $catArray['name'] = $categorie->getIdentifiant();
            $catArray['display_name'] = $categorie->getNom();
            $catArray['icon_name'] = $categorie->getIcone();
            $categoriesArray[] = $catArray;
        }
        
        // Artistes
        $artistes = $em->createQuery("SELECT a FROM AssoMakerAnimBundle:Artiste a WHERE a.statut = 2")->getResult();
        $artistesArray = array();

        foreach ($artistes as $artiste) {
            $artArray = array();
            $artArray['_id'] = $artiste->getId();
            $artArray['name'] = $artiste->getNom();
            $artArray['description'] = $artiste->getDescription();
            $artArray['position'] = $artiste->getPosition();
            $artArray['main_picture_url'] = $artiste->getPhotos()[0] ? urlencode('http://' . $config->getValue('mobile_baseurl') . '/up/artistsPicturesMobile/' . $artiste->getPhotos()[0]->getNom()) : null;
            $artArray['pictures'] = array();
            foreach ($artiste->getPhotos() as $photo) {
                $artArray['pictures'][] = urlencode('http://' . $config->getValue('mobile_baseurl') . '/up/artistsPicturesMobile/' . $photo->getNom());
            }
            $artArray['schedule'] = $config->getValue('mobile_publish_concert_schedule') == 1 ? $artiste->getHoraires() : $artiste->getDays();
            $artArray['stage'] = $artiste->getStage();
            $artArray['site_url'] = $artiste->getWebsiteUrl();
            $artArray['facebook_url'] = $artiste->getFacebookUrl();
            $artArray['twitter_url'] = $artiste->getTwitterUrl();
            $artistesArray[] = $artArray;
        }


        // Compilation
        $responseArray = array('resources' => $animationsArray, 'categories' => $categoriesArray, 'artists' => $artistesArray);
        

        // Check version
        $files = scandir($this->getContainer()->get('kernel')->getRootDir() . '/../web/up/jsonMobile', SCANDIR_SORT_DESCENDING);
        $newestFile = $files[0];
        $lastJson = file_get_contents($this->getContainer()->get('kernel')->getRootDir() . '/../web/up/jsonMobile/' . $newestFile);
        $lastData = json_decode($lastJson, true);
        unset($lastData['version']);
        if (!$lastData || $responseArray !== $lastData)
        {
            // New data !
            $date = date('YmdHis');
            $responseArray['version'] = $date;
            $jsonFile = fopen($this->getContainer()->get('kernel')->getRootDir() . '/../web/up/jsonMobile/mobile-' . $date . '.json', 'w');
            fwrite($jsonFile, json_encode($responseArray));
            fclose($jsonFile);
            $output->writeln('JSON file successfully created.');
        }
        else
        {
            $output->writeln('No new data.');
        }

        // Version de l'appli mobile
        $version = array('android' => $config->getValue('mobile_version'));
        $versionFile = fopen($this->getContainer()->get('kernel')->getRootDir() . '/../web/up/jsonMobileVersion/version.json', 'w');
        fwrite($versionFile, json_encode($version));
        fclose($versionFile);
        $output->writeln('Version file successfully updated.');
    }
}