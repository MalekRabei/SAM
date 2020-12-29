<?php

namespace App\Controller;

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
        $currentdate = new \DateTime('now');
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


      /*  $usr= $this->get('security.token_storage')->getToken()->getUser()->getId();
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
        $em->flush();*/

        return $this->render('perse/new.html.twig', array(

            'listEmployee' => $listEmployee,
            'notifications'=>"test",
            'list' => $list ,
            'currentDate'=>$currentdate));

    }

    public function edit(Request $request, $id)
    {
        $currentdate = new \DateTime('now');
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
    {
        $currentdate = new \DateTime('now');
        $em = $this->getDoctrine()->getManager();

        //recuperer l id user
        $usr= $this->get('security.token_storage')->getToken()->getUser()->getId();
        $user=$em->getRepository(User::class)->find($usr);
        /// $notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();


        $listEmployee = $this->getDoctrine()->getRepository(Employee::class)->findAll();
        $list = $this->getDoctrine()->getRepository(Perse::class)->perseGroupedByUser();

        $listByUser = $this->getDoctrine()->getRepository(Perse::class)->listByUser($usr);
        $totalPerDay = $this->getDoctrine()->getRepository(Perse::class)->TotalPersePerDay();

        var_dump($totalPerDay[0]["total"]);

        return $this->render('perse/index.html.twig', array(

            'listEmployee' => $listEmployee,
            'listByUser'=>$listByUser,
            'notifications'=>"test",
            'list' => $list ,
            'currentDate'=>$currentdate,
            'totalPerDay'=>$totalPerDay[0]["total"]));

    }

//par mois + jours + user
    public function calcul()
    {
        return $this->render('perse/calcul.html.twig', array(
            // ...
        ));
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
