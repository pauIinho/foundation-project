<?php
namespace AppBundle\Controller\Cabinet;

use AppBundle\Entity\Donation;
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

    /**
     * Approve donation
     *
     * @Route("/cabinet/approve-donation", name="admin_approve_donation")
     * @Method({"POST"})
     *
     * @param $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function approveDonation(Request $request)
    {
        /** @var \AppBundle\Entity\User $user */
        $user = $this->getUser();

        if ('admin' !== $user->getType()) {
            return new JsonResponse(['success' => false]);
        }

        $donationId = $request->request->get('donation_id');
        $em = $this->getDoctrine()->getManager();
        $donationRepostory = $em->getRepository('AppBundle\Entity\Donation');
        $donation = $donationRepostory->findOneBy(['id' => $donationId, 'status' => 0]);

        if (null !== $donation) {
            $donation->setStatus(1);
            $em->persist($donation);
            $em->flush();
            return new JsonResponse(['success' => true]);
        }

        return new JsonResponse(['success' => false]);
    }

    /**
     * Cancel donation
     *
     * @Route("/cabinet/cancel-donation", name="admin_cancel_donation")
     * @Method({"POST"})
     *
     * @param $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function cancelDonation(Request $request)
    {
        /** @var \AppBundle\Entity\User $user */
        $user = $this->getUser();

        if ('admin' !== $user->getType()) {
            return new JsonResponse(['success' => false]);
        }

        $donationId = $request->request->get('donation_id');
        $em = $this->getDoctrine()->getManager();
        $donationRepostory = $em->getRepository('AppBundle\Entity\Donation');
        $donation = $donationRepostory->findOneBy(['id' => $donationId, 'status' => [0, 1]]);

        if (null !== $donation) {
            $donation->setStatus(2);
            $em->persist($donation);
            $em->flush();
            return new JsonResponse(['success' => true]);
        }

        return new JsonResponse(['success' => false]);
    }

    /**
     * Complete order
     *
     * @Route("/cabinet/complete-order", name="admin_complete_order")
     * @Method({"POST"})
     *
     * @param $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function completeOrder(Request $request)
    {
        /** @var \AppBundle\Entity\User $user */
        $user = $this->getUser();

        if ('admin' !== $user->getType()) {
            return new JsonResponse(['success' => false]);
        }

        $orderId = $request->request->get('order_id');
        $em = $this->getDoctrine()->getManager();
        $orderRepository = $em->getRepository('AppBundle\Entity\Order');
        $order = $orderRepository->findOneBy(['id' => $orderId, 'status' => 1]);

        if (null !== $order) {
            $order->setStatus(3);
            $order->setCloseDate(new \DateTime());
            /** @var Donation $donation */
            foreach ($order->getDonations() as $donation) {
                $donation->setStatus(3);
            }
            $em->persist($order);
            $em->flush();
            return new JsonResponse(['success' => true]);
        }

        return new JsonResponse(['success' => false]);
    }

    /**
     * Cancel order
     *
     * @Route("/cabinet/cancel-order", name="admin_cancel_order")
     * @Method({"POST"})
     *
     * @param $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function cancelOrder(Request $request)
    {
        /** @var \AppBundle\Entity\User $user */
        $user = $this->getUser();

        if ('admin' !== $user->getType()) {
            return new JsonResponse(['success' => false]);
        }

        $orderId = $request->request->get('order_id');
        $em = $this->getDoctrine()->getManager();
        $orderRepository = $em->getRepository('AppBundle\Entity\Order');
        $order = $orderRepository->findOneBy(['id' => $orderId, 'status' => 1]);

        if (null !== $order) {
            $order->setStatus(2);
            $em->persist($order);
            $em->flush();
            return new JsonResponse(['success' => true]);
        }

        return new JsonResponse(['success' => false]);
    }

    /**
     * Confirm contributor
     *
     * @Route("/cabinet/confirm-contributor", name="admin_confirm_contributor")
     * @Method({"POST"})
     *
     * @param $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function confirmContributor(Request $request)
    {
        /** @var \AppBundle\Entity\User $user */
        $user = $this->getUser();

        if ('admin' !== $user->getType()) {
            return new JsonResponse(['success' => false]);
        }

        $contributorId = $request->request->get('contributor_id');
        $em = $this->getDoctrine()->getManager();
        $contributorRepository = $em->getRepository('AppBundle\Entity\Contributor');
        $contributor = $contributorRepository->findOneBy(['id' => $contributorId]);

        if (null !== $contributor) {
            $contributor->getUser()->setConfirmed(1);
            $em->persist($contributor);
            $em->flush();
            return new JsonResponse(['success' => true]);
        }

        return new JsonResponse(['success' => false]);
    }

    /**
     * Confirm ward
     *
     * @Route("/cabinet/confirm-ward", name="admin_confirm_ward")
     * @Method({"POST"})
     *
     * @param $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function confirmWard(Request $request)
    {
        /** @var \AppBundle\Entity\User $user */
        $user = $this->getUser();

        if ('admin' !== $user->getType()) {
            return new JsonResponse(['success' => false]);
        }

        $wardId = $request->request->get('ward_id');
        $em = $this->getDoctrine()->getManager();
        $wardRepository = $em->getRepository('AppBundle\Entity\Ward');
        $ward = $wardRepository->findOneBy(['id' => $wardId]);

        if (null !== $ward) {
            $ward->getUser()->setConfirmed(1);
            $em->persist($ward);
            $em->flush();
            return new JsonResponse(['success' => true]);
        }

        return new JsonResponse(['success' => false]);
    }
}