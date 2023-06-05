<?php if($_GET['id'] != '') { ?>
<a href="javascript:void(0)">
	<img src="assets/images/go-back.png" width="5%" onclick="back()">
</a>
<?php } else { ?>
<a class='btn btn-warning' style='margin-bottom: 0%;' data-toggle='collapse' data-target='#demo'>Filter</a>
 	<?php 
 	if ($_GET['halreload'] == 1) {
 	  	require( '../../../funlibs.php' );
	  	$con=new Database;
	  	session_start();

	  	if ($_GET['cm'] != ''){
		  $cmt = $con->searchseqcmt($_GET['cm']);
		  $ncmt = $cmt;
		} else {
		  $ncmt = '';
		}
		if ($_GET['co'] != ''){
		  $colors = $con->searchseq($_GET['co']);
		  $ncol = $colors;
		} else {
		  $ncol = '';
		}

		if ($_GET['xty'] != ''){
		  $xftydate = $_GET['xty'];
		  $xtyd = $xftydate;
		} else {
		  $xtyd = '';
		}
		?>
		<script type="text/javascript">
			$(function() {  
				$("#nocmt").autocomplete({
				 	source: function(request, response) {
					    $.getJSON(
					        "apps/View/ViewData/sourcedata.php",
					        { d:'1', term:request.term}, 
					        response
					    );
					},
				    minLength:2, 
				    select: function (event, ui) {
				    	if (ui.item != undefined) {
				        	$(this).val(ui.item.value);	
				    	} 
				    	return false;
					}
				});
			});

			$(function() {  
				$("#nocolor").autocomplete({
				   //source: "apps/Transaction/Sequence/sourcedata.php?d=4&color="+$('#buyer_no').val(),  
				    source: function(request, response) {
					    $.getJSON(
					        "apps/View/ViewData/sourcedata.php",
					        { d:'2', term:request.term, scmt:$('#nocmt').val()}, 
					        response
					    );
					},
				    minLength:0, 
				    select: function (event, ui) {
				    	if (ui.item != undefined) {
				        	$(this).val(ui.item.value);	
				    	} 
				    	return false;
					}
				}).focus(function () {
				   $(this).autocomplete("search", "");
				});        
			});

			$(function() {  
				    	
				        $("#exftydate").autocomplete({
				         	source: function(request, response) {
					            $.getJSON(
					                "apps/View/ViewData/sourcedata.php",
					                { d:'3', term:request.term, scmt:$('#nocmt').val(), scolor:$('#nocolor').val()}, 
					                response
					            );
					        }, 
				           	minLength:0, 
				           	select: function (event, ui) {
				            if (ui.item != undefined) {
				                $(this).val(ui.item.value);	
				            	} 
				            	return false;
			        		}
				        }).focus(function () {
						    $(this).autocomplete("search", "");
					});
			});

			
		</script>
	<?php  
 	}
 		if ($_GET['cm'] != '' || $_GET['co'] != '' || $_GET['xty'] != ''){ ?>
 		<script type="text/javascript">pindahData2($('#nocmt').val(),$('#nocolor').val(),$('#exftydate').val(),1);</script>
        <div id="demo" class="collapse in" >
     <?php } else { ?>
        <div id="demo" class="collapse in" >
     <?php } ?>
          <hr>
          <div class="form-group">
            <label class="col-md-2 control-label" for="profileLastName"><b>WO No <font style="color:#ff0000;">*</font></b></label>
            <div class="col-md-4">
                <input type="text" name="nocmt" id="nocmt" class="form-control" value="<?php echo $ncmt; ?>" required>
                <!-- <input type="text" name="idcmt" id="idcmt" class="form-control"> -->
            </div>
            <label class="col-md-2 control-label" for="profileLastName"><b>Colors <font style="color:#ff0000;">*</font></b></label>
            <div class="col-md-4">
                <input type="text" name="nocolor" id="nocolor" class="form-control" value="<?php echo $ncol; ?>" required>
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-2 control-label" for="profileLastName"><b>Ex fty Date <font style="color:#ff0000;">*</font></b></label>
            <div class="col-md-4">
                <input type="text" name="exftydate" id="exftydate" class="form-control" value="<?php echo $xtyd; ?>" required>
            </div>
           
          </div>
          <div class="form-group">
              <div class="col-md-12">
                <a href="content.php?option=View&task=history&act=ugr_view" style="height:30px; line-height: 0;float: right; padding: 2%;" type="button" class="btn btn-default">Reset</a>
                <input style="height:30px; line-height: 0;float: right; padding: 2%;" type="button" class="btn btn-info" value="Search" onclick="pindahData2(nocmt.value,nocolor.value,exftydate.value,1)" >
                
              </div>
          </div>
        </div>

<?php } ?>
