<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\CompteRepository;
use App\Entity\Compte;

use App\Form\CompteType;
use App\Form\CompteType2;
use phpDocumentor\Reflection\Types\Integer;
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
        
        
     
        $form = $this->createForm(CompteType::class, $p, array('action'=>$this->generateUrl('compte_add')));
        $data['form'] = $form->createView();

        $data['comptes'] = $em->getRepository(Compte::class)->findAll();
        //$comptes = $em->getRepository(Compte::class)->findAll();
        foreach ($data['comptes'] as $compte){
            $com=$compte->getDateCreation();
            $datetime2 = new \DateTime(date("Y-m-d H:i:s")); 
        $interval = $com->diff($datetime2);
        echo $interval->format('%a');echo '-';
        
        //chaque un an le compte epargne simple est rémunérer de 5000..je teste sur un jour d'abord
        if($interval->format('%a')>=1 && $compte->getTypeCompte()->getLibelle()=='Epargne xeewel'){
            
            $soldeini= $compte->getSolde();
            echo $soldeini;die();
            $soldefinal=$soldeini+2000;
            $compte->setSolde($soldefinal);
            $em->flush();
            return $this->render('compte/liste.html.twig',$data);
        }
        //agios à retirer tous les trois mois pour les compte courant
        if($interval->format('%a')==90 && $p->getTypeCompte()->getLibelle()=='Courant'){
            
            $soldeinit= $compte->getSolde();
            $soldefina=$soldeinit-1000;
            $p->setSolde($soldefina);
            $em->flush();
        }
        
        }
        return $this->render('compte/liste.html.twig',$data);
    }

    /**
     * @Route("/Compte/add", name="compte_add")
     */
    public function add(Request $request, CompteRepository $repository)
    {
        $c = new Compte();
        $p= new Client();
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
            $c->setUser($this->getUser());
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($c);
            //$em->persist($c->getEmployeur()); soit cette ligne ou cascade={"persist"} au  niveau de l'entité
            $em->flush();
            if($c->getClient()->getUser()==null){
                $p =$em->getRepository(Client::class)->find($c->getClient()->getId());
                $p->setUser($this->getUser());
              
                $em->flush();
            }
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
        $p=new Client();
        
        $c->setClient($em->getRepository(Client::class)->find($id));

        $form = $this->createForm(CompteType2::class, $c);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $c = $form->getData();
            
            $dateCreation = new \Datetime();
            $c->setDateCreation($dateCreation);
            $solde='500';
            $c->setSolde($solde);
            //$etat1='actif';
            //$c->setEtatCompte($etat1);
            $lastid = $repository->getMaxId();//getMaxId() fonction créer dans compteRepository
            $c->setNumeroCompte($lastid);
            $c->setUser($this->getUser());
            $em->persist($c);
            
            $em->flush();
            if($c->getClient()->getUser()==null){
                $p =$em->getRepository(Client::class)->find($c->getClient()->getId());
                $p->setUser($this->getUser());
              
                $em->flush();
            }
        }
        return $this->redirectToRoute('compte');  
        
    }


    /**
     * @Route("/Compte/bloque/{id}", name="compte_bloquer")
     */
    public function bloquer($id, Request $request)
    {
        $p = new Compte();
        $em = $this->getDoctrine()->getManager();
        $p = $em->getRepository(Compte::class)->find($id);
        $etat1='bloquer';
        $p->setEtatCompte($etat1); 
            $em->flush();
        return $this->redirectToRoute('compte');
        
    }

    /**
     * @Route("/Compte/active/{id}", name="compte_activer")
     */
    public function activer($id)
    {
        $p = new Compte();
        $em = $this->getDoctrine()->getManager();
        $p = $em->getRepository(Compte::class)->find($id);
        $etat1='actif';
        $dateCrea=$p->getDateCreation();
        $datetime2 = new \DateTime(date("Y-m-d H:i:s")); 
        $interval = $dateCrea->diff($datetime2);
        echo $interval->format('%a');
        $duree=  $interval->format('%a');
        //var_dump($dateCrea);
        if($interval->format('%a')<90 ){
            $form = $this->createForm(CompteType::class, $p, array('action'=>$this->generateUrl('compte_add')));
        $data['form'] = $form->createView();

        $data['comptes'] = $em->getRepository(Compte::class)->findAll();
        $data['active_message']='Impossible d\'activer ce compte !!!! délai minimum requis 3mois. votre est bloqué depuis '.$duree.' jours';
        return $this->render('compte/liste.html.twig',$data);
          
        }else{
            $p->setEtatCompte($etat1); 
            $em->flush();
        return $this->redirectToRoute('compte');
        }
        die();
        
        
        
    }

}
