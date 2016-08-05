<?php

namespace CMS\EquipeBundle\Controller;

use Administration\TraitBundle\Consts\FlashBagConst;
use CMS\EquipeBundle\Entity\Adresse;
use CMS\EquipeBundle\Form\AdresseType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AdresseController
 * @package CMS\EquipeBundle\Controller
 */
class AdresseController extends Controller
{

    /**
     * Lists all Adresse entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('EquipeBundle:Adresse')->findAll();

        return $this->render('EquipeBundle:Adresse:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $entity = new Adresse();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get("session")->getFlashBag()->add(FlashBagConst::success_alert, FlashBagConst::success_message);

            return $this->redirect($this->generateUrl('equipe_adresse_show', array('id' => $entity->getId())));
        }
        $this->get("session")->getFlashBag()->add(FlashBagConst::danger_alert, FlashBagConst::danger_message);

        return $this->render('EquipeBundle:Adresse:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Adresse entity.
     *
     * @param Adresse $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Adresse $entity)
    {
        $form = $this->createForm(new AdresseType(), $entity, array(
            'action' => $this->generateUrl('equipe_adresse_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create', 'attr' => array(
            'class' => 'btn btn-success'
        )));

        return $form;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction()
    {
        $entity = new Adresse();
        $form = $this->createCreateForm($entity);

        return $this->render('EquipeBundle:Adresse:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EquipeBundle:Adresse')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Adresse entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('EquipeBundle:Adresse:show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EquipeBundle:Adresse')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Adresse entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('EquipeBundle:Adresse:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Adresse entity.
     *
     * @param Adresse $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Adresse $entity)
    {
        $form = $this->createForm(new AdresseType(), $entity, array(
            'action' => $this->generateUrl('equipe_adresse_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array(
            'label' => 'Mise à jour',
            'attr' => array(
                'class' => 'btn btn-success'
            )
        ));

        return $form;
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EquipeBundle:Adresse')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Adresse entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->get("session")->getFlashBag()->add(FlashBagConst::success_alert, FlashBagConst::success_message);

            return $this->redirect($this->generateUrl('equipe_adresse_edit', array('id' => $id)));
        }
        $this->get("session")->getFlashBag()->add(FlashBagConst::danger_alert, FlashBagConst::danger_message);

        return $this->render('EquipeBundle:Adresse:edit.html.twig', array(
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
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('EquipeBundle:Adresse')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Adresse entity.');
            }

            $em->remove($entity);
            $em->flush();
            $this->get("session")->getFlashBag()->add(FlashBagConst::success_alert, FlashBagConst::success_delete);

        }

        return $this->redirect($this->generateUrl('equipe_adresse'));
    }

    /**
     * Creates a form to delete a Adresse entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('equipe_adresse_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array(
                'label' => 'Supprimer',
                'attr' => array(
                    'class' => 'btn btn-danger btn-block'
                )
            ))
            ->getForm();
    }
}
