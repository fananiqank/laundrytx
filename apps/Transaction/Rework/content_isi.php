<?php
  session_start();
  require_once("../../../funlibs.php");
  $con=new Database();

foreach ($selectuser = $con->select("m_users a join laundry_user_section b on a.user_id=b.user_id","a.user_id,username,b.master_type_process_id","a.user_id = '$_SESSION[ID_LOGIN]'") as $user){}
?>
<div class="form-group">
        <div class="form-group">
            <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><font style="color:#ff0000;">*</font><b>WO No</b></label>
            <div class="col-md-4">
                <input type="text" name="wo_no_show" id="wo_no_show" class="form-control" value="">
                <input type="hidden" name="wo_no" id="wo_no" class="form-control" value="">
            </div>
            <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><font style="color:#ff0000;">*</font><b>Colors</b></label>
            <div class="col-md-4">
                <input type="text" name="color_no_show" id="color_no_show" class="form-control" value="">
                <input type="hidden" name="color_no" id="color_no" class="form-control" value="">
            </div>
        </div>
         <div class="form-group">
            <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><font style="color:#ff0000;">*</font><b>Ex Fty Date</b></label>
            <div class="col-md-4">
                <input type="text" name="ex_fty_date" id="ex_fty_date" class="form-control" value="">
                <input type="hidden" name="ex_fty_date_asli" id="ex_fty_date_asli" class="form-control" value="">
            </div>
            <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><font style="color:#ff0000;">*</font><b>User</b></label>
            <div class="col-md-4">
                <input type="text" name="usercode" id="usercode" class="form-control" value="">
            </div>
        </div>
      
        <div class="form-group">
            <div class="col-sm-12 col-md-12 col-lg-12">
              <a href="content.php?p=<?=$_GET[p]?>" style="height:30px; line-height: 0;float: right; padding: 2%;" type="button" class="btn btn-default">Reset</a>
                <input style="height:30px; line-height: 0;float: right; padding: 2%;" type="button" class="btn btn-info" value="Search" onClick="pindahData3(wo_no.value,color_no.value,ex_fty_date_asli.value,usercode.value)">
            </div>
        </div>
  
</div>

<div class="form-group" id="contentwip">
    <h4>Lot Number Rework Not In Process</h4>
    <div class="col-sm-12 col-md-12 col-lg-12" style="background-color: #FFFFFF;">
      <div class="col-md-7 pre-scrollable" style="height: 200px;">
          <h5><b>Choose Lot </b> &nbsp;<span style="float: right"><input type="checkbox" value="1" onClick="cekswip(this)" id="checkwip"> All &emsp;</span></h5>
          <div id="tampilwiprec"></div>
      </div>
      <div class="col-md-5" align="center" style="margin-top: 5%;">
          <a href="javascript:void(0)" class="btn btn-primary" style="padding:5%;font-size: 16px;vertical-align: middle;" onclick="receiverework()">Receive</a>
      </div>
    </div>
</div>
<input type="hidden" name="ceklot" id="ceklot" value="">

<script type="text/javascript">
  if($('#stat').val() == 'process'){
    $('#lot_no').focus();
  }

  $(function() {  
    $("#wo_no_show").autocomplete({
      source: function(request, response) {
          $.getJSON(
              "apps/Transaction/Rework/sourcedata.php",
              { d:'1', term:request.term}, 
              response
          );
      },
        minLength:2, 
        select: function (event, ui) {
          if (ui.item != undefined) {
              $(this).val(ui.item.value); 
              $('#wo_no').val(ui.item.woasli);
          } 
          return false;
      }
    });
  });
  
  $(function() {  
    $("#color_no_show").autocomplete({
       //source: "apps/Transaction/Sequence/sourcedata.php?d=4&color="+$('#buyer_no').val(),  
       source: function(request, response) {
          $.getJSON(
              "apps/Transaction/Rework/sourcedata.php",
              { d:'4', term:request.term, scmt:$('#wo_no_show').val()}, 
              response
          );
      },
        minLength:0, 
        select: function (event, ui) {
          if (ui.item != undefined) {
              $(this).val(ui.item.value); 
              $('#color_no').val(ui.item.colorasli);
          } 
          return false;
      }
    }).focus(function () {
       $(this).autocomplete("search", "");
    });        
  });

  $(function() {  
            $("#ex_fty_date").autocomplete({
              source: function(request, response) {
                  $.getJSON(
                      "apps/Transaction/Rework/sourcedata.php",
                      { d:'5', term:request.term, scmt:$('#wo_no_show').val(), scolor:$('#color_no_show').val()}, 
                      response
                  );
              }, 
                minLength:0, 
                select: function (event, ui) {
                if (ui.item != undefined) {
                    $(this).val(ui.item.value); 
                    $('#ex_fty_date_asli').val(ui.item.exftydateasli);  
                  } 
                  return false;
              }
            }).focus(function () {
            $(this).autocomplete("search", "");
      });        
  });

  function pindahData3(cmt,color,exdate,usercode){
    
    //CMT
    trimcmt = cmt.trim();
    expcmt = trimcmt.split('/');
    cmt = expcmt.join('-');

    //Color
    trimcolor = color.trim();
    expcolor = trimcolor.split('#');
      if (expcolor.length > 1) {
        expcolor1 = expcolor.join('K-');
      } else {
        expcolor1 = trimcolor;
      }
    expcolor2 = expcolor1.split(' ');
      if (expcolor2.length > 1){
        expcolor3 = expcolor2.join('_');  
      } else {
        expcolor3 = expcolor1;
      }
    expcolor4 = expcolor3.split('/');
      if (expcolor4.length > 1){
        expcolor5 = expcolor4.join('G-');  
      } else {
        expcolor5 = expcolor3;
      }
    expcolor6 = expcolor5.split('&');
      if (expcolor6.length > 1){
        color = expcolor6.join('Y|');  
      } else {
        color = expcolor5;
      }
      
    if (cmt == ''){
      swal({
        title: "WO No Empty",
        text: "Please Fill WO No",
        icon: "error",
        timer: 3000,
      })
    } 
    else if (color == ''){
      swal({
        title: "Colors Empty",
        text: "Please Fill Colors",
        icon: "error",
        timer: 3000,
      })
    } 
    else if (exdate == ''){
      swal({
        title: "Ex Fty Date Empty",
        text: "Please Fill Ex Fty Date",
        icon: "error",
        timer: 3000,
      })
    } 
    else if (usercode == ''){
      swal({
        title: "User Empty",
        text: "Please Input User",
        icon: "error",
        timer: 3000,
      })
    }   

    else {
      var hal = $('#hal').val();
      $('#loader-on').click();
      $('#cmtnumb').show();
      $('#tampilwiprec').html('<img src="./assets/images/spinner.gif">');
      $('#tampilwiprec').load("apps/Transaction/Rework/tampilswip.php?cm="+cmt+"&co="+color+"&exdate="+exdate+"&usercode="+usercode);
      $('#seq_pro').load("apps/Transaction/Rework/selectsequence.php?cm="+cmt+"&co="+color+"&exdate="+exdate);
      $('#wo_no').val(cmt);
      $('#color_no').val(color);
      $('#ex_fty_date').val(exdate);
      $('#usercode').val(usercode);
      $('#loader-off').click();
    }
  }

  function receiverework(){
    $('#ceklot').val(2);
    var data = $('.form-user').serialize();
    $.ajax({
      type: 'POST',
      url:  "apps/Transaction/Rework/simpan.php",
      data: data,
      success: function(ui) {
//jika success tersimpan
        if(ui == '1'){
          swal({
             icon: 'success',
             title: 'Received',
             text: '',
             footer: '<a href>Why do I have this issue?</a>'
                
          })
          .then((willDelete) => {
            if (willDelete) {
              $('#formku').load("apps/Transaction/Rework/content_isi.php?id=2");
            }
          }); 
        } 
// jika tidak di pilih sama sekali wo nya
        else if(ui == '2'){
          swall('Please Check Lot','','error',2000);
          $('#submit-control').show();
          $('#ceklot').val('');
        } 

      }
    });
  }
</script>
