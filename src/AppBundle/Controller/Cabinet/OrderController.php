<?php

namespace AppBundle\Controller\Cabinet;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class OrderController extends Controller
{
    /**
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
            $em->persist($order);
            $em->flush();
            $this->addFlash('success', 'Заявка успешно создана');

            return $this->redirectToRoute('orders_cart');
        }

        $this->addFlash('error', 'Не удается создать заявку');
        return $this->redirectToRoute('orders_cart');
    }
}