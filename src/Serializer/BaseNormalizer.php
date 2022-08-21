<?php

namespace App\Serializer;

use ApiPlatform\Core\Api\IriConverterInterface;
use App\Entity\BaseEntity;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class BaseNormalizer implements NormalizerInterface
{
    public function __construct(private readonly NormalizerInterface $decorated, private readonly IriConverterInterface $iriConverter)
    {
    }

    public function supportsNormalization(mixed $data, string $format = null): bool
    {
        return $data instanceof BaseEntity && $this->decorated->supportsNormalization(...func_get_args());
    }

    /**
     * @param BaseEntity $object
     */
    public function normalize(mixed $object, string $format = null, array $context = [])
    {
        $context[AbstractObjectNormalizer::SKIP_NULL_VALUES] = false;

        /**
         * @var string[] $ignoredAttributes
         */
        $ignoredAttributes = $context[AbstractObjectNormalizer::IGNORED_ATTRIBUTES] ?? [];
        $context[AbstractObjectNormalizer::IGNORED_ATTRIBUTES] = array_merge(
            $ignoredAttributes,
            [
                'deleted',
                'deletedAt',
            ]
        );

        $data = $this->decorated->normalize($object, $format, $context);

        if (is_string($data)) {
            return $data;
        }

        if (is_array($data)) {
            $dateTimeNormalizer = new DateTimeNormalizer();

            return array_merge(
                [
                    'id' => $object->getId(),
                    'iri' => $this->iriConverter->getIriFromItem($object),
                ],
                $data,
                [
                    'createdAt' => $dateTimeNormalizer->normalize($object->getCreatedAt()),
                    'updatedAt' => $dateTimeNormalizer->normalize($object->getUpdatedAt()),
                ]
            );
        }

        return $data;
    }
}
