<?php

namespace AppBundle\Serializer;

use AppBundle\Entity\Genre;
use JMS\Serializer\Context;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\JsonDeserializationVisitor;
use JMS\Serializer\Metadata\ClassMetadata;
use JMS\Serializer\Metadata\PropertyMetadata;
use JMS\Serializer\Serializer;
use JMS\Serializer\VisitorInterface;

class CustomHandler
{

    public function deserializeGenre(
        JsonDeserializationVisitor $visitor,
        array $data,
        array $type,
        DeserializationContext $context
    ) {

        $action = function (array $data) {

            return $data;
        };

        return $this->deserialize($action, $visitor, $data, $type, $context);
    }

    public function serializeGenre(VisitorInterface $visitor, Genre $data, array $type, Context $context)
    {
        $action = function (array $data) {

            return $data;
        };

        return $this->serialize($action, $visitor, $data, $type, $context);
    }

    public function deserialize(
        callable $action,
        VisitorInterface $visitor,
        array $data,
        array $type,
        DeserializationContext $context
    ) {

        /**
         * Enhance data before the serialization.
         */
        $data = $action($data);

        /**
         * @var ClassMetadata $classMetadata
         * @var PropertyMetadata $metadata
         */
        $classMetadata = $context->getMetadataFactory()->getMetadataForClass($type['name']);

        $visitor->startVisitingObject($classMetadata, new $type['name'](), $type, $context);
        foreach ($classMetadata->propertyMetadata as $metadata) {
            $visitor->visitProperty($metadata, $data, $context);
        }
        $deserialized = $visitor->endVisitingObject($classMetadata, $data, $type, $context);

        return $deserialized;
    }

    public function serialize(callable $action, VisitorInterface $visitor, $data, array $type, Context $context)
    {
        /**
         * @var ClassMetadata $classMetadata
         * @var PropertyMetadata $metadata
         */
        $classMetadata = $context->getMetadataFactory()->getMetadataForClass($type['name']);

        $visitor->startVisitingObject($classMetadata, $data, $type, $context);
        foreach ($classMetadata->propertyMetadata as $metadata) {
            $visitor->visitProperty($metadata, $data, $context);
        }
        $serialized = $visitor->endVisitingObject($classMetadata, $data, $type, $context);

        /**
         * Enhance $serialized data array.
         */
        return $action($serialized);
    }
}