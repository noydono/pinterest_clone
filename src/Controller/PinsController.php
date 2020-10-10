<?php

namespace App\Controller;


use App\Repository\PinRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PinsController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(PinRepository $pinRepo)
    {
        $pins = $pinRepo->findAll();
        return $this->render('pins/index.html.twig',compact('pins'));
    }
}
