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


class DonationController extends Controller
{
    /**
     * Form for adding donation
     *
     * @Route("/cabinet/donations/new", name="new_donation")
     * @Method({"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newDonationAction()
    {
        /** @var \AppBundle\Entity\User $user */
        $user = $this->getUser();

        if ('contributor' !== $user->getType()) {
            throw new AccessDeniedHttpException('Функционал недоступен данному типу пользователя');
        }

        return $this->render('@App/Cabinet/Donation/new_donation.html.twig');
    }

    /**
     * Add donation
     *
     * @Route("/cabinet/donations/add", name="add_donation")
     * @Method({"POST"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addDonationAction(Request $request)
    {
        /** @var \AppBundle\Entity\User $user */
        $user = $this->getUser();

        if ('contributor' !== $user->getType()) {
            throw new AccessDeniedHttpException('Функционал недоступен данному типу пользователя');
        }

        $em = $this->getDoctrine()->getManager();
        $contributorRepository = $em->getRepository('AppBundle\Entity\Contributor');
        $contributor = $contributorRepository->findOneBy(['user' => $user]);

        $form = $request->request->all();

        $em = $this->getDoctrine()->getManager();
        $donation = new Donation();
        $donation->setContributor($contributor);
        $donation->setName($form['name']);
        $donation->setDescription($form['description']);
        $donation->setReceiptDate(new \DateTime());
        $donation->setStatus(0);

        if ($request->files->has('image')) {
            /** @var File $file */
            $file = $request->files->get('image');

            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('uploads_directory_donations'), $fileName);

            $donation->setImage($fileName);
        }
        $em->persist($donation);
        $em->flush();

        $this->addFlash('success', 'Пожертвование успешно добавлено');
        return $this->redirectToRoute('new_donation');
    }

    /**
     * List of contributor's donations
     *
     * @Route("/cabinet/donations/all", name="contributor_donations")
     * @Method({"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function contributorDonationsAction()
    {
        /** @var \AppBundle\Entity\User $user */
        $user = $this->getUser();

        if ('contributor' !== $user->getType()) {
            throw new AccessDeniedHttpException('Функционал недоступен данному типу пользователя');
        }

        $em = $this->getDoctrine()->getManager();
        $contributorRepository = $em->getRepository('AppBundle\Entity\Contributor');
        $contributor = $contributorRepository->findOneBy(['user' => $user]);

        $donationRepostory = $em->getRepository('AppBundle\Entity\Donation');
        $currentDonations = $donationRepostory->findBy(['contributor' => $contributor, 'status' => [0, 1]]);
        $archiveDonations = $donationRepostory->findBy(['contributor' => $contributor, 'status' => 2]);

        return $this->render('@App/Cabinet/Donation/cotributor_donations.html.twig', [
            'currentDonations' => $currentDonations,
            'archiveDonations' => $archiveDonations,
        ]);
    }
}