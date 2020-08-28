<?php

namespace App\Controller;

use App\Entity\Agence;
use App\Entity\User;
use App\Form\AgencesType;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UtilisateurController extends AbstractController
{
    /**
     * @Route("/utilisateur", name="utilisateur")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $u=new User();
        $a=new Agence();
        $form = $this->createForm(UserType::class, $u, array('action'=>$this->generateUrl('user_add')));
        $data['form'] = $form->createView();
        
        $forms = $this->createForm(AgencesType::class, $a, array('action'=>$this->generateUrl('agence_add')));
        $data['forms'] = $forms->createView();

        $data['users'] = $em->getRepository(User::class)->findAll();
        return $this->render('utilisateur/index.html.twig',$data);
    }

    /**
     * @Route("/utilisateur/add", name="user_add")
     */
    public function add(Request $request)
    {
        $c = new User();
        $form = $this->createForm(UserType::class, $c);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $c = $form->getData();
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($c);
            //$em->persist($c->getEmployeur()); soit cette ligne ou cascade={"persist"} au  niveau de l'entité
            $em->flush();
        }
        return $this->redirectToRoute('utilisateur');
    }

    /**
     * @Route("/utilisateur/addAgence", name="agence_add")
     */
    public function addAgence(Request $request)
    {
        $a = new Agence();
        $forms = $this->createForm(AgencesType::class, $a);
        $forms->handleRequest($request);
        if ($forms->isSubmitted() && $forms->isValid()){
            $a = $forms->getData();
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($a);
            $em->flush();
        }
        return $this->redirectToRoute('utilisateur');
    }

    /**
     * @Route("/utilisateur/edit/{id}", name="user_edit")
     */
    public function edit($id)
    {
        $a=new Agence();
        $em = $this->getDoctrine()->getManager();
        $p = $em->getRepository(User::class)->find($id);

        $form = $this->createForm(UserType::class, $p, array('action'=>$this->generateUrl('user_update', ['id' => $id])));
        $data['form'] = $form->createView();

        $forms = $this->createForm(AgencesType::class, $a, array('action'=>$this->generateUrl('agence_add')));
        $data['forms'] = $forms->createView();

        $data['users'] = $em->getRepository(User::class)->findAll();
        return $this->render('utilisateur/index.html.twig',$data);
    
    }

    /**
     * @Route("/utilisateur/update/{id}", name="user_update")
     */
    public function update($id, Request $request)
    {
        
        $p = new User();
        $form = $this->createForm(UserType::class, $p);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $p = $form->getData();
            //$p->setUser($this->getUser()); la modification est effectuée sur le libelle du produit
            //$p->setId($id);
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository(User::class)->find($id);
            $user->setEmail($p->getEmail());
            $em->flush();
        }
      
        return $this->redirectToRoute('utilisateur');
    }

    /**
     * @Route("/utilisateur/delete/{id}", name="user_delete")
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($id);
        if ($user != null)
        {
            $em->remove($user);
            $em->flush();
        }
        return $this->redirectToRoute('utilisateur');
         
    }
}
