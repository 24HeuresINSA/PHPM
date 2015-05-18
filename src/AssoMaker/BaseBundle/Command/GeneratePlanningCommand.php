<?php

namespace AssoMaker\BaseBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GeneratePlanningCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('am:base:genplanning')
            ->setDescription('Generate plannings');
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('test');
        $em = $this->getContainer()->get('doctrine')->getManager();
        $config = $this->getContainer()->get('config.extension');

        $debut = new \DateTime();
        $fin = new \DateTime($config->getValue('phpm_planning_fin'));
        $orgas = $em->getConnection()->fetchAll("SELECT id, nom, prenom FROM Orga WHERE statut>=1");

        $pdfGenerator = $this->getContainer()->get('spraed.pdf.generator');

        // Generate planning for each orga (could take a long)
        foreach ($orgas as $o) {
            $output->writeln('Generating: [' . $o['id'] . '] ' . $o['prenom'] . ' ' . $o['nom']);

            try{
                list($orga, $tachesResp) = $this->getPlanningDataForOrga($o['id'], $em, $debut, $fin);
                $pdf = $pdfGenerator->generatePDF($this->getContainer()->get('templating')->render('AssoMakerBaseBundle:Orga:printPlanning.html.twig',array('orga'=>$orga,'tachesResp'=>$tachesResp)));
            } catch(\Exception $e){
                $pdf = $pdfGenerator->generatePDF("User ".$o['id']." Erreur : ".$e->getMessage());
            }
            $file = fopen("/tmp/pdfOrga/" . str_replace('/', '_', $o['nom']) . " " . str_replace('/', '_', $o['prenom']) . " (" . $o['id'] . ").pdf", "w+");
            fwrite($file, $pdf);
            fclose($file);
            //$zip->addFromString($o['id'].".pdf",  $pdf);
        }

        $output->writeln('OK.');
    }

    protected function getPlanningDataForOrga($orgaid, $em, $debut, $fin)
    {
        // On prend le planning de l'orga
        $orga = $em->getRepository('AssoMakerBaseBundle:Orga')->getPlanning($orgaid, 'all', $debut, $fin)[0];

        // Si il est resp on cherche les infos de ses taches
        $tachesResp = array();
        return array($orga, $tachesResp);
    }

}