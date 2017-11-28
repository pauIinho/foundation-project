<?php
namespace AppBundle\Controller\Cabinet;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class AdminController extends Controller
{
    /**
     * Form for adding donation
     *
     * @Route("/cabinet/all-bases", name="all_bases")
     * @Method({"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function allBasesAction()
    {
        /** @var \AppBundle\Entity\User $user */
        $user = $this->getUser();

        if ('admin' !== $user->getType()) {
            throw new AccessDeniedHttpException('Функционал недоступен данному типу пользователя');
        }

        $em = $this->getDoctrine()->getManager();

        $donationsRepository = $em->getRepository('AppBundle\Entity\Donation');
        $donations = $donationsRepository->findBy(['status' => [0, 1, 2, 3]], ['receiptDate' => 'DESC']);

        $ordersRepository = $em->getRepository('AppBundle\Entity\Order');
        $orders = $ordersRepository->findBy(['status' => [1, 2, 3]], ['startDate' => 'DESC']);

        $contributorsRepository = $em->getRepository('AppBundle\Entity\Contributor');
        $contributors = $contributorsRepository->findAll();

        $wardsRepository = $em->getRepository('AppBundle\Entity\Ward');
        $wards = $wardsRepository->findAll();

        return $this->render('@App/Cabinet/Admin/all_bases.html.twig', [
            'donations' => $donations,
            'orders' => $orders,
            'contributors' => $contributors,
            'wards' => $wards
        ]);
    }
}