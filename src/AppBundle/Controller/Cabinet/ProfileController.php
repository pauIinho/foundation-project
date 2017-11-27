<?php

namespace AppBundle\Controller\Cabinet;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends Controller
{
    /**
     * @Route("/cabinet/profile", name="cabinet_profile")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        /** @var User $user */
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        switch ($user->getType()) {
            case 'contributor':
                $contributorRepository = $em->getRepository('AppBundle\Entity\Contributor');
                $contributor = $contributorRepository->findOneBy(['user' => $user]);

                return $this->render('@App/Cabinet/Profile/profile_contributor.html.twig', [
                    'contributor' => $contributor,
                    'user' => $user,
                ]);
                break;
            case 'ward':
                $wardRepository = $em->getRepository('AppBundle\Entity\Ward');
                $ward = $wardRepository->findOneBy(['user' => $user]);

                return $this->render('@App/Cabinet/Profile/profile_ward.html.twig', [
                    'ward' => $ward,
                    'user' => $user,
                ]);
                break;
            case 'admin':
                return $this->render('@App/Cabinet/Profile/profile_admin.html.twig', [
                    'user' => $user,
                ]);
                break;
        }
    }
}