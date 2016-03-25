<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use ModelBundle\Entity\Event;
use ModelBundle\Entity\Evaluation;
use ModelBundle\Entity\Result;
use OldSound\RabbitMqBundle\DependencyInjection\OldSoundRabbitMqExtension;
use OldSound\RabbitMqBundle\DependencyInjection\Compiler\RegisterPartsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use ApiBundle\Algorithm;

class ApiController extends Controller
{

    private $errorApi;

    public function putEvaluationAction(Request $request)
    {
        /* DEBUG */
        ini_set('display_errors','On');
        error_reporting(E_ALL | E_STRICT);

        $serializer = $this->get('serializer'); // Service serializer
        $em = $this->getDoctrine()->getManager(); // Get entity manager
        $manager_repository = $em->getRepository('ModelBundle:Manager');
        $normalizer = new ObjectNormalizer(null, new CamelCaseToSnakeCaseNameConverter());
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->registerExtension(new OldSoundRabbitMqExtension());
        $containerBuilder->addCompilerPass(new RegisterPartsPass());

        if($request->getMethod() == 'POST')
            {
                if (0 === strpos($this->getRequest()->headers->get('Content-Type'), 'application/json')) {

                    if (!$this->getRequest()->headers->has('Authorization'))
                        return new JsonResponse(array("Error" => "The authorization field is not specified or the API key is not valid"), 401);

                    // Get api_key from request and try to find api_key with doctrine
                    $authorization = $this->getRequest()->headers->get('Authorization');
                    $api_key_b64 = str_replace('Basic ', '', $authorization);
                    $api_key = base64_decode($api_key_b64);
                    $manager = $manager_repository->findOneBy(array('apiKey' => $api_key));

                    if ($manager == null)
                        return new JsonResponse(array("Error" => "The authorization field is not specified or the API key is not valid"), 401);

                    $data = $this->getRequest()->getContent();
                    $json_array = json_decode($data, True); // decode json to array
                    // TODO(guillaume): handle decoding of json with FOSRestBundle and modify exception error from Body Listener (instead of using json_last_error())
                    if (json_last_error() != JSON_ERROR_NONE)
                        return new JsonResponse(array("Error" => "The data is not in JSON format"), 415);
  
                    // Get evaluation field and fill a new Evaluation object: $evaluation
                    $evaluation = $normalizer->denormalize(array('ResponseURL' => $json_array['ResponseURL']), 'ModelBundle\Entity\Evaluation');
                    $validator = $this->get('validator');
                    $errors = $validator->validate($evaluation);
                    if (count($errors) > 0)
                        {
                            return new JsonResponse(array("Error" => "The response URL is not properly set"), 400);                                   
                        }
		    // set the manager for the evaluation and persist the evaluation
		    $evaluation->setManager($manager);
                    $em->persist($evaluation);

                    // error value if attributes are missing
                    $this->errorApi = array("Error" => "One or several event attributes are missing in the event ", "isError" => false);


                    if (count($json_array['Events']) == count($json_array['Events'], COUNT_RECURSIVE))
                        {
                            //simple array
                            $this->checkEvent($json_array['Events'], 0);
                        }
                    else
                        {
                            //double array
                            foreach($json_array['Events'] as $key => $element)
                                {
                                    $this->checkEvent($element, $key);
                                }
                        }
                
                    if ($this->errorApi['isError'] == true)
                        {
                            return new JsonResponse(array("Error" => $this->errorApi['Error']), 400);
                        }
                    else
                        {

                            // Init Array Event List
                            $eventList = array();
		    
                            if (count($json_array['Events']) == count($json_array['Events'], COUNT_RECURSIVE))
                                {
                                    //simple array
                                    $event = new Event();
                                    $event->setTimezone($json_array['Events']['TimeZone']);
                                    $event->setStartTime(new \DateTime($json_array['Events']['StartTime']));
                                    $event->setEndTime(new \DateTime($json_array['Events']['EndTime']));
                                    $event->setAsmEnvironment($json_array['Events']['ASMEnvironment']);
                                    $event->setControlTechnology($json_array['Events']['ControlTechnology']);
                                    $event->setControllerStatus($json_array['Events']['ControllerStatus']);
                                    $event->setTraffic($json_array['Events']['Traffic']);
                                    $event->setEquipment($json_array['Events']['Equipment']);
                                    $event->setWeather($json_array['Events']['Weather']);
                                    $event->setEvaluation($evaluation);
                                    $em->persist($event);

                                    // Fill event list
                                    array_push($eventList, $event);
                                }
                            else
                                {
                                    //double array
                                    foreach($json_array['Events'] as $key => $element)
                                        {
                                            $event = new Event();
                                            $event->setTimezone($json_array['Events'][$key]['TimeZone']);
                                            $event->setStartTime(new \DateTime($json_array['Events'][$key]['StartTime']));
                                            $event->setEndTime(new \DateTime($json_array['Events'][$key]['EndTime']));
                                            $event->setAsmEnvironment($json_array['Events'][$key]['ASMEnvironment']);
                                            $event->setControlTechnology($json_array['Events'][$key]['ControlTechnology']);
                                            $event->setControllerStatus($json_array['Events'][$key]['ControllerStatus']);
                                            $event->setTraffic($json_array['Events'][$key]['Traffic']);
                                            $event->setEquipment($json_array['Events'][$key]['Equipment']);
                                            $event->setWeather($json_array['Events'][$key]['Weather']);
                                            $event->setEvaluation($evaluation);
                                            $em->persist($event);

                                            // Fill event list
                                            array_push($eventList, $event);
                                        }
                                }
                  
                        }

                    $em->flush();

                    /* Get Producer Service */
                    $producer = $this->get('old_sound_rabbit_mq.upload_profile_producer');

                    /* Publish Message */
                    $msg = strval($evaluation->getId());
                    $producer->publish($msg);

                    /* Evaluation's status is now ongogin */
                    $evaluation->setStatus('pending');
                    $em->persist($evaluation);
                    $em->flush();
		
                    /* Get Response */
                    $client = $this->get('old_sound_rabbit_mq.client_rpc');
                    $replies = $client->getReplies();

                    // THIS IS TEST CODE TO TRY THE REQUEST BACK TO NARMS
                    //$request = Request::createFromGlobals();
                    //$request->request->get('requestID', $evaluation->getId());
//                    $request = Request::create(
//                        $evaluation->getResponseURL(),
//                        'POST',
//                       array('responseId' => $evaluation->getId(), 'stress' => '5', 'fatigue' => '3')
//                    );
		
                    return new JsonResponse(array('RequestId' => $evaluation->getId(), 'ResponseURL' => $evaluation->getResponseURL()), 200);
                }
            }
	
        return new JsonResponse(array("Error" => "The data is not in JSON format"), 415);
    }

    public function setProfile(){
        if(!empty($list_request))
            return $list_request;
    	   
    }

    public function checkEvent($event, $eventNbr)
    {
        $ASMEnvironmentArray = array(
            'E', 
            'T',
            'LM',
            'D',
            'A',
            'GM'
        );

        $ControlTechnologyArray = array(
            'R', 
            'PS',
            'PM'
        );

        $ControllerStatusArray = array(
            'SC', 
            'MCU',
            'MCS',
            'MCM',
            'MCT',
            'MCI'
        );

        $TrafficArray = array(
            'VH', 
            'H',
            'B',
            'NB',
            'L',
            'VL'
        );

        $EquipmentArray = array(
            'SD', 
            'BD',
            'D',
            'O'
        );

        $WeatherArray = array(
            'HD', 
            'D',
            'MD',
            'ND'
        );

        $authorizedArray = array(
            0 => array(
                'function' => 'TimeZone',
            ),           
            1 => array(
                'function' => 'StartTime'
            ), 
            2 => array(
                'function' => 'EndTime'
            ),
            3 => array(
                'function' => 'ASMEnvironment',
                'ASMEnvironment' => $ASMEnvironmentArray
            ),
            4 => array(
                'function' => 'ControlTechnology', 
                'ControlTechnology' => $ControlTechnologyArray
            ),
            5 => array(
                'function' => 'ControllerStatus', 
                'ControllerStatus' => $ControllerStatusArray
            ),
            6 => array(
                'function' => 'Traffic', 
                'Traffic' => $TrafficArray
            ),
            7 => array(
                'function' => 'Equipment', 
                'Equipment' => $EquipmentArray
            ),
            8 => array(
                'function' => 'Weather', 
                'Weather' => $WeatherArray
            )
        );

        $pos = 0;
        $attributeArray = "";
        foreach($event as $key => $eventValue)
            { 
                if ($key != $authorizedArray[$pos]['function'])
                    {
                        //check index is correct
                        $attributeArray = $attributeArray."\"[".$key."]\", "; 
                    }
                else
                    {
                        //check content of index
                        if ($key == "TimeZone")
                            {
                                if (!in_array($eventValue, timezone_identifiers_list()))
                                    {
                                        $attributeArray = $attributeArray."\"[".$key."]\", ";
                                    }
                            }
                        else if ( ($key == "StartTime") || ($key == "EndTime") )
                            {
                                if ($this->validateDate($eventValue) == false)
                                    {
                                        $attributeArray = $attributeArray."\"[".$key."]\", ";
                                    }
                            }
                        else
                            {
                                $checkArray = $authorizedArray[$pos][$key];
                                if (!in_array($eventValue, $checkArray))
                                    $attributeArray = $attributeArray."\"[".$key."]\", ";
                            }
                    }
                $pos++;
            }
        
        //if array exist 
        if (!empty($attributeArray))
            {
                if ($this->errorApi["isError"] == false)
                    $this->errorApi["isError"] = true;

                $this->errorApi["Error"] = $this->errorApi["Error"]."[".$eventNbr."]:  ".$attributeArray;
            }
    }
  
    // return true if date is correct or false    
    public function validateDate($date, $format = "Y-m-d H:i:s")
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    public function putManagerAction(Request $request)
    {
    	ini_set('display_errors', 'On');
        error_reporting(E_ALL | E_STRICT);


        $serializer = $this->get('serializer');
        $em = $this->getDoctrine()->getManager();
        $manager_repository = $em->getRepository('ModelBundle:Manager');
        $organization_repository = $em->getRepository('ModelBundle:Organization');
        $normalizer = new ObjectNormalizer(null, new CamelCaseToSnakeCaseNameConverter());  
        
	if($request->getMethod() == 'POST')
        {
		if (!$this->getRequest()->headers->has('Authorization'))
                    return new JsonResponse(array("Error" => "The authorization field is not specified or the API key is not valid"), 401);



		// Get api_key from request and try to find api_key with doctrine
                $authorization = $this->getRequest()->headers->get('Authorization');
                $api_key_b64 = str_replace('Basic ', '', $authorization);
                $api_key = base64_decode($api_key_b64);
                $organization = $organization_repository->findOneBy(array('apiKey' => $api_key));
                if ($organization == null)
                    return new JsonResponse(array("Error" => "The authorization field is not specified or the API key is not valid"), 401);
	
		$data = $this->getRequest()->getContent();
                $json_array = json_decode($data, True); // decode json to array
                if (json_last_error() != JSON_ERROR_NONE)
                     return new JsonResponse(array("Error" => "The data is not in JSON format"), 415);
		
		// Create a new manager field
                $manager = $normalizer->denormalize(array('Name' => $json_array['Name']), 'ModelBundle\Entity\Manager');
                $validator = $this->get('validator');
                $errors = $validator->validate($manager);
		if (count($errors) > 0 || $manager->getName() == "")
                {
                  return new JsonResponse(array("Error" => "The Name is not properly set"), 400);                                   
                }

		$manager->setOrganization($organization);
		$apiKey = $random = substr( md5(rand()), 0, 12);
		$manager->setApiKey($apiKey);
		$manager->setActive(True);
		$em->persist($manager);
                $em->flush();

		return new JsonResponse(array('ManagerId' => $manager->getId(), 'Name' => $manager->getName(), 'ApiKey' => base64_encode($manager->getApiKey())), 200);
	}

	return new Response('Api is loaded. Route : /organizations/managers');    
    }

    public function deleteManagerAction(Request $request, $manid)
    {
        $em = $this->getDoctrine()->getManager();
        $manger_repository = $em->getRepository('ModelBundle:Manager');
        
        if($request->getMethod() == 'DELETE')
            {
                $authorization = $this->getRequest()->headers->get('Authorization');
                $api_key_b64 = str_replace('Basic','',$authorization);
                $api_key = base64_decode($api_key_b64);
                $manager = $manager_repository->findOneBy(array('apiKey' => $api_key, 'id'=> $manid));

                if($manager == null)
                    {
                        return new JsonResponse(array("Error" => "The authorization field is not specified or the API key is not valid"),401);
                        
                    }
                $manager->setActive(0);
                $em->persist($manager);
		
                return new JsonResponse(array(), 204);
            }
      
        return new Response('Api is loaded. Route: /organizations/managers/{manid}');
    }

    public function getInvoicesAction(Request $request)
    {
        if($request->getMethod() == 'GET')
            {
                return new JsonResponse(array('OrganizationName' => 'ATCO', 'Invoice' => array("Date" => "2016-02-01 12:12:12", "PeriodStart" => "2016-02-01 12:12:12", "PeriodEnd" => "2016-02-01 12:12:12", "Amount" => 125, "Currency" => "test")), 200);
            } 
     
        return new Response('Api is loaded. Route: /organizations/invoices');
    }

    public function putInvoicesAction(Request $request, $orgaid)
    {
        if($request->getMethod() == 'POST')
            {
                return new JsonResponse(array('OrganizationName' => 'ATCO', 'Invoice' => array("Date" => "2016-02-01 12:12:12", "PeriodStart" => "2016-02-01 12:12:12", "PeriodEnd" => "2016-02-01 12:12:12", "Amount" => 125, "Currency" => "test")), 200);
            } 
      
        return new Response('Api is loaded. Route : /organizations/{orgaid}/invoices');    
    }

}
