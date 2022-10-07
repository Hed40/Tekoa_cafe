<?php
 
namespace App\Classe;
 
use Symfony\Component\HttpFoundation\RequestStack;
use App\Entity\Products;
use Doctrine\ORM\EntityManagerInterface;

class Cart
{
    private $stack;
    private $entityManager;
 
    public function __construct(EntityManagerInterface $entityManager, RequestStack $stack)
 
    {
        return $this->stack = $stack;
        return $this->entityManager = $entityManager;
    }
 
    public function add($id)
    {
 
        $session = $this->stack->getSession();
        $cart = $session->get('cart', []);
 
        if(!empty($cart[$id])){
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
 
 
        $session->set('cart', $cart);
    }
 
    public function get()

    {
        $methodget = $this->stack->getSession();
        return $methodget->get('cart');
    }
 
    public function remove()
    {
        $methodremove = $this->stack->getSession();
        return $methodremove->remove('cart');
    }

    public function delete($id)
    {
        $session = $this->stack->getSession();
        $cart = $session->get('cart', []);
 
        unset($cart[$id]);
 
        $session->set('cart', $cart);

        
    }

    public function decrease($id)
    {
        $session = $this->stack->getSession();
        $cart = $session->get('cart', []);

        if($cart[$id] > 0){
            $cart[$id] --;
            //retire la quantité (-1)
        } else {
            unset($cart[$id]);
            //supprimer le produit
        }
        $session->set('cart', $cart);
    }

    public function getFull(){

        $cartComplete = [];

        if($this->get()){
            foreach ($this->get() as $id => $quantity){
                
                $productObject = $this->entityManager->getRepository(Products::class)->finOneById($id);

                if (!$productObject){
                $this->delete($id);
                continue;
                }

                $cartComplete[] = [
                    'product' => $productObject,
                    'quantity'=> $quantity

                    ];
            }
        }
        return $cartComplete;
    }
}