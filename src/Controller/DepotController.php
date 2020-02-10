<?php

namespace App\Controller;


use App\Entity\User;
use App\Entity\Depot;
use App\Entity\Compte;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
* @Route("/api")
*/
class DepotController extends AbstractController
{
    private $tokenStorage;
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }
    /**
    *@Route("/fairedepot", name="faire_depot", methods={"POST"})
    */
    public function faire_depot(Request $request, EntityManagerInterface $entityManager)
    {
        
        $values = json_decode($request->getContent());
        if(isset($values->id))
        // dump($values->id);die();
        {
            $userCreateur = $this->tokenStorage->getToken()->getUser();

            // dump($userCreateur->getComptes()[0]); die;
             // je controle si l'utilisateur a le droit de creer un compte (appel CompteVoter)
             //$this->denyAccessUnlessGranted('POST_EDIT',$this->getUser());
             $reposCompte = $this->getDoctrine()->getRepository(Compte::class);
                 // recuperer de l'utilisateur proprietaire du compte
                 $comptedepot = $reposCompte->find($values->id);
                //  dump($values->id);die();
                 if($comptedepot){
            $dateJours = new \DateTime();
            $depot = new Depot();
            $compte = new Compte();                     
            $user = new User();

            $userCreateur = $this->tokenStorage->getToken()->getUser();
            
            #####   DEPOT    ######
           
            $depot->setDateDepot($dateJours);
            $depot->setMontant($values->montant);
            $depot->setUser($userCreateur);
            $depot->setCompte($comptedepot);

            $entityManager->persist($depot);
            $entityManager->flush();
            ####    MIS A JOUR DU SOLDE DE COMPTE   ####
            $NouveauSolde = ($values->montant+$comptedepot->getMontant());
            $comptedepot->setMontant($NouveauSolde);
            $entityManager->persist($comptedepot);
            $entityManager->flush();

            $data = [
                'status' => 201,
                'message' => 'Depot fait avec succes.Votre compte est maintenant égae à '
                ];
            return new JsonResponse($data, 201);
        }
        $data = [
            'status' => 500,
            'message' => 'Depot non enregistrer'
            ];
            return new JsonResponse($data, 500);
    
        }
    }
}