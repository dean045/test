<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Yaml\Yaml;
use App\Entity\Organization;
use App\Services\OrganizationService;
use App\Entity\User;


class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(OrganizationService $service): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'array' => $service->getAll($this->getParameter('kernel.project_dir'))
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function getform(Request $request, $id, OrganizationService $service): Response
    {
        return $this->render('edit/index.html.twig', [
            'controller_name' => 'HomeController',
            'id' => $id,
            'array' => $service->getById(--$id, $this->getParameter('kernel.project_dir'))
        ]);
    }

    /**
     * @Route("/update/{id}", name="update")
     */
    public function update(Request $request, $id, OrganizationService $service): Response
    {
        $service->Edit(--$id, $request, $this->getParameter('kernel.project_dir'));
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/getadd", name="getadd")
     */
    public function getadd(Request $request): Response
    {
        return $this->render('add/index.html.twig', [
            'controller_name' => 'HomeController'
        ]);
    }

    /**
     * @Route("/add/", name="add")
     */
    public function add(Request $request, OrganizationService $service): Response
    {
        $service->add($request, $this->getParameter('kernel.project_dir'));
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Request $request, $id, OrganizationService $service): Response
    {
        $service->Delete($id - 1, $this->getParameter('kernel.project_dir'));
        return $this->redirectToRoute('home');
    }
}
