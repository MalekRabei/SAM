<?php

namespace App\Controller;

use App\Entity\Calcul;
use App\Entity\Employee;
use App\Entity\Perse;
use App\Entity\Presence;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Include Dompdf required namespaces
use Dompdf\Dompdf;
use Dompdf\Options;
class PerseController extends Controller
{

    public function new()
    {

        //base sidebar
        $currentdate = new \DateTime('now');
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



        $em = $this->getDoctrine()->getManager();
        $perse = new Perse();
        if (isset($_POST['numeroPerse'])) {
            $numeroPerse = $_POST['numeroPerse'];
            $note = $_POST['note'];




            //recuperer l id user
            $usr= $this->get('security.token_storage')->getToken()->getUser()->getId();
            $user=$em->getRepository(User::class)->find($usr);

            $perse->setNumPerse($numeroPerse);
            $perse->setNote($note);
            $perse->setDatePerse($currentdate);
            $perse->setIdEmployee($user);

            $em->persist($perse);
            $em->flush();
            return $this->redirectToRoute("ajoutPerse");

        }
       /// $notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        $listEmployee = $this->getDoctrine()->getRepository(Employee::class)->findAll();

        //recuperer l id user
        $usr= $this->get('security.token_storage')->getToken()->getUser()->getId();
        $list = $this->getDoctrine()->getRepository(Perse::class)->listByUser($usr);

        $manager = $this->get('mgilet.notification');
        $notif = $manager->createNotification('Hello world !');
        $notif->setMessage('This a notification.');
        $notif->setLink('http://symfony.com/');
        $manager->addNotification(array($perse), $notif, true);




        return $this->render('perse/new.html.twig', array(
            'nbTerna' => $nbterna
        , 'nbShapeTek' => $nbShapeTek
        , 'nbCongeShapeTek' => $nbCongeShapeTek,
            'nbCongeTerna' => $nbCongeTerna
        , 'nbpresent' => $nbpresentTerna,
            'nbpresentSh' => $nbpresentSh,
            'listEmployee' => $listEmployee,
            'notifications'=>"test",
            'list' => $list ,
            'currentDate'=>$currentdate));

    }

    public function edit(Request $request, $id)
    {
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
        $em = $this->getDoctrine()->getManager();

        $perse =  $this->getDoctrine()->getRepository(Perse::class)->find($id);

        $numeroPerseA= $perse->getNumPerse();
        $noteA= $perse->getNote();

        if (isset($_POST['numeroPerse'])) {
            $numeroPerse = $_POST['numeroPerse'];
            $note = $_POST['note'];
            //recuperer l id user
            $usr= $this->get('security.token_storage')->getToken()->getUser()->getId();
            $user=$em->getRepository(User::class)->find($usr);
            $perse->setNumPerse($numeroPerse);
            $perse->setNote($note);
            $perse->setDatePerse($currentdate);
            $perse->setIdEmployee($user);

            $em->persist($perse);
            $em->flush();
            return $this->redirectToRoute("ajoutPerse");

        }
       // $notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        $listEmployee = $this->getDoctrine()->getRepository(Employee::class)->findAll();
        $list = $this->getDoctrine()->getRepository(Perse::class)->findAll();


        return $this->render('perse/edit.html.twig', array(
            'nbTerna' => $nbterna
        , 'nbShapeTek' => $nbShapeTek
        , 'nbCongeShapeTek' => $nbCongeShapeTek,
            'nbCongeTerna' => $nbCongeTerna
        , 'nbpresent' => $nbpresentTerna,
            'nbpresentSh' => $nbpresentSh,
            'id'=>$id,
            'numeroPerse'=> $numeroPerseA,
            'note'=> $noteA,
            'listEmployee' => $listEmployee,
            'notifications'=>"test",
            'list' => $list ,
            'currentDate'=>$currentdate));
    }


    public function delete(request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $perse = $em->getRepository(Perse::class)->find($id);
        $em->remove($perse);
        $em->flush();
        return $this->redirectToRoute('ajoutPerse');
    }


    public function admin()
    {   //base side bar
        $currentdate = new \DateTime('now');
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
        $em = $this->getDoctrine()->getManager();

        //recuperer l id user
        $usr= $this->get('security.token_storage')->getToken()->getUser()->getId();
        $user=$em->getRepository(User::class)->find($usr);
        /// $notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();


        $listEmployee = $this->getDoctrine()->getRepository(Employee::class)->findAll();
        $list = $this->getDoctrine()->getRepository(Perse::class)->perseGroupedByUser();


        $listAllUser = $this->getDoctrine()->getRepository(Perse::class)-> listAllUsers();

       // $listByUser = $this->getDoctrine()->getRepository(Perse::class)->listByUser($usr);
        $totalPerDay = $this->getDoctrine()->getRepository(Perse::class)->TotalPersePerDay();

        var_dump($totalPerDay[0]["total"]);

        return $this->render('perse/index.html.twig', array(
            'nbTerna' => $nbterna
        , 'nbShapeTek' => $nbShapeTek
        , 'nbCongeShapeTek' => $nbCongeShapeTek,
            'nbCongeTerna' => $nbCongeTerna
        , 'nbpresent' => $nbpresentTerna,
            'nbpresentSh' => $nbpresentSh,
            'listEmployee' => $listEmployee,
            'listByUser'=>$listAllUser,
            'notifications'=>"test",
            'list' => $list ,
            'currentDate'=>$currentdate,
            'totalPerDay'=>$totalPerDay[0]["total"]));

    }

    public function listPDF(){
        $listEmployee = $this->getDoctrine()->getRepository(Employee::class)->findAll();
        $list = $this->getDoctrine()->getRepository(Perse::class)->perseGroupedByUser();
        $currentdate = new \DateTime('now');
        $totalPerDay = $this->getDoctrine()->getRepository(Perse::class)->TotalPersePerDay();

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('perse/persePerDay.html.twig', [
            'title' => "Welcome to our PDF Test",
            'listEmployee' => $listEmployee,
            'list' => $list ,
            'currentDate'=>$currentdate,
            'totalPerDay'=>$totalPerDay[0]["total"]
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'landscape' or 'portrait'
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        //For view
        //$dompdf->stream("",array("Attachment" => false));
        // for download
        return new Response (
            $dompdf->stream());

    }


}
