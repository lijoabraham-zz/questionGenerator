<?php

class QuestionPaperGenerator{

	public $fileName = './questions.csv';
	public $heading = array('question','subject','topic','difficulty','marks');
	public $diffPercentage = array('easy' => 20,'medium' => 50,'hard' => 30);

	public function getCsvdetails(){
		$csvFile = file($this->fileName);
		$all_rows = [];
		foreach ($csvFile as $line) {
			$all_rows[] = array_combine($this->heading, str_getcsv($line));			
		}		
		return $all_rows;		
	}

	public function checkConditions($questions){
		//echo "<pre>";print_r($questions);
		foreach ($questions as $key => $value) {
			$difficultyWiseData[$value['difficulty']][]= $value;
			//$allMarks[$value['difficulty']][] = $value['marks'];
		}
		//echo "<pre>";print_r($difficultyWiseData);die;
		foreach ($difficultyWiseData as $key => $value) {
			switch ($key) {
				case 'easy':
					$marks = array_column($value, 'marks');			
					array_multisort($marks, SORT_ASC, $value);
					$indexes = $this->checkMarks($marks,$this->diffPercentage[$key]);
					if(empty($indexes)){
						echo "Allocation issue in Easy Questions";die;
					}
					$finalQuestions['easy'] = array_slice($value,$indexes[0],$indexes[1]);
					//echo "<pre>";print_r($indexes);die;
					break;
				case 'medium':
					$marks = array_column($value, 'marks');			
					array_multisort($marks, SORT_ASC, $value);
					$indexes = $this->checkMarks($marks,$this->diffPercentage[$key]);
					if(empty($indexes)){
						echo "Allocation issue in Medium Questions";die;
					}
					$finalQuestions['medium'] = array_slice($value,$indexes[0],$indexes[1]);
					//echo "<pre>";print_r($indexes);die;
					break;
				case 'hard':
					$marks = array_column($value, 'marks');			
					array_multisort($marks, SORT_ASC, $value);
					$indexes = $this->checkMarks($marks,$this->diffPercentage[$key]);
					if(empty($indexes)){
						echo "Allocation issue in Hard Questions";die;
					}
					$finalQuestions['hard'] = array_slice($value,$indexes[0],$indexes[1]);
					//echo "<pre>";print_r($indexes);die;
					break;
				
				default:
					# code...
					break;
			}			
		}		
		//echo "<pre>";print_r($finalQuestions);die;				
		return $finalQuestions;
	}	

	public function checkMarks($arr, $total) {
	    $maxMarks = $arr[0];  
	    $start = 0; $i; 
	  	$res = array();
	    for ($i = 1; $i <= count($arr); $i++) 
	    { 
	        while ($maxMarks > $total and 
	               $start < $i - 1) 
	        { 
	            $maxMarks = $maxMarks -  
	                        $arr[$start]; 
	            $start++; 
	        } 
	  
	        if ($maxMarks == $total) 
	        { 
	            $res = array($start,($i - $start)); 
	        } 
	        if ($i < count($arr)) 
	        $maxMarks = $maxMarks + $arr[$i]; 
	    } 
	    return $res; 
	} 

	public function generateQuestions(){

		$csvDetails = $this->getCsvdetails();
		$questions = $this->checkConditions($csvDetails);	
		return $questions;
	}	

}

$qspGen = new QuestionPaperGenerator();
$finalQuestions = $qspGen->generateQuestions();
foreach ($finalQuestions as $key => $value) {
	echo "<br><div>".strtoupper($key)."</div><br>";	
	echo "<table border='1' ><thead>";

	foreach ($qspGen->heading as $k => $v) {
		echo "<th>".ucwords($v)."</th>";
	}
	echo "</thead>";
	echo "<tbody>";
	//echo "<pre>";print_r($value);die;
	foreach ($value as $k1 => $v1) {
		echo "<tr>";
		foreach ($v1 as $k2 => $v2) {
			echo "<td>".$v2."</td>";
		}
		echo "</th>";
	}
	echo "</tbody>";
	echo "</table>";
}
