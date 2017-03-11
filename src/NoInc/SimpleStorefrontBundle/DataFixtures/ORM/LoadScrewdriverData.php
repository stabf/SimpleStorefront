<?php

namespace NoInc\SimpleStorefrontBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use NoInc\SimpleStorefrontBundle\Entity\User;
use NoInc\SimpleStorefrontBundle\Entity\Ingredient;
use NoInc\SimpleStorefrontBundle\Entity\Recipe;
use NoInc\SimpleStorefrontBundle\Entity\RecipeIngredient;

class LoadScrewdriverData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $ingredients = [];
        foreach ( $this->ingredientArray() as $ingredientData )
        {
            $ingredient = new Ingredient();
            
            $ingredient->setName($ingredientData["name"]);
            $ingredient->setPrice($ingredientData["price"]);
            $ingredient->setMeasure($ingredientData["measure"]);
            $ingredient->setStock(100);
    
            $manager->persist($ingredient);
        
            $ingredients[$ingredient->getName()] = $ingredient;
        }
        $manager->flush();
        
        $recipeData = $this->recipeArray();
        
        $recipe = new Recipe();
        $recipe->setName($recipeData["name"]);
        $recipe->setPrice($recipeData["price"]);
        $manager->persist($recipe);
        $manager->flush();
        
        foreach( $recipeData["ingredients"] as $recipeIngredientData )
        {
            $recipeIngredient = new RecipeIngredient();
            
            $recipeIngredient->setIngredient($ingredients[$recipeIngredientData["name"]]);
            $recipeIngredient->setRecipe($recipe);
            $recipeIngredient->setQuantity($recipeIngredientData["quantity"]);
            $manager->persist($recipeIngredient);
        }
        $manager->flush();
    }
    
    public function ingredientArray()
    {
        return [
            [
                "name" => "Orange Juice",
                "price" => 0.20,
                "measure" => "Cup"
            ],
            [
                "name" => "Vodka",
                "price" => 1.00,
                "measure" => "Ounce"
            ]
        ];
    }
    
    public function recipeArray()
    {
        return [
            "name" => "Screwdriver",
            "price" => 3.00,
            "ingredients" => [
                [
                    "name" => "Orange Juice",
                    "quantity" => 2
                ],
                [
                    "name" => "Vodka",
                    "quantity" => 1
                ],
            ]
        ];
    }
    
    public function getOrder()
    {
        return 2;
    }
}