<?php

namespace App\Controller;

use App\Entity\Product;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homePage")
     */
    public function index(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager->getRepository(Product::class)->findAll();

        return $this->render('main/default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    /**
     * @Route("/product-add", name="product-add")
     */
    public function productAdd(): Response
    {
      $product = new Product();
      $product->setTitle('Product' . round(1,10));
      $product->setDescription('Some');
      $product->setPrice('1060');
      $product->setQuantity(1);

     $entityManager = $this->getDoctrine()->getManager();
     $entityManager->persist($product);
     $entityManager->flush();

     return $this->redirectToRoute('homePage');
    }

    /**
     * @Route("/edit-product/{id}", methods="GET|POST", name="product-edit", requirements={"id"="\d+"})
     * @Route("/add-product", methods="GET|POST", name="add_product")
     */
    public function productEdit(Request $request, int $id = null): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
       if ($id){
           $product = $entityManager->getRepository(Product::class)->find($id);
       }else{
           $product = new Product();
       }
       $form = $this->createFormBuilder($product)->add('title', TextType::class)->getForm();

        return $this->render('main/default/edit_product.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
