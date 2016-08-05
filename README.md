# Export_Impot_Csv_Symfony2
Méthode facile à exporter vos données de BDD via CSV et pourvoir importer d'autre par la suite ...  Cette méthode fonctionne correctement. Par contre, elle n'est pas la meilleur. 


#Export DBB to CSV en Symfony2 

Dans le controleur de le controleur de l'unitée en question, on ajoutera la methode : 

  
    public function generateCsvAction()
    {
        $Equipe_manager = $this->container->get('equipe.manager.equipe_manager');

        $export_manager = $this->container->get('administration_csv.manager.export_manager');

        /** Se connecter à la base de données  */
        $conn = $this->get('database_connection');
        /** donner  un nom au  fichier */
         $filename = $export_manager->getNameCsvFile("equipe");
        /** retour de requette en SQL*/
        $requette = $Equipe_manager->getSqlEquipes();


        $stmt = $conn->prepare($requette);
        $stmt->execute();

        $response = new StreamedResponse();
        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=' . $filename);
        $response->setCallback(function () use ($stmt) {
            $config = new ExporterConfig();
            $exporter = new Exporter($config);

            $exporter->export('php://output', new PdoCollection($stmt->getIterator()));
        });


        $response->send();

        return $response;
    }
 
 
 Dans le routing : 
 
 csv_upload:
    path:    /equipe/equipes_csv
    defaults: { _controller: EquipeBundle:Equipes:generateCsv }
 

#Import CSV to BDD en Symfony2 
Dans le cas de l'import, j'ai crée une command  qui me permet d'uploader le contenu d'une fichier csv stoquer dans web/uploads/import/equipe/ 

La command : 
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

