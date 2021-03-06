<?php

declare(strict_types=1);

namespace App\Swagger;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class SwaggerDecorator implements NormalizerInterface
{
    /**
     * @var NormalizerInterface
     */
    private $decorated;

    public function __construct(NormalizerInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function supportsNormalization($data, string $format = null): bool
    {
        return $this->decorated->supportsNormalization($data, $format);
    }

    public function normalize($object, string $format = null, array $context = [])
    {
        $docs = $this->decorated->normalize($object, $format, $context);

        $docs['components']['schemas']['Token'] = [
            'type' => 'object',
            'properties' => [
                'token' => ['type' => 'string', 'readOnly' => true],
            ],
        ];

        $docs['components']['schemas']['Credentials'] = [
            'type' => 'object',
            'properties' => [
                'email'    => ['type' => 'string', 'example' => 'mario.rossi@fixture.it'],
                'password' => ['type' => 'string', 'example' => 'mario123'],
            ],
        ];

        $docs['components']['schemas']['Registration'] = [
            'type' => 'object',
            'properties' => [
                'name'     => ['type' => 'string', 'example' => 'Mario'],
                'surname'  => ['type' => 'string', 'example' => 'Rossi'],
                'email'    => ['type' => 'string', 'example' => 'mario.rossi@example.com'],
                'password' => ['type' => 'string', 'example' => 'mario123'],
            ],
        ];

        $tokenDocumentation = [
            'paths' => [
                '/authentication_token' => [
                    'post' => [
                        'tags' => ['Token'],
                        'operationId' => 'postCredentialsItem',
                        'summary' => 'Get JWT token to login.',
                        'requestBody' => [
                            'description' => 'Create new JWT Token',
                            'content' => [
                                'application/json' => [
                                    'schema' => ['$ref' => '#/components/schemas/Credentials'],
                                ],
                            ],
                        ],
                        'responses' => [
                            Response::HTTP_OK => [
                                'description' => 'Get JWT token',
                                'content' => [
                                    'application/json' => [
                                        'schema' => ['$ref' => '#/components/schemas/Token'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],


                '/api/registration/new-user' => [
                    'post' => [
                        'tags' => ['Registration'],
                        'operationId' => 'postRegistrationUser',
                        'summary' => 'Registration new user.',
                        'requestBody' => [
                            'description' => 'Create new user',
                            'content' => [
                                'application/json' => [
                                    'schema' => ['$ref' => '#/components/schemas/Registration'],
                                ],
                            ],
                        ],
                        'responses' => [
                            Response::HTTP_OK => [
                                'description' => 'New registered user',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [/*'$ref' => '#/components/schemas/Token',*/],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        return array_merge_recursive($docs, $tokenDocumentation);
    }
}
