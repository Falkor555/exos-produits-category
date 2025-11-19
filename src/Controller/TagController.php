<?php

namespace App\Controller;


use App\Entity\Tag;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TagController extends AbstractController
{

    public function __construct(private EntityManagerInterface $em)
    {

    }

    #[Route('/tag', name: 'app_tag')]
    public function index(): Response
    {
        return $this->render('tag/index.html.twig', [
            'controller_name' => 'TagController',
        ]);
    }
    #[Route('/tag/create', name: 'app_tag_create')]
    public function create(Request $request): Response
    {
        $tag = new Tag();
        $formBuilder=$this->createFormBuilder($tag);
        $formBuilder->add('nom',TextType::class)
            ->add('submit',SubmitType::class);



        $form=$formBuilder->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $tag=$form->getData();
            //persist

            $this->em->persist($tag);
            $this->em->flush();
        }


        return $this->render('tag/create.html.twig', [
            'form' =>   $form,
        ]);
    }
}
