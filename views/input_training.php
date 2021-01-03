<?php
    if(isset($_GET['ratio'])){
        if(!empty($_GET['ratio'])){
            $ratio=$_GET['ratio'];
			$fileName = "heart.csv";

			$dataSet = NaiveBayesGaussian::load_data($fileName);
			$data = NaiveBayesGaussian::split_data($dataSet, $ratio);

			$dataTraining = $data['training'];
			$dataTesting = $data['testing'];

			$dataByClassMapping = NaiveBayesGaussian::maping_vector_by_class($dataTraining);
			$predictions = NaiveBayesGaussian::get_predict_datatesting($dataByClassMapping, $dataTesting);
			$accuracy = NaiveBayesGaussian::calculate_accuracy($dataTesting, $predictions);

			$matchResult =  NaiveBayesGaussian::match_data_result($dataTesting, $predictions);
        }
    }
?>

<div class="header_input" style="height: 100px">
    <div id="div_request">
        <strong>RATIO (range 0.5 ... 0.9)</strong>&nbsp&nbsp
        <input type="number" name="ratio" min="0.5" max="0.9" value = "<?php if(!empty($ratio)) echo $ratio; else echo 0.8?>" placeholder="0.5 -> 0.9 please" style="font-size: 1.3em; padding: 10px; width: 300px">
        &nbsp&nbsp
        <input type="button" class="button primary" id="training" value="Training">
        <span style="float: right; padding: 20px"><?php echo "Data size =" .count($dataSet) . "|| Training Size=".count($dataTraining) ." || Test Size=" .count($dataTesting); ?></span>
    </div>
</div>