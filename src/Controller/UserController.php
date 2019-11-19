<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * https://stackoverflow.com/questions/52528915/using-api-platform-automatically-assign-user-to-object-onetomany
 * https://github.com/api-platform/core/issues/477 me action
 *
 * @Route("/api")
 */
class UserController extends AbstractController
{
    /**
     * @Route(
     *     name="api_users_post",
     *     path="/users",
     *     methods={"POST"},
     *     defaults={"_api_resource_class"=User::class, "_api_collection_operation_name"="post"}
     * )
     * @param User $data
     * @param UserPasswordEncoderInterface $encoder
     * @return User
     */
    public function postAction(User $data, UserPasswordEncoderInterface $encoder): User
    {
        return $this->encodePassword($data, $encoder);
    }

    /**
     * @Route(
     *     name="api_users_put",
     *     path="/users/{id}",
     *     requirements={"id"="\d+"},
     *     methods={"PUT"},
     *     defaults={"_api_resource_class"=User::class, "_api_item_operation_name"="put"}
     * )
     * @param User $data
     * @param UserPasswordEncoderInterface $encoder
     * @param TokenStorageInterface $tokenStorage
     * @return User
     */
    public function putAction(User $data, UserPasswordEncoderInterface $encoder, TokenStorageInterface $tokenStorage): User
    {
        $this->checkAccess($data, $tokenStorage);
        return $this->encodePassword($data, $encoder);
    }

    /**
     * @Route(
     *     name="api_users_get",
     *     path="/users/{id}",
     *     requirements={"id"="\d+"},
     *     methods={"GET"},
     *     defaults={"_api_resource_class"=User::class, "_api_item_operation_name"="get"}
     * )
     * @param User $data
     * @param UserPasswordEncoderInterface $encoder
     * @param TokenStorageInterface $tokenStorage
     * @return User
     */
    public function getAction(User $data, UserPasswordEncoderInterface $encoder, TokenStorageInterface $tokenStorage): User
    {
        $this->checkAccess($data, $tokenStorage);
        return $data;
    }

    /**
     * @param User $data
     * @param TokenStorageInterface $tokenStorage
     */
    private function checkAccess(User $data, TokenStorageInterface $tokenStorage) {
        if ($tokenStorage->getToken()->getUser()->getId() != $data->getId()) {
            throw new AccessDeniedHttpException();
        }
    }

    /**
     * @param User $data
     * @param UserPasswordEncoderInterface $encoder
     * @return User
     */
    private function encodePassword(User $data, UserPasswordEncoderInterface $encoder): User
    {
        $encoded = $encoder->encodePassword($data, $data->getPassword());
        $data->setPassword($encoded);
        return $data;
    }
}