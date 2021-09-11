<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Yaml\Yaml;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $array = Yaml::parseFile($this->getParameter('kernel.project_dir') . '/content/organizations.yaml');
        //dd($array['organizations']);
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'array' => $array['organizations']
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function getform(Request $request, $id): Response
    {
        $array = Yaml::parseFile($this->getParameter('kernel.project_dir') . '/content/organizations.yaml');
        //dd($array['organizations'][$id]);
        return $this->render('edit/index.html.twig', [
            'controller_name' => 'HomeController',
            'array' => $array['organizations'][--$id],
            'id' => $id
        ]);
    }

    /**
     * @Route("/update/{id}", name="update")
     */
    public function update(Request $request, $id): Response
    {
        $array = Yaml::parseFile($this->getParameter('kernel.project_dir') . '/content/organizations.yaml');
        $array['organizations'][$id]['name'] = $request->request->get('name');
        $array['organizations'][$id]['description'] = $request->request->get('descri');
        $i = -1;
        while (++$i < count($array['organizations'][$id]['users']))
        {
            if($request->request->get('usrname'. $i))
                $array['organizations'][$id]['users'][$i]['name'] = $request->request->get('usrname'.$i);
        }
        $yaml = Yaml::dump($array);

        file_put_contents($this->getParameter('kernel.project_dir') . '/content/organizations.yaml', $yaml);
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/getadd", name="getadd")
     */
    public function getadd(Request $request): Response
    {
        $array = Yaml::parseFile($this->getParameter('kernel.project_dir') . '/content/organizations.yaml');
        return $this->render('add/index.html.twig', [
            'controller_name' => 'HomeController',
            'array' => $array['organizations']
        ]);
    }
    /**
     * @Route("/add/", name="add")
     */
    public function add(Request $request): Response
    {
        $array = Yaml::parseFile($this->getParameter('kernel.project_dir') . '/content/organizations.yaml');
        $i = -1;
        $users = null;
        if( $request->request->get('username'. ++ $i))
        {
            $users = array(
                $i => array(
                "name" => $request->request->get('username'. $i),
                "password" => "empty",
                "role" => array("empty"))
                );
            while( $request->request->get('username'. ++ $i))
            {
                array_push($users, array(
                    "name" => $request->request->get('username'. $i),
                    "password" => "empty",
                    "role" => array("empty")));
            }
        }
        $new = array (
            "name" => $request->request->get('name'),
            "description" => $request->request->get('descri'),
            "users" => $users
        );
        array_push($array['organizations'], $new);
        $yaml = Yaml::dump($array);

        file_put_contents($this->getParameter('kernel.project_dir') . '/content/organizations.yaml', $yaml);
        return $this->redirectToRoute('home');
    }
    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Request $request, $id): Response
    {
        $id--;
        $array = Yaml::parseFile($this->getParameter('kernel.project_dir') . '/content/organizations.yaml');
        array_splice($array['organizations'], $id, $id+1);
        $yaml = Yaml::dump($array);
        file_put_contents($this->getParameter('kernel.project_dir') . '/content/organizations.yaml', $yaml);
        return $this->redirectToRoute('home');
    }
}
