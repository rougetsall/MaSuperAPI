<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

class ProductController extends AbstractController
{

     /**
     * @Route("/products", methods={"GET"})
     */
    public function AllProduct(ProductRepository $productRepository)
    {  
        return  $this-> json($productRepository->findAll(),200,[],['groups' => 'productsall']);
        //autres Solution
        //$products = $productRepository->findAll();
        // $prodNormalises = $normalise->normalize( $products ,null,['groups' => 'productsall']);
        // $productjson = json_decode($prodNormalises);
        //$productjson =  $serialiser ->serialize( $products ,'json',['groups' => 'productsall']);
        // $response = new Response($productjson,200,["Content-type" => "application/json"]);
    }
    /**
     * @Route("/product", name="product", methods={"POST"})
     */
    public function addProduct(Request $request,SerializerInterface $serialiser)
    {
        $jsonproduct = $request->getContent();

        
        try {
            $em = $this->getDoctrine()->getManager();
            $product = $serialiser-> deserialize($jsonproduct,Product::class,'json');
           
            $em->persist($product);
            $em->flush();
            return  $this-> json($product,201,[],['groups' => 'productsall']);

        } catch (NotEncodableValueException $e) {
            return $this->json(["status"=> 400,
                                "message" => $e->getMessage()]);
        }
    }
    /**
     * @Route("/product/{id}", methods={"GET"})
     */
    public function show(Product $product)
    { 
        $product->toArray();
        return new JsonResponse($product->toArray());
    }
    

    /**
    * @param $id
    * @Route("/product/{id}", methods={"PUT"})
    */
   public function updateuser(Request $request, ProductRepository $productRepository, $id,SerializerInterface $serialiser){
 
        try{
            $em = $this->getDoctrine()->getManager();
        $product = $productRepository->find($id);

        if (!$product){
        
        return $this->json(["status"=> 400,
                            "message" => "user not found"]);
        }

        $jsonproduct = $request->getContent();
        $productquery = $serialiser-> deserialize($jsonproduct,product::class,'json');
        if (!$productquery){
        throw new \Exception();
        }

        $product->setNom($productquery->getNom());
        $product->setPhoto($productquery->getPhoto());
        $product->setDescription($productquery->getDescription());
        $product->setPrix($productquery->getPrix());
        $product->setQuantite($productquery->getQuantite());
        $em->persist($product);
        $em->flush();
        return $this->json(['status' => 200,
                        'message' => "product updated successfully"]);

        }catch (NotEncodableValueException $e) {
            return $this->json(["status"=> 400,
                                "message" => $e->getMessage()]);
        }
    }

   /**
   * @param $id
   * @Route("/product/{id}", methods={"DELETE"})
   */
    public function delete(ProductRepository $productRepository, $id){
        $product = $productRepository->find($id);
        $em = $this->getDoctrine()->getManager();
        if (!$product){
    
        return $this->json(["status"=> 404,
                                "message" => "user not found"]);
        }
    
        $em ->remove($product);
        $em->flush();
        return $this->json(["status"=> 200,
                                "message" => "user deleted successfully"]);
    }
}
