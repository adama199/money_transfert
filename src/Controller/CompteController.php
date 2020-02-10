<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Entity\Depot;
use App\Entity\Compte;
use App\Entity\Partenaire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

    /**
     * @Route("/api")
     */

class CompteController extends AbstractController
{
    private $tokenStorage;
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }
      /**
     * @Route("/newcomptep", name="creation_compte_NewPartenaire", methods={"POST"})
     * 
     */
    public function compteNew_Partenaire(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $userPasswordEncoder)
    {
    //     $a = $this->denyAccessUnlessGranted('POST', $this->getUser());
    //    // dd($a);
       
        $values = json_decode($request->getContent());
        if(isset($values->email,$values->password,$values->ninea,$values->montant) && $values->montant>=500000)
        {
            $dateCreation = new \DateTime();
            $depot = new Depot();
            $compte = new Compte();                     
            $user = new User();
            $partenaire = new Partenaire();   
            
            $partenaire->setNinea($values->ninea);
            $partenaire->setRC($values->rC);
            $entityManager->persist($user);
            // AFFECTATION DES VALEURS AUX DIFFERENTS TABLE
                    #####   USER    ######

            $roleRepo = $this->getDoctrine()->getRepository(Role::class);
            $role = $roleRepo->find($values->role);
            $user->setNom($values->nom);
            $user->setPrenom($values->prenom);
            $user->setEmail($values->email);
            $user->setPassword($userPasswordEncoder->encodePassword($user, $values->password));
            $user->setRole($role);
            $user->setPartenaire($partenaire);


            
            
           
            // $entityManager->flush();

            
            // $partenaire->setDateContrat($dateJours);
            // $partenaire->setUsers($user);

            $entityManager->persist($partenaire);
            $entityManager->flush();

            ####    GENERATION DU NUMERO DE COMPTE  ####
            $annee = Date('y');
            $cpt = $this->getLastCompte();
            $long = strlen($cpt);
            $ninea2 = substr($partenaire->getNinea() , -2);
            $NumCompte = str_pad("KH".$annee.$ninea2, 11-$long, "0").$cpt;
                    #####   COMPTE    ######
            // recuperer de l'utilisateur qui cree le compte et y effectue un depot initial
            $userCreateur = $this->tokenStorage->getToken()->getUser();
            $compte->setNumCompte($NumCompte);
            $compte->setMontant(0);
            $compte->setDateCreation($dateCreation);
            $compte->setUser($userCreateur);
            $compte->setPartenaire($partenaire);  

            $entityManager->persist($compte);
            $entityManager->flush();
                    #####   DEPOT    ######
            $depot->setDateDepot($dateCreation);
            $depot->setMontant($values->montant);
            $depot->setUser($userCreateur);
            $depot->setCompte($compte);

            $entityManager->persist($depot);
            $entityManager->flush();

            ####    MIS A JOUR DU SOLDE DE COMPTE   ####
            $NouveauSolde = ($values->montant+$compte->getMontant());
            $compte->setMontant($NouveauSolde);
            $entityManager->persist($compte);
            $entityManager->flush();

            $data = [
                'status' => 201,
                'message' => 'Le compte du partenaire est bien cree avec un depot initia de: '.$values->montant
            ];
            return new JsonResponse($data, 201);
        }
        $data = [
            'status' => 500,
            'message' => 'Vous devez renseigner un login et un passwordet un ninea pour le partenaire, le numero de compte ainsi que le montant a deposer'
        ];
        return new JsonResponse($data, 500);
    }
    
    /**
     * @Route("/compte_PartenaireExistent", name="creation_compte_PartenaireExistent", methods={"POST"})
     */
    public function compte_PartenaireExistent(Request $request, EntityManagerInterface $entityManager)
    {
        $values = json_decode($request->getContent());
        if(isset($values->ninea))
        {
            // je controle si l'utilisateur a le droit de creer un compte (appel CompteVoter)
            $this->denyAccessUnlessGranted('POST_EDIT',$this->getUser());
            $reposPartenaire = $this->getDoctrine()->getRepository(User::class);
                // recuperer de l'utilisateur proprietaire du compte
                $partenaire = $reposPartenaire->findBy($values->ninea);
            if ($partenaire) 
            {
                if ($values->montant >= 500000) 
                {
                    $dateJours = new \DateTime();
                    $depot = new Depot();
                    $compte = new Compte();
                    $partenaire = new Partenaire();
                    #####   COMPTE    ######
                
                    // recuperer de l'utilisateur qui cree le compte et y effectue un depot initial
                    $userCreateur = $this->tokenStorage->getToken()->getUser();

                    ####    GENERATION DU NUMERO DE COMPTE  ####
                    $annee = Date('y');
                    $cpt = $this->getLastCompte();
                    $long = strlen($cpt);
                    $ninea2 = substr($partenaire->getNinea(), -2);
                    $numCompte = str_pad("AD".$annee.$ninea2, 11-$long, "0").$cpt;
                    $compte->setNumCompte($numCompte);
                    $compte->setMontant($values->montant);
                    $compte->setDateCreation($dateJours);
                    $compte->setUser($userCreateur);
                    $compte->setPartenaire($partenaire);

                    $entityManager->persist($compte);
                    // $entityManager->flush();
                    #####   DEPOT    ######
                    $ReposCompte = $this->getDoctrine()->getRepository(Compte::class);
                    $compteDepos = $ReposCompte->findOneBy(['numCompte' => $numCompte]);
                    $depot->setDateDepot($dateJours);
                    $depot->setMontant($values->montant);
                    $depot->setUser($userCreateur);
                    $depot->setCompte($compteDepos);

                    $entityManager->persist($depot);
                    $entityManager->flush();
                     ####    MIS A JOUR DU SOLDE DE COMPTE   ####
                    $NouveauSolde = ($values->montant+$compte->getMontant());
                    $compte->setMontant($NouveauSolde);
                    $entityManager->persist($compte);
                    $entityManager->flush();

                    $data = [
                        'status' => 201,
                        'message' => 'Le compte du partenaire est bien cree avec un depot initia de: '.$values->montant
                        ];
                    return new JsonResponse($data, 201);
                }
                $data = [
                    'status' => 500,
                    'message' => 'Veuillez saisir un montant de depot valide'
                    ];
                    return new JsonResponse($data, 500);
            }
            $data = [
                'status' => 500,
                'message' => 'Desole le NINEA saisie n est ratache a aucun partenaire'
                ];
                return new JsonResponse($data, 500);
        }
        $data = [
            'status' => 500,
            'message' => 'Vous devez renseigner le ninea du partenaire, le numero de compte ainsi que le montant a deposer'
            ];
            return new JsonResponse($data, 500);
    }    

    public function getLastCompte(){
        $ripo = $this->getDoctrine()->getRepository(Compte::class);
        $compte = $ripo->findBy([], ['id'=>'DESC']);
        if(!$compte){
            $cpt = 1;
        }else{
            $cpt = ($compte[0]->getId()+1);
        }
        return $cpt;
      }

}