<link rel="stylesheet" href="css/style.css">
<script language="javascript" src="css/jquery-3.3.1.min.js"></script>

<div class="main">
	<?php require_once "NaiveBayesGaussian.php"; ?>

	<?php include_once "views/input_training.php" ?>

	<?php include_once "views/testing.php" ?>

	<?php include_once "views/result.php" ?>

</div>


<script>

 
    $("#training").click(function(){
        var ratio = $('[name="ratio"]').val();

        if(ratio != ""){
	        if(ratio < 0.5 || ratio >0.9) {
	        	alert("ratio range: 0.5 ... 0.9  please");
	        }
	        else{
    			var url= window.location.href;
				var myRe = new RegExp(".*?NaiveBayesGaussian", "g");
				url = myRe.exec(url);
				alert(url);
	            url += '/index.php?ratio='+ratio;
	            window.location.href=url;	        	
	        }
        }       
    });


</script>
