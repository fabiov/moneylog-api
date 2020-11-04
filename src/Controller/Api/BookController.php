<?php

namespace App\Controller\Api;

use App\Service\Domain\Repository\BookRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RegistrationController
 * @Route("/api-library")
 */
class BookController extends AbstractController
{
    /**
     * @Route("/books", name = "api_library_post_book_collection", methods = {"POST"})
     * @param Request $request
     * @param BookRepository $bookRepository
     * @return Response
     */
    public function postBooksCollection(Request $request, BookRepository $bookRepository)
    {
        $errs = [];
        $parameters = [];
        $data = json_decode($request->getContent(), true);

        if (isset($data['author']) && isset($data['author']['id'])) {
            $parameters['author_id'] = $data['author']['id'];
        } else {
            $errs['author_id'] = 'missing parameters';
        }

        if (isset($data['title'])) {
            $parameters['title'] = $data['title'];
        } else {
            $errs['title'] = 'missing parameters';
        }

        if (isset($data['isbn'])) {
            $parameters['isbn'] = $data['isbn'];
        } else {
            $errs['isbn'] = 'missing parameters';
        }

        if (isset($data['description'])) {
            $parameters['description'] = $data['description'];
        } else {
            $errs['description'] = 'missing parameters';
        }

        if (isset($data['price'])) {
            $parameters['price'] = $data['price'];
        } else {
            $errs['price'] = 'missing parameters';
        }

        if (isset($data['availability'])) {
            $parameters['availability'] = $data['availability'];
        } else {
            $errs['availability'] = 'missing parameters';
        }

        if ($errs) {
            return new JsonResponse($errs, Response::HTTP_BAD_REQUEST);
        }

        try {
            $id = $bookRepository->add($parameters);
        } catch (UniqueConstraintViolationException $e) {
            return new JsonResponse('Integrity constraint violation', Response::HTTP_BAD_REQUEST);
        } catch (Exception $exception) {
            return new JsonResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($bookRepository->findOne($id), Response::HTTP_CREATED);
    }

    /**
     * @Route("/books", name = "api_library_get_books_collection", methods = {"GET"})
     * @param Request $request
     * @param BookRepository $bookRepository
     * @return Response
     */
    public function getBooksCollection(Request $request, BookRepository $bookRepository)
    {
        $filters = [];
        if (null !== ($bTitle = $request->query->get('title'))) {
            $filters['title'] = "%$bTitle%";
        }
        if (null !==  ($bIsbn = $request->query->get('isbn'))) {
            $filters['isbn'] = "%$bIsbn%";
        }
        if (null !== ($aName = $request->query->get('author_name'))) {
            $filters['author.name'] = "%$aName%";
        }
        $data = $bookRepository->find($filters);

        return new JsonResponse($data, $data ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }

    /**
     * @Route("/books/{id}", name = "api_library_get_book_item", methods = {"GET"}, requirements={"id"="\d+"})
     * @param Request $request
     * @param BookRepository $bookRepository
     * @return Response
     */
    public function getBookItem(Request $request, BookRepository $bookRepository)
    {
        $data = $bookRepository->findOne((int) $request->get('id'));
        return new JsonResponse($data, $data ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }
}
