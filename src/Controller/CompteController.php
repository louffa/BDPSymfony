<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\CompteRepository;
use App\Entity\Compte;

use App\Form\CompteType;
use App\Form\CompteType2;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CompteController extends AbstractController
{
    /**
     * @Route("/Compte/liste", name="compte")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $p=new Compte();
        //chaque un an le compte epargne simple est rémunérer de 5000
        /*$t=new \Datetime();
        $datenow =$t->format('Y');
        $g=$p->getDateCreation()->format('Y'); 
        //var_dump($g);
        //echo $g;
        $a=$g+1;
        echo $a-$datenow;
        if($a==$datenow && $p->getTypeCompte()->getLibelle()=='epargne simple'){
           $solde= $p->getSolde()+5000;
           $p->setSolde($solde);
           $em->flush();

        }*/
        $t=new \Datetime();
    
        $g=$p->getDateCreation(); 
        $d=date_diff($g,$t);
        $datediff= $d->format("%a");
        echo $datediff;//je teste sur un jour d'abord
        if($datediff==1 && $p->getTypeCompte()->getLibelle()=='epargne simple'){
            
            $solde= $p->getSolde()+5000;
            $p->setSolde($solde);
            $em->flush();
        }
        
     
        $form = $this->createForm(CompteType::class, $p, array('action'=>$this->generateUrl('compte_add')));
        $data['form'] = $form->createView();

        $data['comptes'] = $em->getRepository(Compte::class)->findAll();
        
        return $this->render('compte/liste.html.twig',$data);
    }

    /**
     * @Route("/Compte/add", name="compte_add")
     */
    public function add(Request $request, CompteRepository $repository)
    {
        $c = new Compte();
        $form = $this->createForm(CompteType::class, $c);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $c = $form->getData();
            
            $dateCreation = new \Datetime();
            $c->setDateCreation($dateCreation);
            $solde='500';
            $c->setSolde($solde);
            $etat1='actif';
            $c->setEtatCompte($etat1);
            $lastid = $repository->getMaxId();//getMaxId() fonction créer dans compteRepository 
            $c->setNumeroCompte($lastid);
            //$c->setUser($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($c);
            //$em->persist($c->getEmployeur()); soit cette ligne ou cascade={"persist"} au  niveau de l'entité
            $em->flush();
        }
      
        return $this->redirectToRoute('compte');
    }

    /**
     * @Route("/Compte/delete/{id}", name="compte_delete")
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $compte = $em->getRepository(Compte::class)->find($id);
        if ($compte != null)
        {
            $em->remove($compte);
            $em->flush();
        }
        return $this->redirectToRoute('compte');
         
    }
    /**
     * @Route("/Compte/edit/{id}", name="compte_edit")
     */
    public function edit($id)
    {
        $em = $this->getDoctrine()->getManager();
        $p = $em->getRepository(Compte::class)->find($id);

        $form = $this->createForm(CompteType2::class, $p, array('action'=>$this->generateUrl('compte_update', ['id' => $id])));
        $data['form'] = $form->createView();

        //$data['comptes'] = $em->getRepository(Compte::class)->findAll();
        return $this->render('compte/modifier.html.twig',$data);
        
        
    }

    /**
     * @Route("/Compte/update/{id}", name="compte_update")
     */
    public function update($id, Request $request)
    {
        $p = new Compte();
       
        $form = $this->createForm(CompteType2::class, $p);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $p = $form->getData();
  
            $em = $this->getDoctrine()->getManager();
            $compte = $em->getRepository(Compte::class)->find($id);
            $compte->setTypeCompte($p->getTypeCompte());
            $compte->setEtatCompte($p->getEtatCompte());
            
            $em->flush();
        }
      
        return $this->redirectToRoute('compte');
    }
    /**
     * @Route("/Compte/new/{id}", name="client_AjoutCompte")
     */
    public function new($id)
    {
        $em = $this->getDoctrine()->getManager();
        //$p = $em->getRepository(Compte::class)->find($id);
        $p = new Compte();
       
        $form = $this->createForm(CompteType2::class,$p, array('action'=>$this->generateUrl('client_newCompte', ['id' => $id])));
        $data['form'] = $form->createView();

        //$data['comptes'] = $em->getRepository(Compte::class)->findAll();
        return $this->render('compte/nouveau.html.twig',$data);
        
        
    }

    /**
     * @Route("/Compte/AjoutCompte/{id}", name="client_newCompte")
     */
    public function ajoutCompte(Request $request,$id,CompteRepository $repository)
    {
        $em = $this->getDoctrine()->getManager();
        $c = new Compte();
       // $p=new Client();
        
        $c->setClient($em->getRepository(Client::class)->find($id));

        $form = $this->createForm(CompteType2::class, $c);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $c = $form->getData();
            
            $dateCreation = new \Datetime();
            $c->setDateCreation($dateCreation);
            $solde='500';
            $c->setSolde($solde);
            $etat1='actif';
            $c->setEtatCompte($etat1);
            $lastid = $repository->getMaxId();//getMaxId() fonction créer dans compteRepository
            $c->setNumeroCompte($lastid);
            
            $em->persist($c);
            
            $em->flush();
        }
        return $this->redirectToRoute('compte');  
        
    }

}
