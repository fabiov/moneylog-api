<?php

namespace App\Controller\Api;

use App\Entity\Setting;
use App\Entity\User;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class RegistrationController
 * @Route("/registration")
 */
class RegistrationController extends AbstractController
{
    /**
     * @Route("/new-user", name = "api_new_registration", methods = {"POST"})
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param NormalizerInterface $normalizer
     * @return Response
     * @throws Exception|ExceptionInterface
     */
    public function newUser(
        Request $request, UserPasswordEncoderInterface $passwordEncoder, NormalizerInterface $normalizer
    ) {
        $em = $this->getDoctrine()->getManager();

        $password = $request->get('password');

        $user = (new User())
            ->setEmail($request->get('email'))
            ->setName($request->get('name'))
            ->setSurname($request->get('surname'));
        $user->setPassword($passwordEncoder->encodePassword($user, $password));
        $em->persist($user);

        $setting = (new Setting())->setUser($user);
        $em->persist($setting);

        try {
            $em->flush();
        } catch (UniqueConstraintViolationException $e) {
            return new JsonResponse(['message' => 'User already exists'], Response::HTTP_BAD_REQUEST);
        }
        return new JsonResponse($normalizer->normalize($user), Response::HTTP_CREATED);
    }
}
