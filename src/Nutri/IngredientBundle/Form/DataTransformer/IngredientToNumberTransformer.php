<?php

namespace Nutri\IngredientBundle\Form\DataTransformer;

use Nutri\IngredientBundle\Entity\Ingredient;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class IngredientToNumberTransformer implements DataTransformerInterface
{
    private $entityRepository;

    public function __construct(\Doctrine\ORM\EntityRepository $entityRepository)
    {
        $this->entityRepository = $entityRepository;
    }

    /**
     * Transforms an object (ingredient) to a string (number).
     *
     * @param  Issue|null $ingredient
     * @return string
     */
    public function transform($ingredient)
    {
        dump($ingredient);
        if (null === $ingredient) {
            return null;
        }

        return $ingredient->getId();
    }

    /**
     * Transforms a string (number) to an object (ingredient).
     *
     * @param  string $ingredientNumber
     * @return Issue|null
     * @throws TransformationFailedException if object (ingredient) is not found.
     */
    public function reverseTransform($ingredientNumber)
    {
        dump($ingredientNumber);
        // no ingredient number? It's optional, so that's ok
        if (!$ingredientNumber) {
            throw new Exception('Error: empty !!!');
            return;
        }

        $ingredient = $this->entityRepository->find($ingredientNumber);
        
        dump($ingredient);
        if (null === $ingredient) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'An ingredient with number "%s" does not exist!',
                $ingredientNumber
            ));
        }

        return $ingredient;
    }
}