<?php
namespace ApiBundle\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\HttpFoundation\Request;
use DateTime;

use ModelBundle\Entity\Result;

class UploadProfileConsumer implements ConsumerInterface {

    private $container;
    private $em;
    
    public function __construct($container)
    {
        $this->container = $container;
        $this->em = $this->container->get('doctrine')->getManager();
    }
    
    public function execute(AMQPMessage $msg) {
        if (!is_numeric($msg->body))
	     	return;

        $id = intval($msg->body);

        // Mark: - Access to the Event
        
        $event_repository = $this->em->getRepository('ModelBundle:Event');
        $eventList = $event_repository->findBy(array('evaluation' => $id));

        // Mark: - Run the algorithm
        
        $sfList = $this->container->get('safaps_algorithm')->run($eventList);

        // Mark: - Add SAFAPS result in the database
        
        $algoResult = new Result();
        $algoResult->setStress($sfList['stress']);
        $algoResult->setFatigue($sfList['fatigue']);
        $this->em->persist($algoResult);
        $this->em->flush();

        // Mark: - Add SAFAPS status, date and idResult in the database
        
        $eval_repo = $this->em->getRepository('ModelBundle:Evaluation');
        $evaluation = $eval_repo->findOneBy(array('id' => $id));
        $evaluation->setStatus('done');
        $evaluation->setDateCompleted(new DateTime());
        $evaluation->setResult($algoResult);
        $this->em->persist($evaluation);
        $this->em->flush();

        // Mark: - Request to NARMS
        
        $url = $evaluation->getResponseURL();
        $data = array('id_evaluation' => $evaluation->getId(), 'stress' => '5', 'fatigue' => '3');
        $data_string = json_encode($data);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))
        );

        $response = curl_exec($ch);
        
        echo $response;
        if (curl_errno($ch))
            {
                \Doctrine\Common\Util\Debug::dump(curl_error($ch));
            }

        curl_close($ch);

        return $msg->body;
    }
}