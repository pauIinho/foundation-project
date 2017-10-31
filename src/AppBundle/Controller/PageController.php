<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class PageController extends Controller
{
    /**
     * @Route("/", name="donations")
     */
    public function donationsAction() {
        return $this->render('@App/donations.html.twig');
    }

    /**
     * @Route("/contributors", name="contributors")
     */
    public function contributorsAction() {
        return $this->render('@App/contributors.html.twig');
    }

    /**
     * @Route("/wards", name="wards")
     */
    public function wardsAction() {
        return $this->render('@App/wards.html.twig');
    }
}