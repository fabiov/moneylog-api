<?php

namespace App\Controller\Api;

use App\Entity\Setting;
use App\Entity\User;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\DBAL\Statement;
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
 * @Route("/api-library")
 */
class BookController extends AbstractController
{
    /**
     * @Route("/books", name = "api_library_post_book_collection", methods = {"POST"})
     * @param Request $request
     * @return Response
     * @throws Exception|ExceptionInterface
     */
    public function postBooksCollection(Request $request)
    {
        $errs = [];
        $data = json_decode($request->getContent(), true);
        $sql = 'INSERT INTO book VALUES (null, :author_id, :title, :isbn, :description, :price, :availability)';

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

        /* @var Statement $stmt */
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $stmt = $conn->prepare($sql);

        try {
            $stmt->execute($parameters);
        } catch (UniqueConstraintViolationException $e) {
            return new JsonResponse('Integrity constraint violation', Response::HTTP_BAD_REQUEST);
        } catch (Exception $exception) {
            return new JsonResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(['id' => $conn->lastInsertId()], Response::HTTP_CREATED);
    }

    /**
     * @Route("/books", name = "api_library_get_books_collection", methods = {"GET"})
     * @param Request $request
     * @return Response
     * @throws Exception|ExceptionInterface
     */
    public function getBooksCollection(Request $request)
    {
        $data = [];
        $queryParameters = [];
        $query = self::bookQuery();

        if (null !== ($bTitle = $request->query->get('book_title'))) {
            $query .= ' AND b.title LIKE :b_title';
            $queryParameters['b_title'] = "%$bTitle%";
        }
        if (null !==  ($bIsbn = $request->query->get('book_isbn'))) {
            $query .= ' AND b.isbn LIKE :b_isbn';
            $queryParameters['b_isbn'] = "%$bIsbn%";
        }
        if (null !== ($aName = $request->query->get('author_name'))) {
            $query .= ' AND a.name LIKE :a_name';
            $queryParameters['a_name'] = "%$aName%";
        }

        /* @var Statement $stmt */
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute($queryParameters);

        while ($row = $stmt->fetch()) {
            $data[] = self::serialize($row);
        }

        return new JsonResponse($data, $data ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }

    /**
     * @Route("/books/{id}", name = "api_library_get_book_item", methods = {"GET"}, requirements={"id"="\d+"})
     * @param Request $request
     * @return Response
     * @throws Exception|ExceptionInterface
     */
    public function getBookItem(Request $request)
    {

        $data = [];
        $queryParameters = ['id' => $request->get('id')];
        $query = self::bookQuery();

        /* @var Statement $stmt */
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $stmt = $conn->prepare("$query AND b.id=:id");
        $stmt->execute($queryParameters);

        if (!empty($row = $stmt->fetch())) {
            $data = self::serialize($row);
        }

        return new JsonResponse($data, $data ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }

    private static function bookQuery()
    {
        return <<< EOL
SELECT
    b.id AS b_id,
    b.title AS b_title,
    b.isbn AS b_isbn,
    b.description AS b_description,
    b.price AS b_price,
    b.availability AS b_availability,
    a.id AS a_id,
    a.name AS a_name
FROM book b INNER JOIN author a ON a.id = b.author_id WHERE 1=1
EOL;
    }

    private static function serialize($row)
    {
        return [
            'id'           => $row['b_id'],
            'name'         => $row['b_title'],
            'isbn'         => $row['b_isbn'],
            'description'  => $row['b_description'],
            'price'        => $row['b_price'],
            'availability' => $row['b_availability'],
            'author'       => ['id' => $row['a_id'], 'name' => $row['a_name']],
        ];
    }
}
