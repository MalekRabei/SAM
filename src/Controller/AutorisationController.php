<?php

namespace App\Controller;

use App\Entity\Autorisation;
use App\Entity\Calcul;
use App\Entity\Employee;
use App\Entity\Poste;
use App\Entity\User;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AutorisationController extends AbstractController
{
    /**
     * @Route("/autorisation", name="autorisation")
     */
    public function index(Request $request): Response
    {

        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Unable to access this page!');

        $list = $this->getDoctrine()
            ->getRepository(Autorisation::class)->findAll();
        if ($request->isMethod('POST')) {
            $nom = $request->get('nom');
            $list = $this->getDoctrine()
                ->getRepository(Employee::class)->findBy(array('nom' => $nom));
        }
      //  $notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        $currentdate = new \DateTime('now');

        //base side bar
        $nbemployeeTerna = $this->getDoctrine()
            ->getRepository(Calcul::class)->totalEmployeeTernaQuery();
        $nbterna = $nbemployeeTerna[0]["total"];


        $nbCongeTerna = $this->getDoctrine()
            ->getRepository(Calcul::class)->nbCongeTernaQuery();
        $nbCongeTerna = $nbCongeTerna[0]["nbConge"];
        $nbpresentTerna = intval($nbterna) - intval($nbCongeTerna);

        $nbemployeeShapeTek = $this->getDoctrine()
            ->getRepository(Calcul::class)->totalEmployeeShapeTekQuery();
        $nbShapeTek = $nbemployeeShapeTek[0]["total"];


        $nbCongeShapeTek = $this->getDoctrine()
            ->getRepository(Calcul::class)->nbCongeShaptekQuery();
        $nbCongeShapeTek = $nbCongeShapeTek[0]["nbConge"];
        $nbpresentSh = intval($nbShapeTek) - intval($nbCongeShapeTek);

        $poste= $this->getDoctrine()->getRepository(Poste::class)->posteQuery();

        return $this->render('autorisation/index.html.twig', [
            'nbTerna' => $nbterna
            , 'nbShapeTek' => $nbShapeTek
            , 'nbCongeShapeTek' => $nbCongeShapeTek,
            'nbCongeTerna' => $nbCongeTerna
            , 'nbpresent' => $nbpresentTerna,
            'nbpresentSh' => $nbpresentSh,
            'currentDate' => $currentdate,
            'controller_name' => 'AutorisationController',
            'poste'=>$poste,
            'currentDate' => $currentdate,
            'list' => $list,
            'notifications'=>"test",
        ]);
    }

    public function new()
    {   $currentdate = new \DateTime('now');

        //base side bar
        $nbemployeeTerna = $this->getDoctrine()
            ->getRepository(Calcul::class)->totalEmployeeTernaQuery();
        $nbterna = $nbemployeeTerna[0]["total"];
        $nbCongeTerna = $this->getDoctrine()
            ->getRepository(Calcul::class)->nbCongeTernaQuery();
        $nbCongeTerna = $nbCongeTerna[0]["nbConge"];
        $nbpresentTerna = intval($nbterna) - intval($nbCongeTerna);
        $nbemployeeShapeTek = $this->getDoctrine()
            ->getRepository(Calcul::class)->totalEmployeeShapeTekQuery();
        $nbShapeTek = $nbemployeeShapeTek[0]["total"];
        $nbCongeShapeTek = $this->getDoctrine()
            ->getRepository(Calcul::class)->nbCongeShaptekQuery();
        $nbCongeShapeTek = $nbCongeShapeTek[0]["nbConge"];
        $nbpresentSh = intval($nbShapeTek) - intval($nbCongeShapeTek);

        $autorisation = new Autorisation();
        $usr= $this->get('security.token_storage')->getToken()->getUser()->getId();

        if (isset($_POST['motif'])) {
            $motif = $_POST['motif'];

            $nbHeure = $_POST['nbJours'];


            $em = $this->getDoctrine()->getManager();

            //recuperer l id user
             $user=$em->getRepository(User::class)->find($usr);

            $autorisation->setMotif($user->getUsername()."  ".$motif);
            $autorisation->setDateAutorisation($currentdate);
            $autorisation->setNbHeure($nbHeure);
            $autorisation->setIdUser($user);
            $autorisation->setEtat('Non validé');

            $em->persist($autorisation);
            $em->flush();
            $usr= $this->get('security.token_storage')->getToken()->getUser()->getRoles();
            foreach ($usr as $role){
                if ($role != "ROLE_ADMIN" ){
                    echo 'supp from user ';
                    return $this->redirectToRoute('demandeAutorisation');

                } else
                    echo 'supp from admin';
                return $this->redirectToRoute('listAutorisation');

            }

        }
        //$notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        $listEmployee = $this->getDoctrine()->getRepository(Employee::class)->findAll();

        $list = $this->getDoctrine()->getRepository(Autorisation::class)->autorisationByUser($usr);

        return $this->render('autorisation/new.html.twig', array(
            'nbTerna' => $nbterna
        , 'nbShapeTek' => $nbShapeTek
        , 'nbCongeShapeTek' => $nbCongeShapeTek,
            'nbCongeTerna' => $nbCongeTerna
        , 'nbpresent' => $nbpresentTerna,
            'nbpresentSh' => $nbpresentSh,
            'listEmployee' => $listEmployee,
            'notifications'=>"test",
            'currentDate'=>$currentdate,
            'list'=>$list ));
    }


    public function adminNew()
    {   $currentdate = new \DateTime('now');
        $autorisation = new Autorisation();
        $usr= $this->get('security.token_storage')->getToken()->getUser()->getId();

        if (isset($_POST['motif'])) {
            $motif = $_POST['motif'];

            $nbHeure = $_POST['nbJours'];


            $em = $this->getDoctrine()->getManager();

            //recuperer l id user
            $user=$em->getRepository(User::class)->find($usr);

            $autorisation->setMotif($user->getUsername()."  ".$motif);
            $autorisation->setDateAutorisation($currentdate);
            $autorisation->setNbHeure($nbHeure);
            $autorisation->setIdUser($user);
            $autorisation->setEtat('Non validé');

            $em->persist($autorisation);
            $em->flush();
            return $this->redirectToRoute("listAutorisation");

        }
        //$notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        $listEmployee = $this->getDoctrine()->getRepository(Employee::class)->findAll();

        $list = $this->getDoctrine()->getRepository(Autorisation::class)->findAll();

        return $this->render('autorisation/new.html.twig', array(
            'listEmployee' => $listEmployee,
            'notifications'=>"test",
            'currentDate'=>$currentdate,
            'list'=>$list ));
    }



    public function update(Request $request, $id)
    {
        $currentdate = new \DateTime('now');
        $em = $this->getDoctrine()->getManager();

        //recuperer l id user
        $usr= $this->get('security.token_storage')->getToken()->getUser()->getId();

        $autorisation = $this->getDoctrine()->getRepository(Autorisation::class)->find($id);

        $motifA = $autorisation->getMotif();
        $nbHeureA= $autorisation->getNbHeure();
        $dateDebut= $autorisation->getDateAutorisation();
        $idEmployee= $autorisation->getIdUser();


        if (isset($_POST['motif'])) {
            $motif = $_POST['motif'];
            $nbHeure = $_POST['nbJours'];
            $user=$em->getRepository(User::class)->find($usr);

            $autorisation->setMotif($user->getUsername()."".$motif);
            $autorisation->setDateAutorisation($currentdate);
            $autorisation->setNbHeure($nbHeure);
            $autorisation->setIdUser($user);

            $em->persist($autorisation);
            $em->flush();
            $usr= $this->get('security.token_storage')->getToken()->getUser()->getRoles();
            foreach ($usr as $role){
                if ($role != "ROLE_ADMIN" ){
                    echo 'supp from user ';
                    return $this->redirectToRoute('demandeAutorisation');

                } else
                    echo 'supp from admin';
                return $this->redirectToRoute('listAutorisation');

            }

        }
       // $notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        $listEmployee = $this->getDoctrine()->getRepository(Employee::class)->findAll();
        $list = $this->getDoctrine()->getRepository(Autorisation::class)->autorisationByUser($usr);

        //base side bar
        $nbemployeeTerna = $this->getDoctrine()
            ->getRepository(Calcul::class)->totalEmployeeTernaQuery();
        $nbterna = $nbemployeeTerna[0]["total"];


        $nbCongeTerna = $this->getDoctrine()
            ->getRepository(Calcul::class)->nbCongeTernaQuery();
        $nbCongeTerna = $nbCongeTerna[0]["nbConge"];
        $nbpresentTerna = intval($nbterna) - intval($nbCongeTerna);

        $nbemployeeShapeTek = $this->getDoctrine()
            ->getRepository(Calcul::class)->totalEmployeeShapeTekQuery();
        $nbShapeTek = $nbemployeeShapeTek[0]["total"];


        $nbCongeShapeTek = $this->getDoctrine()
            ->getRepository(Calcul::class)->nbCongeShaptekQuery();
        $nbCongeShapeTek = $nbCongeShapeTek[0]["nbConge"];
        $nbpresentSh = intval($nbShapeTek) - intval($nbCongeShapeTek);


        return $this->render('autorisation/edit.html.twig', array(
            'nbTerna' => $nbterna
        , 'nbShapeTek' => $nbShapeTek
        , 'nbCongeShapeTek' => $nbCongeShapeTek,
            'nbCongeTerna' => $nbCongeTerna
        , 'nbpresent' => $nbpresentTerna,
            'nbpresentSh' => $nbpresentSh,
            'id'=>$id,
            'motif'=>$motifA ,
            'nbHeure'=> $nbHeureA,
            'dateDebut'=>$dateDebut,
            'idEmploye'=>$idEmployee,
            'listEmployee' => $listEmployee,
            'notifications'=>"test",
            'currentDate'=>$currentdate,
            'list'=>$list));
    }


    public function adminUpdate(Request $request, $id)
    {
        $currentdate = new \DateTime('now');
        $em = $this->getDoctrine()->getManager();

        //recuperer l id user
        $usr= $this->get('security.token_storage')->getToken()->getUser()->getId();

        $autorisation = $this->getDoctrine()->getRepository(Autorisation::class)->find($id);

        $motifA = $autorisation->getMotif();
        $nbHeureA= $autorisation->getNbHeure();
        $dateDebut= $autorisation->getDateAutorisation();
        $idEmployee= $autorisation->getIdUser();


        if (isset($_POST['motif'])) {
            $motif = $_POST['motif'];
            $nbHeure = $_POST['nbJours'];
            $user=$em->getRepository(User::class)->find($usr);

            $autorisation->setMotif($user->getUsername()."".$motif);
            $autorisation->setDateAutorisation($currentdate);
            $autorisation->setNbHeure($nbHeure);
            $autorisation->setIdUser($user);

            $em->persist($autorisation);
            $em->flush();
            return $this->redirectToRoute("demandeAutorisation");

        }
        // $notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        $listEmployee = $this->getDoctrine()->getRepository(Employee::class)->findAll();
        $list = $this->getDoctrine()->getRepository(Autorisation::class)->findAll();




        return $this->render('autorisation/edit.html.twig', array(
            'id'=>$id,
            'motif'=>$motifA ,
            'nbHeure'=> $nbHeureA,
            'dateDebut'=>$dateDebut,
            'idEmploye'=>$idEmployee,
            'listEmployee' => $listEmployee,
            'notifications'=>"test",
            'currentDate'=>$currentdate,
            'list'=>$list));
    }

    public function show()
    {
        return $this->render('autorisation/show.html.twig', array(
            // ...
        ));
    }


    public function delete(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $autorisation = $em->getRepository(Autorisation::class)->find($id);
        $em->remove($autorisation );
        $em->flush();
        $usr= $this->get('security.token_storage')->getToken()->getUser()->getRoles();
        foreach ($usr as $role){
            if ($role != "ROLE_ADMIN" ){
                echo 'supp from user ';
                return $this->redirectToRoute('demandeAutorisation');

            } else
                echo 'supp from admin';
            return $this->redirectToRoute('listAutorisation');

        }
    }

    public function valider(Request $request, $id){

        $em = $this->getDoctrine()->getManager();

        $autorisation = $this->getDoctrine()
            ->getRepository(Autorisation::class)->find($id);
            $autorisation->setEtat("Validé");
            $em->persist($autorisation);
            $em->flush();
            return $this->redirectToRoute("listAutorisation");

    }




    public function rejeter(Request $request, $id){

        $em = $this->getDoctrine()->getManager();

        $autorisation = $this->getDoctrine()
            ->getRepository(Autorisation::class)->find($id);
        $autorisation->setEtat("Rejeté");
        $em->persist($autorisation);
        $em->flush();
        return $this->redirectToRoute("listAutorisation");

    }



}
