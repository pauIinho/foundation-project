<?php

namespace AppBundle\Controller\Cabinet;

use AppBundle\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class OrderController extends Controller
{
    /**
     * Cart for ward
     *
     * @Route("/cabinet/orders/cart", name="orders_cart")
     * @Method({"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function cartAction()
    {
        /** @var \AppBundle\Entity\User $user */
        $user = $this->getUser();

        if ('ward' !== $user->getType()) {
            throw new AccessDeniedHttpException('Функционал недоступен данному типу пользователя');
        }

        $em = $this->getDoctrine()->getManager();
        $orderRepository = $em->getRepository('AppBundle\Entity\Order');
        $wardRepository = $em->getRepository('AppBundle\Entity\Ward');
        $ward = $wardRepository->findOneBy(['user' => $user]);
        $order = $orderRepository->findOneBy(['ward' => $ward, 'status' => 0]);

        return $this->render('@App/Cabinet/Order/orders_cart.html.twig', [
            'order' => $order
        ]);
    }

    /**
     * Create new order by ward
     *
     * @Route("/cabinet/orders/create", name="create_order")
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $user = $this->getUser();

        if ('ward' !== $user->getType()) {
            throw new AccessDeniedHttpException('Функционал недоступен данному типу пользователя');
        }

        $em = $this->getDoctrine()->getManager();
        $wardRepository = $em->getRepository('AppBundle\Entity\Ward');
        $ward = $wardRepository->findOneBy(['user' => $user]);

        $orderId = $request->request->get('order');
        $orderRepository = $em->getRepository('AppBundle\Entity\Order');
        $order = $orderRepository->findOneBy(['ward' => $ward, 'id' => (int) $orderId, 'status' => 0]);

        if (null !== $order) {
            $order->setStatus(1);
            $order->setStartDate(new \DateTime());
            $em->persist($order);
            $em->flush();
            $this->addFlash('success', 'Заявка успешно создана');

            return $this->redirectToRoute('ward_orders');
        }

        $this->addFlash('error', 'Не удается создать заявку');
        return $this->redirectToRoute('orders_cart');
    }

    /**
     * Remove one donation from ward's order
     *
     * @Route("/cabinet/orders/remove-donation", name="remove_from_cart")
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removeFromCartAction(Request $request)
    {
        $user = $this->getUser();

        if ('ward' !== $user->getType()) {
            return new JsonResponse(['success' => false]);
        }

        $em = $this->getDoctrine()->getManager();
        $wardRepository = $em->getRepository('AppBundle\Entity\Ward');
        $ward = $wardRepository->findOneBy(['user' => $user]);

        $orderId = $request->request->get('order_id');
        $donationId = $request->request->get('donation_id');
        $orderRepository = $em->getRepository('AppBundle\Entity\Order');
        $order = $orderRepository->findOneBy(['ward' => $ward, 'id' => (int) $orderId]);

        $donationRepository = $em->getRepository('AppBundle\Entity\Donation');
        $donation = $donationRepository->find($donationId);
        if (null !== $order) {
            $donation->removeOrder($order);
            $em->persist($donation);
            $em->flush();

            return new JsonResponse(['success' => true]);
        }

        return new JsonResponse([
            'success' => false
        ]);
    }

    /**
     * List of opened and closed ward's orders
     *
     * @Route("/cabinet/orders/all", name="ward_orders")
     * @Method({"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function wardOrdersAction()
    {
        $user = $this->getUser();

        if ('ward' !== $user->getType()) {
            throw new AccessDeniedHttpException('Функционал недоступен данному типу пользователя');
        }

        $em = $this->getDoctrine()->getManager();
        $wardRepository = $em->getRepository('AppBundle\Entity\Ward');
        $ward = $wardRepository->findOneBy(['user' => $user]);

        $orderRepository = $em->getRepository('AppBundle\Entity\Order');
        $currentOrders = $orderRepository->findBy(['ward' => $ward, 'status' => 1]);
        $closedOrders = $orderRepository->findBy(['ward' => $ward, 'status' => 3]);

        return $this->render('@App/Cabinet/Order/ward_orders.html.twig', [
            'currentOrders' => $currentOrders,
            'closedOrders' => $closedOrders,
        ]);
    }

    /**
     * Cancel ward's order
     *
     * @Route("/cabinet/orders/cancel", name="cancel_order")
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function cancelOrderAction(Request $request)
    {
        $user = $this->getUser();

        if ('ward' !== $user->getType()) {
            return new JsonResponse(['success' => false]);
        }

        $em = $this->getDoctrine()->getManager();
        $wardRepository = $em->getRepository('AppBundle\Entity\Ward');
        $ward = $wardRepository->findOneBy(['user' => $user]);

        $orderId = $request->request->get('order_id');
        $orderRepository = $em->getRepository('AppBundle\Entity\Order');
        $order = $orderRepository->findOneBy(['ward' => $ward, 'id' => (int) $orderId]);

        if (null !== $order) {
            $order->setStatus(2);
            $em->persist($order);
            $em->flush();

            return new JsonResponse(['success' => true]);
        }

        return new JsonResponse(['success' => false]);
    }

    /**
     * Add new donation to ward's cart
     *
     * @Route("/cabinet/orders/cart/add", name="add_to_cart")
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addDonationToCartAction(Request $request)
    {
        $user = $this->getUser();

        if ('ward' !== $user->getType()) {
            throw new AccessDeniedHttpException('Функционал недоступен данному типу пользователя');
        }

        $em = $this->getDoctrine()->getManager();
        $wardRepository = $em->getRepository('AppBundle\Entity\Ward');
        $ward = $wardRepository->findOneBy(['user' => $user]);

        $donationId = $request->request->get('donation_id');

        $donationRepository = $em->getRepository('AppBundle\Entity\Donation');
        $donation = $donationRepository->find($donationId);

        if ($donation === null) {
            return new JsonResponse(['success' => false]);
        }

        $orderRepository = $em->getRepository('AppBundle\Entity\Order');
        $cartOrder = $orderRepository->findOneBy(['ward' => $ward, 'status' => 0]);

        if (null !== $cartOrder) {
            $donation->addOrder($cartOrder);
            $em->persist($donation);
            $em->flush();
        } else {
            $order = new Order();
            $order->setWard($ward);
            $order->setStatus(0);
            $em->persist($order);
            $em->flush();
            $donation->addOrder($order);
            $em->persist($donation);
            $em->flush();
        }

        return new JsonResponse(['success' => true]);
    }
}