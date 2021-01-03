

    <div class="main_contents">
        <div class="main_contents_header">Result   <?php if(isset($accuracy)) echo "<span style='float:right'>ACCURACY: ".$accuracy ."%</span>";?></div>
        <hr/>

        <div id="div_request">
			<table class="table_match_result" border="1" width="100%">

	        <?php 
	        if(isset($matchResult) && !empty($matchResult)) {
	        	$countAttr = count($matchResult[0]); 

		        	foreach($matchResult as $row) {
		        		echo "<tr>";

		        		for($i=0; $i<$countAttr; $i++) {
					  		if($i == $countAttr - 2) {
					  			echo "<th style='background: #00ddff'>".$row[$i] ."</th>";
					  			continue;
					  		}

					  		if($i == $countAttr - 1) {
					  			if($row[$i] == $row[$i-1]) 
						  			echo "<th style='background: #00ddff'>".$row[$i] ."</th>";
					  			else
					  				echo "<th style='background: red'>".$row[$i] ."</th>";
					  			continue;
					  		}

					  		echo "<th>".$row[$i] ."</th>";
		        		}
		        		echo "</tr>";
		        	}
				}
			?>
			  
			</table>
        </div>
    </div>