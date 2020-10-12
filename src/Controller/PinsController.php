<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Repository\PinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PinsController extends AbstractController
{
    /**
     * @Route("/", name="app_home",methods={"GET"})
     */
    public function index(PinRepository $pinRepo): Response
    {
        $pins = $pinRepo->findBy([],['createdAt'=>'DESC']);
        return $this->render('pins/index.html.twig',compact('pins'));
    }

    /**
     * @Route("/pins/{id<[0-9]+>}", name="app_pins_show", methods={"GET"})
     */
    public function show(Pin $pin): Response
    {
        return $this->render('pins/show.html.twig',compact('pin'));
    }
   
    /**
     * @Route("/pins/create", name="app_pins_create", methods={"GET","POST"})
     */
    public function create(Request $req, EntityManagerInterface $em): Response
    {
        $form = $this->createFormBuilder(new Pin)
            ->add('title', TextType::class)
            ->add('content', TextareaType::class)
            ->getForm()
        ;

        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid()){
            $pin = $form->getData();
            $em->persist($pin);
            $em->flush();
            return $this->redirectToRoute('app_home');
        }

        return $this->render('pins/create.html.twig',[
            'form' => $form->createView()
        ]);
    }

     /**
     * @Route("/pins/{id<[0-9]+>}/edit", name="app_pins_edit", methods={"GET","POST"})
     */
    public function edit(Pin $pin,Request $req,EntityManagerInterface $em): Response
    {
        $form = $this->createFormBuilder($pin)
            ->add('title', TextType::class)
            ->add('content', TextareaType::class)
            ->getForm()
        ;
        
        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid()){
            $em->flush();
            return $this->redirectToRoute('app_home');
        }

        return $this->render('pins/edit.html.twig',[
            'pin' => $pin,
            'form' => $form->createView()
        ]);
    }
}
