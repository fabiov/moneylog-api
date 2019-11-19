<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
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
     * @return User
     */
    public function putAction(User $data, UserPasswordEncoderInterface $encoder): User
    {
        return $this->encodePassword($data, $encoder);
    }

    protected function encodePassword(User $data, UserPasswordEncoderInterface $encoder): User
    {
        $encoded = $encoder->encodePassword($data, $data->getPassword());
        $data->setPassword($encoded);
        return $data;
    }
}