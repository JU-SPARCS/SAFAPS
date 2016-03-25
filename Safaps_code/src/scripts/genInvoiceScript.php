<?php 
namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand; 
//use Symfony\Bundle\FrameworkBundle\Controller\Controller; use 
//Sensio\Bundle\FrameworkExtraBundle\Configuration\Route; use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template; use 
//Symfony\Component\HttpFoundation\Request; use Symfony\Component\HttpFoundation\Response; use Symfony\Component\HttpFoundation\JsonResponse; use 
//Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter; use Symfony\Component\Serializer\Normalizer\ObjectNormalizer; use 
//ModelBundle\Entity\Event; use ModelBundle\Entity\Evaluation; use ModelBundle\Entity\Result; use ModelBundle\Entity\Organization; use 
//ModelBundle\Entity\Manager; use ModelBundle\Entity\Invoice; 

class InvoiceScript extends Controller {
	
function genInvoice($sDate,$eDate){
		$em = $this->getDoctrine()->getOrganization();
		$organization_repository = $em->getRepository('ModelBundle:Organization');
		
		foreach($organization_repository as $organization){
			createInvoice($organzitaion,$sDate,$eDate);
		}
	}
	function createInvoice($org,$sDate,$eDate){
		$invoicecost = 0.05; // Costs of a single evaluation
		$totalcost = 0;
		$amount = 0;
		//Find active managers for organization
		$em_m = $this->getDoctrine()->getManager();
		$manager_repository = $em_m->getRepository('ModelBundle:Manager');
		$managers = $manager_repository->findBy(array('organization' => $org, 'active' => 1)); //Returns managers for organization org
		
		//Returns all evalutaions so that they can be used in a comparasion
		$em_e = $this->getDoctrine()->getEvaluation();
		$evaluation_repository = $em_e->getRepository('ModelBundle:Evaluation');
		
		//Get their evaluations
		foreach($managers as $manager){
			$evaluation = $evaluation_repository->findBy(array('manager' => $manager, 'status' => 'done'));
			if($evaluation != null){
				//Sum their cost
				foreach($evaluation as $eva){
					if($eva->getDateSubmitted() >= $sDate && $eva->getDateCompleted() <= $eDate ){
						$totalcost = $totalcost + $invoicecost;
						$amount = $amount + 1;
					}
				}
			}
		}
		//Create invoice with start and end date
		$em_i = $this->getDoctorine()->getInvoice();
		
		$invoice = new Invoice();
		$invoice->setCreationDate(date());
		$invoice->setPeriodStart($sDate);
		$invoice->setAmount($amount);
		$invoice->setOrganization($org);
		$invoice->setCurrency('USD');
		
		//Save changes
		$em_i->persist($invoice);
		$em_i->flush();
	}
}
$b4 = mktime(11, 14, 54, 8, 12, 2015);
$now = date();
$TE = new InvoiceScript();
$TE->genInvoice($b4,$now);
?>
