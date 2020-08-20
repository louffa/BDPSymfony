<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Employeur;
use App\Form\ClientType;
use App\Form\ClientType2;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    /**
     * @Route("/client/liste", name="client")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $p=new Client;
        $form = $this->createForm(ClientType::class, $p, array('action'=>$this->generateUrl('client_add')));
        $data['form'] = $form->createView();

        $data['clients'] = $em->getRepository(Client::class)->findAll();
        
        return $this->render('client/liste.html.twig',$data);
    }

    /**
     * @Route("/Client/add", name="client_add")
     */
    public function add(Request $request)
    {
        
        $c = new client();
        $form = $this->createForm(ClientType::class, $c);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $c = $form->getData();
            //$c->setUser($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($c);
            //$em->persist($c->getEmployeur()); soit cette ligne ou cascade={"persist"} au  niveau de l'entité
            $em->flush();
        }
      
        return $this->redirectToRoute('client');
    }

    /**
     * @Route("/client/delete/{id}", name="client_delete")
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $client = $em->getRepository(Client::class)->find($id);
        if ($client != null)
        {
            $em->remove($client);
            $em->flush();
        }
        return $this->redirectToRoute('client');
         
    }

    /**
     * @Route("/client/edit/{id}", name="client_edit")
     */
    public function edit($id)
    {
        $em = $this->getDoctrine()->getManager();
        $p = $em->getRepository(Client::class)->find($id);

        $form = $this->createForm(ClientType2::class, $p, array('action'=>$this->generateUrl('client_update', ['id' => $id])));
        $data['form'] = $form->createView();

        $data['produits'] = $em->getRepository(Client::class)->findAll();
        return $this->render('client/modif.html.twig',$data);
        
    
        
    }
     /**
     * @Route("/Client/update/{id}", name="client_update")
     */
    public function update($id, Request $request)
    {
        
        $p = new Client();
        $form = $this->createForm(ClientType2::class, $p);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $p = $form->getData();
            //$p->setUser($this->getUser()); 
            //$p->setId($id);
            $em = $this->getDoctrine()->getManager();
            $client = $em->getRepository(Client::class)->find($id);
            $client->setCni($p->getCni());
            $client->setNom($p->getNom());
            $client->setPrenom($p->getPrenom());
            $client->setEmail($p->getEmail());
            $client->setTelephone($p->getTelephone());
            $client->setSalaire($p->getSalaire());
            $client->setProfession($p->getProfession());
            $client->setEmployeur($p->getEmployeur());
            $em->flush();
        }
      
        return $this->redirectToRoute('client');
    }

   

}
