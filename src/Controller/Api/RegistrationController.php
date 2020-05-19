<?php

namespace App\Controller\Api;

use App\Entity\User;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
     * @return Response
     */
    public function newUser(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $em = $this->getDoctrine()->getManager();

        $password = $request->get('password');

        $user = (new User())
            ->setEmail($request->get('email'))
            ->setName($request->get('name'))
            ->setSurname($request->get('surname'));
        $user->setPassword($passwordEncoder->encodePassword($user, $password));
        $em->persist($user);

        try {
            $em->flush();
        } catch (UniqueConstraintViolationException$e) {
            return new JsonResponse(['message' => 'User already exists'], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(null, Response::HTTP_CREATED);
    }
}
