<?php

namespace AppBundle\Controller\Cabinet;

use AppBundle\Entity\User;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends Controller
{
    /**
     * @Route("/cabinet/profile", name="cabinet_profile")
     * @Method({"GET"})
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

        return null;
    }

    /**
     * @Route("/cabinet/profile/update", name="cabinet_profile_update")
     * @Method({"POST"})
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();
        $form = $request->request->all();
        $em = $this->getDoctrine()->getManager();

        try {
            switch ($user->getType()) {
                case 'contributor':
                    $contributorRepository = $em->getRepository('AppBundle\Entity\Contributor');
                    $contributor = $contributorRepository->findOneBy(['user' => $user]);

                    if ($contributor === null) {
                        throw new \Exception('Произошла непредвиденная ошибка');
                    }

                    if (isset($form['full_name']) && !empty($form['full_name']) &&
                        $contributor->getName() !== $form['full_name']) {

                        $contributor->setName($form['full_name']);
                    }

                    if (isset($form['organization']) && !empty($form['organization']) &&
                        $contributor->getOrganizationName() !== $form['organization']) {

                        $contributor->setOrganizationName($form['organization']);
                    }

                    if (isset($form['contact_phone']) && !empty($form['contact_phone']) &&
                        $contributor->getContactPhone() !== $form['contact_phone']) {

                        $contributor->setContactPhone($form['contact_phone']);
                    }

                    if ($request->files->has('image') && null !== $request->files->get('image')) {
                        /** @var File $file */
                        $file = $request->files->get('image');

                        $fileName = md5(uniqid()).'.'.$file->guessExtension();
                        $file->move($this->getParameter('uploads_directory'), $fileName);

                        $contributor->setImage($fileName);
                    }

                    $em->persist($contributor);
                    $em->flush();

                    break;
                case 'ward':
                    $wardRepository = $em->getRepository('AppBundle\Entity\Ward');
                    $ward = $wardRepository->findOneBy(['user' => $user]);

                    if ($ward === null) {
                        throw new \Exception('Произошла непредвиденная ошибка');
                    }

                    if (isset($form['full_name']) && !empty($form['full_name']) &&
                        $ward->getContactFullname() !== $form['full_name']) {

                        $ward->setContactFullname($form['full_name']);
                    }

                    if (isset($form['organization']) && !empty($form['organization']) &&
                        $ward->getName() !== $form['organization']) {

                        $ward->setName($form['organization']);
                    }

                    if (isset($form['address']) && !empty($form['address']) &&
                        $ward->getAddress() !== $form['address']) {

                        $ward->setAddress($form['address']);
                    }

                    if (isset($form['contact_phone']) && !empty($form['contact_phone']) &&
                        $ward->getContactPhone() !== $form['contact_phone'] ) {

                        $ward->setContactPhone($form['contact_phone']);
                    }

                    if ($request->files->has('image') && null !== $request->files->get('image')) {
                        /** @var File $file */
                        $file = $request->files->get('image');

                        $fileName = md5(uniqid()).'.'.$file->guessExtension();
                        $file->move($this->getParameter('uploads_directory'), $fileName);

                        $ward->setImage($fileName);
                    }

                    $em->persist($ward);
                    $em->flush();

                    break;
            }

            if (isset($form['email']) && !empty($form['email']) && $user->getEmail() !== $form['email']) {
                $userRepository = $em->getRepository('AppBundle\Entity\User');
                $isEmailExists = (bool) $userRepository->findOneBy(['email' => $form['email']]);

                if ($isEmailExists) {
                    throw new Exception('Пользователь с таким email уже существует');
                }

                $user->setEmail($form['email']);
                $user->setUsername($form['email']);
            }
            if (isset($form['password']) && !empty($form['password'])) {
                $user->setPlainPassword($form['password']);
            }
            $em->persist($user);
            $em->flush();

        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('cabinet_profile');
        }

        $this->addFlash('success', 'Данные успешно сохранены');
        return $this->redirectToRoute('cabinet_profile');
    }
}