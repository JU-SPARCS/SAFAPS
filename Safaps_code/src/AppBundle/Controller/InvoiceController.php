<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ModelBundle\Entity\Organization;
use ModelBundle\Entity\Manager;
use ModelBundle\Entity\Invoice;
use \DateTime;

class InvoiceController extends Controller
{
    public function showOrganizationListAction()
    {
        $em            = $this->getDoctrine()->getManager();
        $org_repo      = $em->getRepository('ModelBundle:Organization');
        $organizations = $org_repo->findAll();
        
        return $this->render('AppBundle:Invoice:listOrganizations.html.twig', array(
            "organizations" => $organizations
        ));
    }
    
    public function showOrganizationInvoicesAction($orgId)
    {
        $em               = $this->getDoctrine()->getManager();
        $org_repo         = $em->getRepository('ModelBundle:Organization');
        $organization     = $org_repo->findOneBy(array(
            'id' => $orgId
        ));
        $invoices         = $organization->getInvoices();
        $invoicesDetailed = array();
        
        foreach ($invoices as $invoice) {
            $amountList = $this->computeAmountList($invoice);
            array_push($invoicesDetailed, array(
                "invoice" => $invoice,
                "amountList" => $amountList
            ));
        }
        
        return $this->render('AppBundle:Invoice:showOrganizationInvoiceList.html.twig', array(
            "organization" => $organization,
            "invoices" => $invoicesDetailed
        ));
    }
    
    public function generateOrganizationInvoicesAction(Request $request, $orgId)
    {
        if ($request->getMethod() == 'POST') {
            $em       = $this->getDoctrine()->getManager();
            $org_repo = $em->getRepository('ModelBundle:Organization');
            $org      = $org_repo->findOneBy(array(
                'id' => $orgId
            ));
            $sDateStr = $request->get("startDate");
            $eDateStr = $request->get("endDate");
            
            if ($this->validateInvoiceGeneration($org, $sDateStr, $eDateStr)) {
                $sDate  = DateTime::createFromFormat('Y-m-d', $sDateStr);
                $eDate  = DateTime::createFromFormat('Y-m-d', $eDateStr);
                $amount = $this->createInvoices($org, $sDate, $eDate);
                if ($amount > 0) {
                    $this->addFlash('invoice', 'generated');
                }
            } else {
                $this->addFlash('invoice', 'invalid');
            }
            
            $url = $this->generateUrl("organization_invoice_list", array(
                "orgId" => $orgId
            ));
            return $this->redirect($url);
        }
    }
    
    function validateDate($date)
    {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') == $date;
    }
    
    private function validateInvoiceGeneration($org, $sDateStr, $eDateStr)
    {
        if ($this->validateDate($sDateStr) && $this->validateDate($eDateStr)) {
            $sDate = DateTime::createFromFormat('Y-m-d', $sDateStr);
            $eDate = DateTime::createFromFormat('Y-m-d', $eDateStr);
            
            return $org != NULL && $eDate > $sDate;
        }
        return FALSE;
    }
    
    private function computeAmountList($invoice)
    {
        $org             = $invoice->getOrganization();
        $amounts         = array();
        $em_m            = $this->getDoctrine()->getManager();
        $manager_repo    = $em_m->getRepository('ModelBundle:Manager');
        $evaluation_repo = $em_m->getRepository('ModelBundle:Evaluation');
        
        // Get the managers
        $managers = $manager_repo->findBy(array(
            'organization' => $org,
            'active' => 1
        ));
        foreach ($managers as $manager) {
            $amount = 0;
            $evals  = $evaluation_repo->findBy(array(
                'manager' => $manager,
                'status' => 'done'
            ));
            if ($evals != null) {
                foreach ($evals as $eva) {
                    if ($eva->getDateCompleted() >= $invoice->getPeriodStart() && $eva->getDateCompleted() <= $invoice->getPeriodEnd()) {
                        $amount = $amount + 1;
                    }
                }
            }
            $mng_amount_pair = array(
                "manager" => $manager,
                "amount" => $amount
            );
            array_push($amounts, $mng_amount_pair);
        }
        return $amounts;
    }
    
    private function createInvoices($org, $sDate, $eDate)
    {
        $invoicecost     = 1.25; // Costs of a single evaluation
        $amount          = 0;
        // Get Entity Manager
        $em_m            = $this->getDoctrine()->getManager();
        // Get repos
        $manager_repo    = $em_m->getRepository('ModelBundle:Manager');
        $evaluation_repo = $em_m->getRepository('ModelBundle:Evaluation');
        
        // Get the managers
        $managers = $manager_repo->findBy(array(
            'organization' => $org,
            'active' => 1
        ));
        foreach ($managers as $manager) {
            $evals = $evaluation_repo->findBy(array(
                'manager' => $manager,
                'status' => 'done'
            ));
            if ($evals != null) {
                foreach ($evals as $eva) {
                    if ($eva->getDateCompleted() >= $sDate && $eva->getDateCompleted() <= $eDate) {
                        $amount = $amount + 1;
                    }
                }
            }
        }
        
        if ($amount > 0) {
            $invoice = new Invoice();
            $invoice->setCreationDate(new \DateTime());
            $invoice->setPeriodStart($sDate);
            $invoice->setPeriodEnd($eDate);
            $invoice->setAmount($amount);
            $invoice->setOrganization($org);
            $invoice->setCurrency('USD');
            $invoice->setTotalCost($amount * $invoicecost);
            
            $em_m->persist($invoice);
            $em_m->flush();
            
        }
        return $amount;
    }
}
