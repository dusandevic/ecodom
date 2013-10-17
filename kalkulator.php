<?php

class Kalkulator {
	
	//arrays initialisation
	private $dobro = array(); //good isolation matrix
	private $prosecno = array(); //average isolation matrix
	private $lose = array(); //bad isolation matrix
	
	function __construct() {
		//constructor for calculation matrix
      	 for($i=1; $i<=100; $i++){
			$this->dobro[$i][0] = $i*40;
			$this->dobro[$i][1] = $i*50;
			$this->dobro[$i][2] = $i*60;
			$this->dobro[$i][3] = $i*70;
			$this->dobro[$i][4] = $i*80;
			
			$this->prosecno[$i][0] = $i*40;
			$this->prosecno[$i][1] = $i*70;
			$this->prosecno[$i][2] = $i*80;
			$this->prosecno[$i][3] = $i*90;
			$this->prosecno[$i][4] = $i*100;

			$this->lose[$i][0] = $i*40;
			$this->lose[$i][1] = $i*90;
			$this->lose[$i][2] = $i*100;
			$this->lose[$i][3] = $i*110;
			$this->lose[$i][4] = $i*120;			
		 }
    }
	//getter
	public function getDobro(){
		return $this->dobro;
	}
	//getter
	public function getProsecno(){
		return $this->prosecno;
	}
	//getter
	public function getLose(){
		return $this->lose;
	}
	//getter for matrix value, accepts matrix, area and number of external walls
	public function getMatrixValue($matrix, $m2, $brSpoljnihZidova){
		if($matrix == 'dobro'){
			$myMatrix = $this->getDobro();
		}
		
		if($matrix == 'prosecno'){
			$myMatrix = $this->getProsecno();
		}
		
		if($matrix == 'lose'){
			$myMatrix = $this->getLose();
		}		
		$m2=intval($m2);
		$brSpoljnihZidova = intval($brSpoljnihZidova);
			
		return $myMatrix[$m2][$brSpoljnihZidova];				
	}
}

$m = array();
$h = array();
$z = array();

//this is for area
$m[1] = intval($_POST['m1']);
$m[2] = intval($_POST['m2']);
$m[3] = intval($_POST['m3']);
$m[4] = intval($_POST['m4']);
$m[5] = intval($_POST['m5']);
$m[6] = intval($_POST['m6']);
$m[7] = intval($_POST['m7']);
$m[8] = intval($_POST['m8']);

//$h array is used in cost calculation
$h[1] = doubleval($_POST['h1']);
$h[2] = doubleval($_POST['h2']);
$h[3] = doubleval($_POST['h3']);
$h[4] = doubleval($_POST['h4']);
$h[5] = doubleval($_POST['h5']);
$h[6] = doubleval($_POST['h6']);
$h[7] = doubleval($_POST['h7']);
$h[8] = doubleval($_POST['h8']);

//this is for external walls
$z[1] = intval($_POST['z1']);
$z[2] = intval($_POST['z2']);
$z[3] = intval($_POST['z3']);
$z[4] = intval($_POST['z4']);
$z[5] = intval($_POST['z5']);
$z[6] = intval($_POST['z6']);
$z[7] = intval($_POST['z7']);
$z[8] = intval($_POST['z8']);

//get price input
$price = $_POST['price'];

$kalkulator = new Kalkulator; //create matrix

//init result variable
$rez = array();
$rez['dobro']['sum'] = 0;
$rez['prosecno']['sum'] = 0;
$rez['lose']['sum'] = 0;
$rez['dobro']['sum_trosak'] = 0;
$rez['prosecno']['sum_trosak'] = 0;
$rez['lose']['sum_trosak'] = 0;



for($i=1; $i<=8; $i++){ //for each room
	if($m[$i] > 0){
		$rez['dobro'][$i] = $kalkulator->getMatrixValue('dobro', $m[$i], $z[$i]);
		$rez['dobro']['sum'] = $rez['dobro']['sum'] + $rez['dobro'][$i]; //++ sum
		$rez['dobro']['trosak'][$i] = $rez['dobro'][$i] / 1000 * $h[$i] * $price;
		$rez['dobro']['sum_trosak'] = $rez['dobro']['sum_trosak'] + $rez['dobro']['trosak'][$i];
	
		
		$rez['prosecno'][$i] = $kalkulator->getMatrixValue('prosecno', $m[$i], $z[$i]);
		$rez['prosecno']['sum'] = $rez['prosecno']['sum'] + $rez['prosecno'][$i]; //++ sum
		$rez['prosecno']['trosak'][$i] = $rez['prosecno'][$i] / 1000 * $h[$i] * $price;
		$rez['prosecno']['sum_trosak'] = $rez['prosecno']['sum_trosak'] + $rez['prosecno']['trosak'][$i];
		
		
		$rez['lose'][$i] = $kalkulator->getMatrixValue('lose', $m[$i], $z[$i]);
		$rez['lose']['sum'] = $rez['lose']['sum'] + $rez['lose'][$i]; //++ sum
		$rez['lose']['trosak'][$i] = $rez['lose'][$i] / 1000 * $h[$i] * $price;
		$rez['lose']['sum_trosak'] = $rez['lose']['sum_trosak'] + $rez['lose']['trosak'][$i];	
	}
}

$sumaKvadrata = 0;
foreach ($m as $mVal){
	$sumaKvadrata = $sumaKvadrata + $mVal; //for total building area
}

$rez['kvadratura'] = $sumaKvadrata;

echo json_encode($rez); //return JSON to client
