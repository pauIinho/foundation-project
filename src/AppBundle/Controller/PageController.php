<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class PageController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction()
    {
        return $this->render('@App/index.html.twig');
    }

    /**
     * @Route("/donations", name="donations")
     */
    public function donationsAction()
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $repository = $em->getRepository('AppBundle:Donation');
        $donations = $repository->findBy(['status' => 0]);

        return $this->render('@App/donations.html.twig', [
            'donations' => $donations
        ]);
    }

    /**
     * @Route("/contributors", name="contributors")
     */
    public function contributorsAction()
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $repository = $em->getRepository('AppBundle:Contributor');
        $contributors = $repository->findAll();

        return $this->render('@App/contributors.html.twig', [
            'contributors' => $contributors
        ]);
    }

    /**
     * @Route("/wards", name="wards")
     */
    public function wardsAction()
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $repository = $em->getRepository('AppBundle:Ward');
        $wards = $repository->findAll();

        return $this->render('@App/wards.html.twig', [
            'wards' => $wards
        ]);
    }

    /**
     * @Route("/about", name="about")
     */
    public function aboutAction()
    {
        return $this->render('@App/about.html.twig');
    }

    /**
     * @Route("/contacts", name="contacts")
     */
    public function contactsAction()
    {
        return $this->render('@App/contacts.html.twig');
    }
}