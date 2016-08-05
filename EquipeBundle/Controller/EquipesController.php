<?php

namespace CMS\EquipeBundle\Controller;

use Administration\CSVBundle\Csv\Export\Standard\Collection\PdoCollection;
use Administration\CSVBundle\Csv\Export\Standard\Exporter;
use Administration\CSVBundle\Csv\Export\Standard\ExporterConfig;
use Administration\TraitBundle\Consts\FlashBagConst;
use CMS\EquipeBundle\Entity\Equipes;
use CMS\EquipeBundle\Entity\Social;
use CMS\EquipeBundle\Form\EquipesType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Class EquipesController
 * @package CMS\EquipeBundle\Controller
 */
class EquipesController extends Controller
{
    /**
     * @return StreamedResponse
     */
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

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response

    public function generateCsvAction(Request $request)
     * {
     * $repository = $this->getDoctrine()->getRepository('EquipeBundle:Equipes');
     * $query = $repository->createQueryBuilder('e');
     * $query->innerJoin('e.adresses', 'adresses');
     * $query->innerJoin('e.galleries', 'galleries');
     * $query->innerJoin('e.socials', 'socials');
     * $query->addSelect('adresses', 'galleries', 'socials');
     * $query->orderBy('e.id', 'ASC');
     *
     * $data = $query->getQuery()->getResult();
     * $filename = 'export_equipe_' . date("Y_m_d_His") . ".csv";
     *
     * $response = $this->render('EquipeBundle:Equipes:exportCsv.html.twig', array(
     * 'data' => $data
     * ));
     *
     * $response->setStatusCode('200');
     * $response->headers->set('Content-Type', 'text/csv');
     * $response->headers->set('Content-Description', 'Submissions Export');
     * $response->headers->set('Content-Disposition', 'attachment; filename=' . $filename);
     * $response->headers->set('Content-Transfer-Encoding', 'binary');
     * $response->headers->set('Pragma', 'no-cache');
     * $response->headers->set('Expires', '0');
     * $response->prepare($request);
     * $response->sendHeaders();
     * $response->sendContent();
     *
     *
     * return $response;
     * } */

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public
    function homeAction()
    {
        $manager = $this->container->get('equipe.manager.equipe_manager');
        $equipes = $manager->getFullEquipes();

        return $this->render('EquipeBundle:Equipes:home.html.twig', array(
            'equipes' => $equipes,
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public
    function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $equipes = $em->getRepository('EquipeBundle:Equipes')->findAll();

        return $this->render('EquipeBundle:Equipes:index.html.twig', array(
            'equipes' => $equipes,

        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public
    function createAction(Request $request)
    {
        /** @var Equipes $equipe */
        $equipe = new Equipes();

        $form = $this->createCreateForm($equipe);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $equipe->getGalleries()->upload();

            /** l'ajout les nouvels enregistrement d'emploi */
            $equipe->getSocials()->map(function (Social $social) use ($equipe, $em) {
                $social->setType($equipe);
                $em->persist($equipe);
            });

            $em->flush();
            $this->get("session")->getFlashBag()->add(FlashBagConst::success_alert, FlashBagConst::success_message);

            return $this->redirect($this->generateUrl('equipes_show', array('id' => $equipe->getId())));
        }
        $this->get("session")->getFlashBag()->add(FlashBagConst::danger_alert, FlashBagConst::danger_message);
        return $this->render('EquipeBundle:Equipes:new.html.twig', array(
            'entity' => $equipe,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Equipes entity.
     *
     * @param Equipes $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private
    function createCreateForm(Equipes $entity)
    {
        $form = $this->createForm(new EquipesType(), $entity, array(
            'action' => $this->generateUrl('equipes_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Valider',
            'attr' => array(
                'class' => 'btn btn-success '
            )));

        return $form;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public
    function newAction()
    {
        $entity = new Equipes();
        $form = $this->createCreateForm($entity);

        return $this->render('EquipeBundle:Equipes:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public
    function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EquipeBundle:Equipes')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Equipes entity.');
        }


        $deleteForm = $this->createDeleteForm($id);

        return $this->render('EquipeBundle:Equipes:show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public
    function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EquipeBundle:Equipes')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Equipes entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('EquipeBundle:Equipes:show.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Equipes entity.
     *
     * @param Equipes $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private
    function createEditForm(Equipes $entity)
    {
        $form = $this->createForm(new EquipesType(), $entity, array(
            'action' => $this->generateUrl('equipes_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update', 'attr' => array(
            'class' => 'btn btn-success'
        )));

        return $form;
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public
    function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EquipeBundle:Equipes')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Equipes entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

            $entity->getGalleries()->upload();
            $entity->getSocials()->map(function (Social $social) use ($entity, $em) {
                $social->setEquipes($entity);
                $em->persist($entity);

            });
            $em->flush();
            $this->get("session")->getFlashBag()->add(FlashBagConst::success_alert, FlashBagConst::success_message);

            return $this->redirect($this->generateUrl('equipes_edit', array('id' => $id)));
        }
        $this->get("session")->getFlashBag()->add(FlashBagConst::danger_alert, FlashBagConst::danger_message);

        return $this->render('EquipeBundle:Equipes:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public
    function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('EquipeBundle:Equipes')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Equipes entity.');
            }
            $entity->getGalleries()->removeUpload();

            /** On supprime les anciens enregistrements */
            $entity->getSocials()->forAll(function ($k, Social $social) use ($entity, $em) {
                $social->setEquipes(null);
                $em->remove($entity);
                return true;
            });


            $em->flush();
        }

        return $this->redirect($this->generateUrl('equipes'));
    }

    /**
     * Creates a form to delete a Equipes entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private
    function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('equipes_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete', 'attr' => array(
                'class' => 'btn btn-block btn-danger',
                'style' => 'margin-top:5px;'
            )))
            ->getForm();
    }
}
