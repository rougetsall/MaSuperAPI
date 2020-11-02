<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{

    public function register(Request $request, UserPasswordEncoderInterface $encoder)
    {
     try{
            $em = $this->getDoctrine()->getManager();

            $email = $request->request->get('email');
            $nom = $request->request->get('nom');
            $prenom = $request->request->get('prenom');
            $password = $request->request->get('password');
        

            $user = new User();
            $user->setEmail($email);
            $user->setNom($nom);
            $user->setPrenom($prenom);
            $user->setPassword($encoder->encodePassword($user, $password));
        
            $em->persist($user);
            $em->flush();
            return  $this-> json($user,201,[]);
        } catch (NotEncodableValueException $e) {
            return $this->json(["status"=> 400,
                                "message" => $e->getMessage()]);
        }
    }
    
    /**
     * @Route("/user/{id}", methods={"GET"})
     */
    public function show(User $user)
        { try {

            $user->toArray();
            return new JsonResponse($user->toArray());
        } catch (NotFoundHttpException $e) {
            return $this->json(["status"=> 400,
                                "message" => $e->getMessage()]);
        }
       
    }
    /**
    * @param $id
    * @Route("/user/{id}", methods={"PUT"})
    */
   public function updateuser(Request $request, UserRepository $userRepository, $id,UserPasswordEncoderInterface $encoder,SerializerInterface $serialiser){
 
        try{
            $em = $this->getDoctrine()->getManager();
        $user = $userRepository->find($id);
    
        if (!$user){
        
        return $this->json(["status"=> 400,
                            "message" => "user not found"]);
        }
    
        $jsonuser = $request->getContent();
        $userquery = $serialiser-> deserialize($jsonuser,User::class,'json');
        if (!$userquery){
        throw new \Exception();
        }
    
        $user->setNom($userquery->getNom());
        $user->setPrenom($userquery->getPrenom());
        $user->setEmail($userquery->getEmail());
        $user->setPassword(($encoder->encodePassword($user,$userquery->getPassword())));
        $em->persist($user);
        $em->flush();
        return $this->json(['status' => 200,
                        'message' => "user updated successfully"]);
    
        }catch (NotEncodableValueException $e) {
            return $this->json(["status"=> 400,
                                "message" => $e->getMessage()]);
        }
   }
 /**
   * @param $id
   * @Route("/user/{id}", methods={"DELETE"})
   */
  public function delete(UserRepository $userRepository, $id){
    $users = $userRepository->find($id);
    $em = $this->getDoctrine()->getManager();
    if (!$users){
   
     return $this->json(["status"=> 404,
                            "message" => "user not found"]);
    }
   
    $em ->remove($users);
    $em->flush();
    return $this->json(["status"=> 200,
                            "message" => "user deleted successfully"]);
   }
   
}
