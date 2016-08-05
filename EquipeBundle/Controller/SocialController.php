<?php

namespace CMS\EquipeBundle\Controller;

use Administration\TraitBundle\Consts\FlashBagConst;
use CMS\EquipeBundle\Entity\Social;
use CMS\EquipeBundle\Form\SocialType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SocialController
 * @package CMS\EquipeBundle\Controller
 */
class SocialController extends Controller
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('EquipeBundle:Social')->findAll();

        return $this->render('EquipeBundle:Social:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $entity = new Social();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get("session")->getFlashBag()->add(FlashBagConst::success_alert, FlashBagConst::success_message);

            return $this->redirect($this->generateUrl('social_adresse_show', array('id' => $entity->getId())));
        }
        $this->get("session")->getFlashBag()->add(FlashBagConst::danger_alert, FlashBagConst::danger_message);

        return $this->render('EquipeBundle:Social:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Social entity.
     *
     * @param Social $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Social $entity)
    {
        $form = $this->createForm(new SocialType(), $entity, array(
            'action' => $this->generateUrl('social_adresse_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array(
            'label' => 'Create',
            'attr' => array(
                'class' => 'btn btn-success'
            )
        ));

        return $form;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction()
    {
        $entity = new Social();
        $form = $this->createCreateForm($entity);

        return $this->render('EquipeBundle:Social:new.html.twig', array(
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

        $entity = $em->getRepository('EquipeBundle:Social')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Social entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('EquipeBundle:Social:show.html.twig', array(
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

        $entity = $em->getRepository('EquipeBundle:Social')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Social entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('EquipeBundle:Social:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Social entity.
     *
     * @param Social $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Social $entity)
    {
        $form = $this->createForm(new SocialType(), $entity, array(
            'action' => $this->generateUrl('social_adresse_update', array('id' => $entity->getId())),
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

        $entity = $em->getRepository('EquipeBundle:Social')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Social entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->get("session")->getFlashBag()->add(FlashBagConst::success_alert, FlashBagConst::success_message);

            return $this->redirect($this->generateUrl('social_adresse_edit', array('id' => $id)));
        }
        $this->get("session")->getFlashBag()->add(FlashBagConst::danger_alert, FlashBagConst::danger_message);

        return $this->render('EquipeBundle:Social:edit.html.twig', array(
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
            $entity = $em->getRepository('EquipeBundle:Social')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Social entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('social_adresse'));
    }

    /**
     * Creates a form to delete a Social entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('social_adresse_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array(
                'label' => 'Delete',
                'attr' => array(
                    'class' => "btn btn-danger btn-block",
                    'style' => 'margin-top:5px;'
                )
            ))
            ->getForm();
    }
}
