<?php

namespace NoInc\SimpleStorefrontBundle\Controller;

use NoInc\SimpleStorefrontBundle\Entity\Recipe;
use NoInc\SimpleStorefrontBundle\Entity\RecipeIngredient;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use NoInc\SimpleStorefrontBundle\Entity\Ingredient;
use NoInc\SimpleStorefrontBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Security("has_role('ROLE_ADMIN')")
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     * @Route("/", name="admin_home")
     * @Method("GET")
     */
    public function getAction()
    {
        $recipes = $this->getDoctrine()->getRepository('NoIncSimpleStorefrontBundle:Recipe')->getRecipesAndIngredients();
        
        $renderData = [];
        
        $renderData['title'] = 'A Simple Storefront';
        $renderData['recipes'] = $recipes;
            
        return $this->render('NoIncSimpleStorefrontBundle:Default:admin.html.twig', $renderData);
    }
    
    /**
     * @Route("/make/{recipe_id}", name="make_recipe")
     * @Method("POST")
     * @ParamConverter("recipe", class="NoIncSimpleStorefrontBundle:Recipe", options={"mapping": {"recipe_id": "id"}})
     */
    public function postMakeRecipeAction(Recipe $recipe)
    {
        $recipeingredients = [];
        $haveEnoughIngredients = true;
        /*foreach ingredient in recipie, decrement ingredient stock by quantity*/
        $recipeingredients = $recipe->getRecipeIngredients();
        foreach($recipeingredients as $recipeingredient)
        {
            $ingredient = $this->getDoctrine()->getRepository('NoInc\SimpleStorefrontBundle\Entity\Ingredient')->find($recipeingredient->getIngredientId());
            $stock = $ingredient->getStock();
            if($stock - $recipeingredient->getQuantity() < 0)
            {
                $haveEnoughIngredients = false;
            }
        }
        if($haveEnoughIngredients == true)
        {
            foreach($recipeingredients as $recipeingredient)
            {
                $ingredient = $this->getDoctrine()->getRepository('NoInc\SimpleStorefrontBundle\Entity\Ingredient')->find($recipeingredient->getIngredientId());
                $stock = $ingredient->getStock();
                $stock = $stock - $recipeingredient->getQuantity();
                $ingredient->setStock($stock);
                $this->getDoctrine()->getEntityManager()->flush();
            }
            
            $product = new Product();
            $product->setCreatedAt(time());
            $product->setRecipe($recipe);
            $this->getDoctrine()->getEntityManager()->persist($product);
            $this->getDoctrine()->getEntityManager()->flush();
        }
        
        
        return $this->redirectToRoute('admin_home');
    }
    
    /**
     * @Route("/buy/{ingredient_id}", name="buy_ingredient")
     * @Method("POST")
     * @ParamConverter("ingredient", class="NoIncSimpleStorefrontBundle:Ingredient", options={"mapping": {"ingredient_id": "id"}})
     */
    public function postBuyIngredientAction(Ingredient $ingredient)
    {
            
        return $this->redirectToRoute('admin_home');
    }
    
}
