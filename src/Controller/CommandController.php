<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Command;
use App\Repository\UserRepository;
use App\Repository\CommandRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandController extends AbstractController
{

     /**
     * @Route("/commands", methods={"GET"})
     */
    public function AllCommand(CommandRepository $commandRepository)
     {  
        return  $this-> json($commandRepository->findAll(),200,[],['groups' => 'commands']);
    }


    /**
     * @Route("/command", methods={"POST"})
     */
    public function AddCommand(Request $request ,ProductRepository $productRepository ,UserRepository $userRepository)
    {  
        $jsoncommand = $request->getContent();
        $com=json_decode($jsoncommand, true);
        //dd($com);
        //dd($com["product"][1]);
        $user_id = $com["user_id"]["email"];
        $users = new User();
        $user = $userRepository->findBy(
            ["email"=>$user_id]);
            //$users = $serialiser-> deserialize($user,User::class,'json');

        //dd($user[0]->getId());
        $command = new Command();
        $command->setAdresseLivrais($com["adresse_livrais"]);
        $command->setZipcode($com["zipcode"]);
        $command->setVille($com["ville"]);
        $command->setPrixTotal($com["prix_total"]);
        $command->setCommandAt(new DateTime);
        $command->setStatus($com["status"]);
        $command->setUserId($user[0]);
    
        for ($i=0; $i < count($com["product"]); $i++) { 
            $pro_id = $com["product"][$i]["nom"];
            
            $product= $productRepository->findBy(
                ["nom"=>$pro_id]
            );
            $command->addProduct( $product[0]);
        }
       
            $em = $this->getDoctrine()->getManager();
           
            $em->persist($command);
            $em->flush();
            return $this->json(['status' => 200,
                        'message' => "commands successfully"]);
    }


    
}
