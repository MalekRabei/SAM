<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Entity\Poste;
use App\Entity\Presence;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeeController extends AbstractController
{
    /**
     * @Route("/employee", name="employee")
     */
    public function index(): Response
    {
        return $this->render('employee/index.html.twig', [
            'controller_name' => 'EmployeeController',
        ]);
    }
    /*************************** Gestion Employee***************************/

    public function ajoutEmployee(Request $request)
    {
       // $notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        $em = $this->getDoctrine()->getManager();
        $pos= $this->getDoctrine()->getRepository(Poste::class)->posteQuery();
       // var_dump($pos);
        //recuperer l id user
         $usr= $this->get('security.token_storage')->getToken()->getUser()->getId();
         $user=$em->getRepository(User::class)->find($usr);

        $employee = new Employee();
        if (isset($_POST['nom']) || isset($_POST['prenom']) || isset($_POST['poste']) || isset($_POST['matricule'])) {
            //condition d'ajout
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $societe = $_POST['ck1'];
            $poste = $_POST['poste'];
            $matricule = $_POST['matricule'];
            $code = $_POST['code'];
            $t= $_POST['t'];
            $pos= $_POST['idPoste'];
            $posi = $this->getDoctrine()->getRepository(Poste::class)->find($pos);
            $employee->setNom($nom);
            $employee->setPrenom($prenom);
            $employee->setSociete($societe);
            $employee->setPoste($poste);
            $employee->setMatricule($matricule);
            $employee->setCode($code);
            $employee->setT($t);
            $employee->setIdPoste($posi);
            $employee->setPresence('NON');

            $em->persist($employee);
            $em->flush();


            return $this->redirectToRoute("index");

        }
        //left side bar

        $currentdate = new \DateTime('now');
        return $this->render('employee/ajoutEmployee.html.twig', array(
            'notifications'=>"test",
            'currentDate'=>$currentdate,
            'pos'=>$pos));
    }

    public function modifEmployee(Request $request, $id)
    {
        $notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        $em = $this->getDoctrine()->getManager();
        $pos= $this->getDoctrine()->getRepository(Positionnement::class)->posteQuery();


        $employee = $this->getDoctrine()
            ->getRepository(Employee::class)->find($id);
        $nomA = $employee->getNom();
        $prenomA = $employee->getPrenom();
        $societeA = $employee->getSociete();
        $posteA = $employee->getPoste();
        $matriculeA = $employee->getMatricule();
        $codeA = $employee->getCode();
        $positionnementA = $employee->getIdPositionnement();
        $tA = $employee->getT();
        if (isset($_POST['nom']) || isset($_POST['prenom']) || isset($_POST['poste']) || isset($_POST['matricule'])) {

            //condition d'ajout
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $societe = $_POST['ck1'];
            $poste = $_POST['poste'];
            $matricule = $_POST['matricule'];
            $code = $_POST['code'];
            $t = $_POST['t'];
            $pos= $_POST['idPoste'];
            $posi = $this->getDoctrine()->getRepository(Poste::class)->find($pos);

            //MAJ nouvelles entrées
            $employee->setNom($nom);
            $employee->setPrenom($prenom);
            $employee->setSociete($societe);
            $employee->setPoste($poste);
            $employee->setMatricule($matricule);
            $employee->setCode($code);
            $employee->setT($t);
            $employee->setIdPositionnement($posi);
            $em->persist($employee);
            $em->flush();
            return $this->redirectToRoute("index");

        }
        $list = $this->getDoctrine()->getRepository(Employee::class)->findAll();



        $currentdate = new \DateTime('now');

        return $this->render('employee/modifEmployee.html.twig'
            , array('id' => $id,
                'list' => $list,
                'nom' => $nomA,
                'prenom' => $prenomA,
                'societe' => $societeA,
                'poste' => $posteA,
                'matricule' => $matriculeA,
                'code' => $codeA,
                'positionnement' => $positionnementA,
                't' => $tA,
                'notifications'=>$notifications,
                'currentDate'=>$currentdate,
                'pos'=>$pos));


    }

    public function suppEmployeeAction(Request $request,$id)
    {

        $em = $this->getDoctrine()->getManager();
        $employee = $em->getRepository(Employee::class)->find($id);
        $em->remove($employee);
        $em->flush();
        return $this->redirectToRoute('index');
    }
    public function listPDFAction(Request $request){

        $snappy =$this->get("knp_snappy.pdf");

        $em=$this->getDoctrine()->getManager();
        $list=$em->getRepository( Presence::class)-> PresentQuery();

        $html = $this->renderView("employee/PDFReport.html.twig", array('list' => $list));
        $fichier = "Votre rapport";
        $pdfPath = $this->getParameter('pdf_directory') . '/"' . $fichier . '.pdf"';
        return new Response(

            $snappy->getOutputFromHtml($html),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment;pdf_directory/filename"' . $fichier . '.pdf"'
            )
        );

    }

    public function presence(){

        $usr= $this->get('security.token_storage')->getToken()->getUser()->getId();
        $user=$em->getRepository(User::class)->find($usr);


        $lastLogin= $this->getDoctrine()->getRepository(Employee::class)->presence($usr);

        $loginTime =  date_format($lastLogin[0]["lastLogin"], 'Y-m-d H:i:s');
        $loginHour=substr($loginTime,-8, 2);
        $present = new Presence();
        if (intval ($loginHour) == 8){
            var_dump(" in time");
            $present->setPresent("OUI");
            $present->setRetard("NON");
            $present->setDateRetard(new \DateTime('now'));
            $present->setDatePresence(new \DateTime('now'));
            $present->setIdUser($user);

        }

        else if (intval ($loginHour) > 8 && intval ($loginHour) < 18 ){
            var_dump("en retard");
            $present->setPresent("OUI");
            $present->setRetard("OUI");
            $present->setDateRetard(new \DateTime('now'));
            $present->setDatePresence(new \DateTime('now'));
            $present->setIdUser($user);

        }else if (intval ($loginHour) > 18) {
            $present->setPresent("NON");
            $present->setRetard("OUI");
            $present->setDateRetard(new \DateTime('now'));
            $present->setDatePresence(new \DateTime('now'));
            $present->setIdUser($user);

        }

        $em->persist($present);
        $em->flush();
        
        return null;
    }
}
