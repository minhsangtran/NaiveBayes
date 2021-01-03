<?php

class NaiveBayesGaussian {

	// private $fileName;
	// private $splitRatio;
	// private $dataSet;
	// private $dataTraining;
	// private $dataTesting;
	
	// static function __construct($file, $ratio) {
	// 	$this->fileName = $file;
	// 	$this->splitRatio = $ratio;

	// 	$dataSet = load_data($file, $ratio);
	// 	$data = split_data($dataSet, $ratio);
	// 	$this->dataTraining = $data['training'];
	// 	$this->dataTesting = $data['testing'];
	// }

	public static function load_data($fileName = "heart.csv") {
		$file = fopen($fileName, "r");
		$dataSet = [];

		while(! feof($file))
		  {
		  	$dataSet[] = fgetcsv($file);
		  }

		unset($dataSet[count($dataSet)-1]);
		fclose($file);

		return $dataSet;
	}

	static function split_data($dataSet, $splitRatio) {
		$trainSize = (int)(count($dataSet) * $splitRatio);
		$data = [];
		
		for($i = 0; $i < count($dataSet); $i++) {
			if($i < $trainSize) {
				$data['training'][] = $dataSet[$i]; 
			}
			else {
				$data['testing'][] = $dataSet[$i]; 
			}
		}

		return $data;
	}

	// tính giá trị trung bình cho attribute - truyền vào list value của attribute
	static function avg_value_for_attribute($listValueOfAttr) {
		return array_sum($listValueOfAttr) / count($listValueOfAttr);
	}

	// tính độ lệch chuẩn cho attribute - truyền vào list value của attribute
	static function standard_deviation($listValueOfAttr) {
		// tính phương sai
		$variance = 0;
		$avg = self::avg_value_for_attribute($listValueOfAttr);

		foreach($listValueOfAttr as $value) {
			$variance += ($value - $avg)*($value - $avg);
		}

		$variance = $variance / (count($listValueOfAttr) - 1);
		$standard_deviation = sqrt($variance);

		return $standard_deviation;
	}

	static function mapping_vector($dataSetByClass) {
		// đếm số cột của data
		$countAttribute = count($dataSetByClass[0]);
		$listValueOfAttr = [];
		$mapping_vector = [];

		// tạo mảng các list value theo từng cột(attribute)
		foreach ($dataSetByClass as $row) {

			for ($i = 0; $i < $countAttribute - 1; $i++) {
				$listValueOfAttr[$i][] = $row[$i];
			}		
		}

		for($j=0; $j<$countAttribute-1; $j++) {
			$avgAttr = self::avg_value_for_attribute($listValueOfAttr[$j]);
			$standard_deviation_attr = self::standard_deviation($listValueOfAttr[$j]);
			$mapping_vector[] =  [$avgAttr, $standard_deviation_attr]; // vector 2 chiều (trung bình, độ lệch chuẩn);
		}
		//var_dump($mapping_vector);
		return $mapping_vector;
	}

	static function separate_data_by_class($dataSet) {
		$dataByClass = [];
		foreach($dataSet as $row) {
			$dataByClass[$row[count($row) - 1]][] = $row;
		}

		return $dataByClass;
	}


	static function maping_vector_by_class($dataSet) {
		$dataByClass = self::separate_data_by_class($dataSet);

		foreach ($dataByClass as $key => $value) {
			$listVector = self::mapping_vector($value);
			foreach($listVector as $vector) {
				$dataByClassMapping[$key][] = $vector;
			}
		}
		//var_dump($dataByClassMapping);
		return $dataByClassMapping;
	}

	// Tính xác suất giá trị ngẫu nhiên X
	static function calculate_prob_random_value($x, $avg, $standard_deviation) {
		$expX = exp(-(pow(($x - $avg), 2) / (2*pow($standard_deviation, 2))));
		$P = (1 / (sqrt(2*pi()) * $standard_deviation)) * $expX; // xác suất cho X

		return $P;
	}

	// Tinh xac suat cho moi thuoc tinh phan chia theo class, sau đó lấy tích các xác suất => xác xuất phân chia theo class
	static function calculate_prob_by_class($dataByClassMapping, $inputRowData) {
		$prob = [];

		foreach ($dataByClassMapping as $className => $vector) {
			$probTemp = 1; // xác suất lưu tạm
			$countAttr = count($vector);

			for($i=0; $i<$countAttr; $i++) {
				$avg = $vector[$i][0];
				$standard_deviation = $vector[$i][1];
				$probTemp *= self::calculate_prob_random_value($inputRowData[$i], $avg, $standard_deviation);
			}

			$prob[] = [$className, $probTemp];
		}

		return $prob;
	}

	// Du doan inputRow thuoc phan lop nao
	static function predict($dataByClassMapping, $inputRowData) {
		$probByClass = self::calculate_prob_by_class($dataByClassMapping, $inputRowData);
		$className = "";
		$bestProb = -1;

		foreach ($probByClass as $item) {
			if((empty($className) && $className != 0)|| $item[1] > $bestProb) {
				$className = $item[0];
				$bestProb = $item[1];
			}
		}

		return $className;
	}


	// Du doan dataSet testing thuoc lop nao
	static function get_predict_datatesting($dataByClassMapping, $dataTesting) {
		$predictions = [];

		foreach ($dataTesting as $row) {
			$probRow = self::predict($dataByClassMapping, $row);
			$predictions[] = $probRow;
		}

		return $predictions;
	}


	static function calculate_accuracy($dataTesting, $predictions) {
		$correct = 0;
		$countAttr = count($dataTesting[0]);

		for($i=0; $i<count($dataTesting); $i++) {
			if($dataTesting[$i][$countAttr-1] == $predictions[$i]) {
				$correct++;
			}
		}

		return $correct/count($dataTesting) * 100;
	}

	static function match_data_result($dataTesting, $predictions) {
		for($i=0; $i<count($dataTesting); $i++) {
			$dataTesting[$i][] = $predictions[$i];
		}
		
		return $dataTesting;
	}

}