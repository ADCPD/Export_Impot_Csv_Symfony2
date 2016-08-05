<?php
/**
 * Created by PhpStorm.
 * User: dhaouadi_a
 * Date: 04/08/2016
 * Time: 17:19
 */
namespace CMS\EquipeBundle\Command\PDO;

use CMS\EquipeBundle\Entity\Equipes;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class importDataCommand
 * @package CMS\EquipeBundle\Command\PDO
 */
class importDataCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('equipe:import:data')
            ->setDescription('Commande qui permet de charger un contenu externe dans l\'entitée EQUIPE via un fichier CSV.');
    }


    /**
     * {@inheritdoc}
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $now = new  \DateTime();

        /** Afficher le moment du  lancement du processus  */
        $output->writeln('<comment>Start: ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');

        /** Import CSV on DB via Doctrine ORM */
        $this->import($input, $output);

        /** Afficher la date de fin du proccess  */
        $now = new \DateTime();
        $output->writeln('<comment>End : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function import(InputInterface $input, OutputInterface $output)
    {
        /** recuperer le csv en format array  */
        $data = $this->get($input, $output);

        /** Doctrine Manager */
        $em = $this->getContainer()->get('doctrine')->getManager();

        /** turning off doctrine pour obtimser la memoire lors du  chargement  */
        $em->getConnection()->getConfiguration()->setSQLLogger(null);

        /** Definir la taille de l'enregistrement */
        $size = count($data);
        $batchSize = 20;
        $i = 1;

        /** Processus start */
        $progress = new ProgressBar($output, $size);

        $progress->start();

        foreach ($data as $row) {
            $equipe = $this->getContainer()->get('equipe.manager.equipe_manager')->getFullEquipes();
            if (!is_object($equipe)) {
                $equipe = new Equipes();
            }

            /** Mise à  jour des informations */
            $equipe->setNom($row['nom']);
            $equipe->setPrenom($row['prenom']);
            $equipe->setPoste($row['poste']);
            $equipe->setApropos($row['apropos']);


//            $equipe->getAdresses()->setAdresse($row['adresse']);
//            $equipe->getAdresses()->setMail($row['mail']);
//            $equipe->getAdresses()->setTel($row['tel']);
//            $equipe->getAdresses()->setFix($row['fix']);
//
//            $equipe->getSocials()->setType($row['type']);
//            $equipe->getSocials()->setUrl($row['url']);

            /** persister les nouvelles modifications */
            $em->persist($equipe);

            /** a chaque 20 iterations on fait un flush  */
            if (($i % $batchSize) === 0) {

                $em->flush();
                // Detaches all objects from Doctrine for memory save
                $em->clear();

                // Advancing for progress display on console
                $progress->advance($batchSize);

                $now = new \DateTime();
                $output->writeln(' equipe importée ... | ' . $now->format('d-m-Y G:i:s'));

            }

            $i++;
        }
        /** Flush and clear */
        $em->flush();
        $em->clear();

        /** Finaliser le process */
        $progress->finish();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return array|bool
     */
    public function get(InputInterface $input, OutputInterface $output)
    {
        $date = new  \DateTime();

        /** recuperer le fichier csv */
        $filename = "web/uploads/import/equipe/equipe_" . $date->format('d-m-Y') . ".csv";

        /** conversion du fichier csv to array */
        $converter = $this->getContainer()->get('adminisitration_csv.csv_import.convert_csv_to_array');
        $data = $converter->convert($filename, ';');

        return $data;
    }


}