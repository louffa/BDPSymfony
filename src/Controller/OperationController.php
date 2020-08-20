<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Entity\Operation;
use App\Form\OperationType;
use App\Repository\OperationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OperationController extends AbstractController
{
    /**
     * @Route("/operation", name="operation")
     */
    public function index()
    {
        return $this->render('operation/index.html.twig');
    }
    /**
     * @Route("/Operation/edit/{id}", name="operation_edit")
     */
    public function edit($id,OperationRepository $repository)
    {
        $em = $this->getDoctrine()->getManager();
        $p=new Operation();
        
        $form = $this->createForm(OperationType::class, $p, array('action'=>$this->generateUrl('operation_add', ['id' => $id])));//ajouter id en parametre de operation_add se referer a ajoutCompte() ds compte controller
        $data['form'] = $form->createView();

        $data['compte'] = $em->getRepository(Compte::class)->find($id);
        //ok ligne35 $data['operations'] = $em->getRepository(Operation::class)->findAll($id);//pour le moment récupere tte les operations or doit recuperer operation compte correspondant
        $data['operations'] = $em->getRepository(Operation::class)->findBy(['compte'=>$id]);
        
        return $this->render('operation/liste.html.twig',$data);
        
    }

    /**
     * @Route("/Operation/add/{id}", name="operation_add")
     */
    public function add(Request $request,$id,OperationRepository $repository)
    {
        $em = $this->getDoctrine()->getManager();
        $c = new Operation();
        $form = $this->createForm(OperationType::class, $c);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $c = $form->getData();
            $c->setCompte($em->getRepository(Compte::class)->find($id));
            $dateOperation = new \Datetime();
            $c->setDateOperation($dateOperation);
            
            $lastid = $repository->getMaxId();//getMaxId() fonction créer dans OperationRepository
            $c->setNumeroOperation($lastid);
            //$c->setUser($this->getUser());
            
            $em->persist($c);
            //$em->persist($c->getEmployeur()); soit cette ligne ou cascade={"persist"} au  niveau de l'entité
            $em->flush();
             //update solde compte apres une operation de depot
             if ($c->getTypeOperation()=='depot'){
                $p =$em->getRepository(Compte::class)->find($c->getCompte()->getId());
                $solde = $p->getSolde() + $c->getMontant();
                $p->setSolde($solde);
                $em->flush();
                $s = new Operation();
                        $form = $this->createForm(OperationType::class, $s, array('action'=>$this->generateUrl('operation_add', ['id' => $id])));
                        $data['form'] = $form->createView();       
                    
                        $data['compte'] = $em->getRepository(Compte::class)->find($id);
                $data['success_message'] = 'Operation de depot éffectuée avec succès';
                $data['operations'] = $em->getRepository(Operation::class)->findBy(['compte'=>$id]);
                return $this->render('operation/liste.html.twig',$data);
                
            }
            elseif($c->getTypeOperation()=='retrait'){
                       
                $soldeC = $c->getMontant();
                $p =$em->getRepository(Compte::class)->find($c->getCompte()->getId());
                    if ($p->getSolde() < $c->getMontant()){
                        $s = new Operation();
                        $form = $this->createForm(OperationType::class, $s, array('action'=>$this->generateUrl('operation_add', ['id' => $id])));
                        $data['form'] = $form->createView();       
                    
                        $data['compte'] = $em->getRepository(Compte::class)->find($id);
                        $data['error_message'] = 'Le solde de votre compte est inférieur à '.$soldeC;
                        $data['operations'] = $em->getRepository(Operation::class)->findBy(['compte'=>$id]);
                        return $this->render('operation/liste.html.twig',$data);
                    }else{
                
                        $solde = $p->getSolde() - $c->getMontant();
                        $p->setSolde($solde);
                        $em->flush();
                            $s = new Operation();
                            $form = $this->createForm(OperationType::class, $s, array('action'=>$this->generateUrl('operation_add', ['id' => $id])));
                            $data['form'] = $form->createView();       
                        
                            $data['compte'] = $em->getRepository(Compte::class)->find($id);
                            $data['reatrait_message'] = 'Operation de retrait éffectuée avec succès';
                            $data['operations'] = $em->getRepository(Operation::class)->findBy(['compte'=>$id]);
                            return $this->render('operation/liste.html.twig',$data);
                        }
                        
            }
        }
     
        //return $this->redirectToRoute('operation_edit', ['id' => $id]);
    }
}
