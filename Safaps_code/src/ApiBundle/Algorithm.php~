<?php

namespace ApiBundle;

class Algorithm {

      public function run($eventList) {
      	     $eventsNumber = sizeOf($eventList);
	     $freeTimeInSec = $this->calc_free_time($eventList, $eventsNumber);
      	     $workTimeInSec = $this->calc_work_time($eventList);

	     /* Debug */
	     //$this->debug_general($eventsNumber, $workTimeInSec, $freeTimeInSec);

	     $sfList = array('stress' => '1', 'fatigue' => '2');

	     return ($sfList);
      }

      private function calc_free_time($eventList, $eventsNumber) {
      	      $freeTimeInSec = 0;
	      
      	      // No Free Time
      	      if ($eventsNumber == 1)
	      	 return $freeTimeInSec;

	      $id = 0;

	      while ($id < ($eventsNumber - 1)) {
	      	    $endTimeEvent1 = $eventList[$id]->getEndTime();
		    $startTimeEvent2 = $eventList[$id + 1]->getStartTime();
		    $freeTimeSecDiff = $this->calc_time_difference($startTimeEvent2, $endTimeEvent1);

		    $freeTimeInSec += $freeTimeSecDiff;

		    /* Debug */
		    //$this->debug_free_time($endTimeEvent1, $startTimeEvent2, $freeTimeSecDiff);

		    $id++;
	      }

	      return $freeTimeInSec;
      }

      private function calc_work_time($eventList) {
      	     $workTimeInSec = 0;
	     
      	     foreach ($eventList as $event) {
             	      $startTime = $event->getStartTime();
		      $endTime = $event->getEndTime();
	              $workTimeSecDiff = $this->calc_time_difference($startTime, $endTime);

		      $workTimeInSec += $workTimeSecDiff;

		      /* Debug */
		      //$this->debug_work_time($startTime, $endTime, $workTimeSecDiff);
	     }

	     return $workTimeInSec;
      }

      private function calc_time_difference($date1, $date2) {
      	      $ts1 = strtotime(date_format($date1, "Y-m-d H:i:s"));
              $ts2 = strtotime(date_format($date2, "Y-m-d H:i:s"));

              $seconds_diff = $ts2 - $ts1;

	      return $seconds_diff;
      }

      private function debug_work_time($date1, $date2, $seconds_diff){
      	      /* Start Time debug */
      	      echo "Start Time :";
              echo date_format($date1, "Y-m-d H:i:s");
	      echo "<br>";

      	      /* End Time debuf */
              echo "End Time :";
	      echo date_format($date2, "Y-m-d H:i:s");
	      echo "<br>";

      	      /* Debug Second Diff */
      	      echo "Seconds : ";
      	      echo $seconds_diff;
	      echo "<br>";

	      echo "<br>";
      }

      private function debug_free_time($date1, $date2, $seconds_diff){
      	      /* End Time Event debug */
	      echo "End Time Event 1 :";
	      echo date_format($date1, "Y-m-d H:i:s");
	      echo "<br>";

	      /* Start Time Event debug */
	      echo "Start Time Event 2";
	      echo date_format($date2, "Y-m-d H:i:s");
	      echo "<br>";

	      /* Debug Second Diff */
	      echo "Seconds : ";
	      echo $seconds_diff;
	      echo "</br>";

	      echo "</br>";
      }


      private function debug_general($eventsNumber, $workTimeInSec, $freeTimeInSec) {
             /* Debug General */
	     echo "<br> Debug General <br>";
	     echo "Events Number :";
	     echo $eventsNumber;
	     echo "<br>";

             echo "Work Time :";
	     echo $workTimeInSec;
	     echo "<br>";

             echo "Free Time :";
	     echo $freeTimeInSec;
	     echo "<br>";

	     echo "<br> ALGO END <br>";
      }
       
}

