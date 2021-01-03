   <?php
	if(isset($_GET['count_attr'])) {
        if(!empty($_GET['count_attr'])){
            $count_attr=$_GET['count_attr'];
            $attrValueList = [];

            for($j=0; $j<$count_attr; $j++) {
            	$temp = "attr" . $j;
            	$attrValueList[] = $_GET[$temp];
            }
            
            $isClass = (string)NaiveBayesGaussian::predict($dataByClassMapping, $attrValueList);

            echo "<script> alert('Dự đoán sẽ thuộc lớp: ".$isClass." '); </script>";
        }
	}
   ?>

    <div class="main_contents">
        <div class="main_contents_header">Nhập Giá Trị Để Test Phân Lớp Trực Tiếp</div>
        <hr>
        <div id="div_response">
        	<table border="1" width="100%">
	        <?php 
		        if(isset($matchResult) && !empty($matchResult)) {
		        	$countAttr = count($matchResult[0]) - 2; 
		        	for($j=0; $j<2; $j++) {
		        		echo "<tr>";
		        		for($i=0; $i<$countAttr; $i++) {
							if($j==1)
								echo "<th><input style='width:100%' type='number' class='valueAttr'></th>";
							else
								echo "<th>"."attribute".$i."</th>";
		        		}
		        		echo "</tr>";
		        	}
				}		        	    
	        	?>    		
        	</table>
			<input type="button" style="margin: 1% 40% " class="button primary" id="selectClass" value="Dự Đoán Lớp">

			<div id='isClass' style="margin: 1% 40%">
				<p style="background: green; font-size: 1.3em"><?php if($isClass == 0 || !empty($isClass)) echo "Class:  " .$isClass; ?></p>
			</div>
        </div>
    </div>



    <script>

 
    $("#selectClass").click(function(){

		var url= window.location.href;
		var myRe = new RegExp(".*?0\.[0-9]", "g");
		var url = myRe.exec(url);
		var checkNull = false;
		var i = 0;

		$( ".valueAttr" ).each(function( index ) {
			if($(this).val() == "") {
				alert('không để trống');
				checkNull = true;
				return false;
			}
			else {
				url += "&attr"+i+"="+$(this).val();
			}
			i++;
		});

		if(checkNull) return false;

		url += "&count_attr="+ i;
		window.location.href=url;

    });


	</script>