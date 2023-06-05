<?php
error_reporting( 0 );
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 150)) {
    $_SESSION['LAST_ACTIVITY'] = time();
}
// $actual_link = "http://$_SERVER[HTTP_HOST]/";
// $eraticket ="eraticket";
// echo "$actual_link";
//error_reporting(0);
ini_set('session.cookie_lifetime', 60 * 60 * 24 * 100);
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 100);

session_start();

// var_dump($_SESSION);
require_once "funlibs.php";
$con=new Database();
require_once "funlibsmysql.php";
$con2=new DatabaseM();
// require_once "funlibsmysql.php";
// $con2=new DatabaseM();

//echo $_SESSION['ID_ROLE'];
echo $_SESSION['ID_LOGIN'];
if(empty($_SESSION['ID_LOGIN'])){
    include"index.php";	
} else {
	include "assets/layout/page.php";
	$selnama = $con2->select("mos_users a join mos_graccess_usergroup b on a.id=b.userid join mos_graccess c on b.groupid=c.id","a.name,c.name as group_name","a.id = '".$_SESSION['ID_LOGIN']."'");
	//echo "select * from  m_users where user_id = $_SESSION[ID_LOGIN]";
	foreach($selnama as $nama){}

?>
<!DOCTYPE html>
<html class="fixed">

	<head>

		<!-- Basic -->
		<meta charset="UTF-8">

		<title>Laundry 192.168.51.24</title>
		<meta name="keywords" content="HTML5 Admin Template" />
		<meta name="description" content="Laundry">
		<meta name="author" content="okler.net">

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<!-- Web Fonts  -->
		<link href="assets/stylesheets/fontstyle.css" rel="stylesheet" type="text/css">

		<!-- icon -->
		<link rel="apple-touch-icon" sizes="57x57" href="assets/images/favicon/apple-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="assets/images/favicon/apple-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="assets/images/favicon/apple-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="assets/images/favicon/apple-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="assets/images/favicon/apple-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="assets/images/favicon/apple-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="assets/images/favicon/apple-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="assets/images/favicon/apple-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/apple-icon-180x180.png">
		<link rel="icon" type="image/png" sizes="192x192"  href="assets/images/favicon/android-icon-192x192.png">
		<link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="assets/images/favicon/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon/favicon-16x16.png">
		<link rel="manifest" href="/manifest.json">
		
		<!-- Vendor CSS -->
    	<link rel="stylesheet" href="assets/vendor/bootstrap-tagsinput/bootstrap-tagsinput.css" />
		<link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.css" />
		<link rel="stylesheet" href="assets/vendor/font-awesome/css/font-awesome.css" />
		<link rel="stylesheet" href="assets/vendor/magnific-popup/magnific-popup.css" />
		<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/css/datepicker3.css" />
		<link rel="stylesheet" href="assets/vendor/bootstrap-timepicker/css/bootstrap-timepicker.css" />

		<!-- Specific Page Vendor CSS -->
		<link rel="stylesheet" href="assets/vendor/jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.css" />
		<link rel="stylesheet" href="assets/vendor/bootstrap-multiselect/bootstrap-multiselect.css" />
		<link rel="stylesheet" href="assets/vendor/bootstrap-tagsinput/bootstrap-tagsinput.css" />
		<link rel="stylesheet" href="assets/vendor/bootstrap-colorpicker/css/bootstrap-colorpicker.css" />
		<link rel="stylesheet" href="assets/vendor/bootstrap-timepicker/css/bootstrap-timepicker.css" />
		<link rel="stylesheet" href="assets/vendor/dropzone/css/basic.css" />
		<link rel="stylesheet" href="assets/vendor/dropzone/css/dropzone.css" />
		<link rel="stylesheet" href="assets/vendor/bootstrap-markdown/css/bootstrap-markdown.min.css" />
		<link rel="stylesheet" href="assets/vendor/summernote/summernote.css" />
		<link rel="stylesheet" href="assets/vendor/summernote/summernote-bs3.css" />
		<link rel="stylesheet" href="assets/vendor/codemirror/lib/codemirror.css" />
		<link rel="stylesheet" href="assets/vendor/codemirror/theme/monokai.css" />
		<link rel="stylesheet" href="assets/vendor/morris/morris.css" />
    	<link rel="stylesheet" href="assets/vendor/select2/select2.css" />		
		<link rel="stylesheet" href="assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />
		<link rel="stylesheet" href="assets/stylesheets/sweetalert.css" />
		<link href="assets/stylesheets/check.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="assets/stylesheets/bootstrap.min.css">
		

		<link rel="stylesheet" href="assets/vendor/bootstrap-fileupload/bootstrap-fileupload.min.css" />
		<!-- Theme CSS -->
		<link rel="stylesheet" href="assets/stylesheets/theme.css" />

		<!-- Skin CSS -->
		<link rel="stylesheet" href="assets/stylesheets/skins/default.css" />

		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="assets/stylesheets/theme-custom.css">
		<link rel="stylesheet" href="assets/stylesheets/themes/base/jquery.ui.autocomplete.css" />
		<!-- Head Libs -->
		<script src="assets/vendor/modernizr/modernizr.js"></script>
		<style type="text/css">
			.my-custom-scrollbar {
		position: relative;
		height: 300px;
		overflow: auto;
		}
		.table-wrapper-scroll-y {
		display: block;
		}
		  	
			#loader {
			  position: absolute;
			  left: 50%;
			  top: 520%;
			  z-index: 1;
			  width: 150px;
			  height: 150px;
			  margin: -75px 0 0 -75px;
			  border: 16px solid #f3f3f3;
			  border-radius: 50%;
			  border-top: 16px solid #3498db;
			  width: 120px;
			  height: 120px;
			  -webkit-animation: spin 2s linear infinite;
			  animation: spin 2s linear infinite;
			}

			@-webkit-keyframes spin {
			  0% { -webkit-transform: rotate(0deg); }
			  100% { -webkit-transform: rotate(360deg); }
			}

			@keyframes spin {
			  0% { transform: rotate(0deg); }
			  100% { transform: rotate(360deg); }
			}

			/* Add animation to "page content" */
			.animate-bottom {
			  position: relative;
			  -webkit-animation-name: animatebottom;
			  -webkit-animation-duration: 1s;
			  animation-name: animatebottom;
			  animation-duration: 1s
			}

			@-webkit-keyframes animatebottom {
			  from { bottom:-100px; opacity:0 } 
			  to { bottom:0px; opacity:1 }
			}

			@keyframes animatebottom { 
			  from{ bottom:-100px; opacity:0 } 
			  to{ bottom:0; opacity:1 }
			}

			#myDiv {
			  display: none;
			  text-align: center;
			}
		</style>
		
		
	</head>

	<body>
		
		<section class="body">
		
			<!-- start: header -->
			<header class="header">
				<div class="logo-container">
					<a href="content.php" class="logo">
						<img src="assets/images/logo-eratex-djaja.png" height="50" width="100%" alt="Eratex Djaja" />
					</a>
					<div class="visible-xs toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
						<i class="fa fa-bars" aria-label="Toggle sidebar"></i>
					</div>
				</div>
				<div id="loader" align="center"></div>
				<!-- start: search & user box -->
				<div class="header-right">
					<a href="javascript:void(0)" class="btn btn-success" onclick="loaderon()" id="loader-on" style="display: none">loading-on</a>
      				<a href="javascript:void(0)" class="btn btn-success" onclick="setTimeout(loaderoff, 3000);"  id="loader-off" style="display: none">loading-off</a>
      				<a href="javascript:void(0)" class="btn btn-success" onclick="loaderoff()"  id="loader-off2" style="display: none">loading-off</a>

					<span class="separator"></span>

					<div id="userbox" class="userbox">
						<a href="#" data-toggle="dropdown">
							<figure class="profile-picture">
								<img src="assets/images/!logged-user.jpg" alt="Joseph Doe" class="img-circle" data-lock-picture="assets/images/!logged-user.jpg" />
							</figure>
							<div class="profile-info" data-lock-name="John Doe" data-lock-email="johndoe@okler.com">
								<span class="name"><?php echo $nama['name']; ?></span>
								<span class="role"><?php echo $nama['group_name'].$_SESSION[ID_LOGIN]?></span>
							</div>

							<i class="fa custom-caret"></i>
						</a>

						<div class="dropdown-menu">
							<ul class="list-unstyled">
								<li class="divider"></li>
								<!-- <li>
									<a role="menuitem" tabindex="-1" href="pages-user-profile.html"><i class="fa fa-user"></i> My Profile</a>
								</li> -->
								<!-- <li>
									<a role="menuitem" tabindex="-1" href="#" data-lock-screen="true"><i class="fa fa-lock"></i> Lock Screen</a>
								</li> -->
								<li>
									<a role="menuitem" tabindex="-1" href="logout.php"><i class="fa fa-power-off"></i> Logout</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<!-- end: search & user box -->
			</header>
			<!-- end: header -->

			<div class="inner-wrapper">
				<!-- start: sidebar -->
				<aside id="sidebar-left" class="sidebar-left">

					<div class="sidebar-header">
						<div class="sidebar-title">
							Menu
						</div>
						<div class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
							<i class="fa fa-bars" aria-label="Toggle sidebar"></i>
						</div>
					</div>

					<div class="nano">
						<div class="nano-content">
							<nav id="menu" class="nav-main" role="navigation">
								<ul class="nav nav-main">
									<?php include "assets/layout/menu.php"; ?>
								</ul>
						</div>

					</div>

				</aside>
				<!-- end: sidebar -->
				
				<section role="main" class="content-body">
					
				<!-- 	<header class="page-header">
						<h2><?php echo $title?></h2>						
					</header> -->

					<!-- start: page -->
					<div class="row">
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
							<section class="panel">
								<div class="panel-body">
									<?php 
									
									if(!empty($_GET['option'])){
										
										include $modul;

									}
									else{
										//include "apps/dashboard/dashboard.php";
									}
									
									 ?>
								</div>
							</section>
						</div>
					</div>
					<!-- end: page -->
				</section>
			</div>


			
		</section>

		<!-- Vendor -->
		<script src="assets/vendor/jquery/jquery.js"></script>
		<script src="assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
		<script src="assets/vendor/bootstrap/js/bootstrap.js"></script>
		<script src="assets/vendor/nanoscroller/nanoscroller.js"></script>
		<script src="assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
		<script src="assets/vendor/magnific-popup/magnific-popup.js"></script>
        <script src="assets/vendor/jquery-autosize/jquery.autosize.js"></script>
        <script src="assets/vendor/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
        <script src="assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>
        <script src="js/jsindex.js"></script>

		<!-- Specific Page Vendor -->
		<script src="assets/vendor/jquery-ui/js/jquery-ui-1.10.4.custom.js"></script>
		<script src="assets/vendor/jquery-ui-touch-punch/jquery.ui.touch-punch.js"></script>
		<script src="assets/vendor/select2/select2.js"></script>
		<script src="assets/vendor/bootstrap-multiselect/bootstrap-multiselect.js"></script>
		<script src="assets/vendor/jquery-maskedinput/jquery.maskedinput.js"></script>
		<script src="assets/vendor/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>
		<script src="assets/vendor/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
		<script src="assets/vendor/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
		<script src="assets/vendor/fuelux/js/spinner.js"></script>
		<script src="assets/vendor/dropzone/dropzone.js"></script>
		<script src="assets/vendor/bootstrap-markdown/js/markdown.js"></script>
		<script src="assets/vendor/bootstrap-markdown/js/to-markdown.js"></script>
		<script src="assets/vendor/bootstrap-markdown/js/bootstrap-markdown.js"></script>
		<script src="assets/vendor/codemirror/lib/codemirror.js"></script>
		<script src="assets/vendor/codemirror/addon/selection/active-line.js"></script>
		<script src="assets/vendor/codemirror/addon/edit/matchbrackets.js"></script>
		<script src="assets/vendor/codemirror/mode/javascript/javascript.js"></script>
		<script src="assets/vendor/codemirror/mode/xml/xml.js"></script>
		<script src="assets/vendor/codemirror/mode/htmlmixed/htmlmixed.js"></script>
		<script src="assets/vendor/codemirror/mode/css/css.js"></script>
		<script src="assets/vendor/summernote/summernote.js"></script>
		<script src="assets/vendor/bootstrap-maxlength/bootstrap-maxlength.js"></script>
		<script src="assets/vendor/ios7-switch/ios7-switch.js"></script>
		<script src="assets/vendor/bootstrap-confirmation/bootstrap-confirmation.js"></script>
		<script src="assets/vendor/jquery-appear/jquery.appear.js"></script>
		<script src="js/libs/ckeditor/ckeditor.js"></script>
		<?php
		include "$data";
		?>

		<script src="assets/vendor/jquery-datatables/media/js/jquery.dataTables.min.js"></script>
		<script src="assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
		<script src="assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
		
		<!-- <script src="assets/vendor/datatables/jquery.dataTables.min.js"></script> -->
		<!-- <script src="assets/vendor/datatables/dataTables.bootstrap.js"></script> -->
		<script src="assets/javascripts/sweetalert.min.js"></script>
		<!-- Theme Base, Components and Settings -->
		<script src="assets/javascripts/theme.js"></script>
		<!-- Theme Custom -->
		<script src="assets/javascripts/theme.custom.js"></script>
		<!-- Theme Initialization Files -->
		<script src="assets/javascripts/theme.init.js"></script>
		<script src="js/jquery.number.js"></script>
		<!-- CKEditor -->
    	<script src="js/libs/ckeditor/ckeditor.js"></script>
		<script src="assets/vendor/bootstrap-fileupload/bootstrap-fileupload.min.js"></script>
        <script src="assets/vendor/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>
        <?php
        if($_GET[task]=="sequence"){
        	echo "<script src=\"js/jquery.min.js\"></script>";
        }
        ?>
        <!-- <script src="js/jquery.min.js"></script> -->
        <script src="assets/javascripts/jquery-ui-1.9.2.js"></script>
     
        <script>
        	function showFileSize() {
			    var input, file;

			    // (Can't use `typeof FileReader === "function"` because apparently
			    // it comes back as "object" on some browsers. So just see if it's there
			    // at all.)
			    if (!window.FileReader) {
			        bodyAppend("p", "The file API isn't supported on this browser yet.");
			        return;
			    }

			    input = document.getElementById('gambar');
			    if (!input) {
			        bodyAppend("p", "Um, couldn't find the fileinput element.");
			    }
			    else if (!input.files) {
			        bodyAppend("p", "This browser doesn't seem to support the `files` property of file inputs.");
			    }
			    else if (!input.files[0]) {
			        bodyAppend("p", "Please select a file before clicking 'Load'");
			    }
			    else if (input.files[0].type != 'application/pdf' ) { 
					if (input.files[0].size > 2044070) {
						file = input.files[0];
						var ses = file.size;
						//alert(input.files[0].size);
						alert("File Terlalu Besar Max 2 MB");
						$('#gambar').val("");
			    	} 
				}
				else if (input.files[0].type == 'application/pdf' ) { 
					if (input.files[0].size > 10044070) {
						file = input.files[0];
						var ses = file.size;
						//alert(input.files[0].size);
						alert("File Terlalu Besar Max 10 MB");
						$('#file').val("");
			    	}
				}
				// else  {
			       
			 //    }

			}

			function bodyAppend(tagName, innerHTML) {
			    var elm;

			    elm = document.createElement(tagName);
			    elm.innerHTML = innerHTML;
			    document.body.appendChild(elm);
				alert(innerHTML);
			}

        	function bacaGambar(input) {
                 if (input.files && input.files[0]) {
                     var reader = new FileReader();                        
                     reader.onload = function (e) {
                           $('#gambar_nodin').attr('src', e.target.result);
                     }
                     reader.readAsDataURL(input.files[0]);
                 	}
            }
                                            
            $("#gambar").change(function(){
                 bacaGambar(this);
            });
            
            function hanyaAngka(evt) {
			  var charCode = (evt.which) ? evt.which : event.keyCode
			   if (charCode > 31 && (charCode < 48 || charCode > 57) && (charCode > 95 || charCode < 106))
	 
				return false;
			  return true;
	  		}
	  		function valscript(evt) {
				  var charCode = (evt.which) ? evt.which : event.keyCode
				   if (charCode > 32 && (charCode < 44 || charCode > 57) && (charCode < 65 || charCode > 90) && (charCode < 97 || charCode > 122))
		 
				    return false;
				  return true;
			}
			
			function allvalscript(evt) {
				  var charCode = (evt.which) ? evt.which : event.keyCode
				   if (charCode > 33 && (charCode < 35 || charCode > 38)&&(charCode < 40 || charCode > 64) && (charCode < 65 || charCode > 90) && (charCode < 97 || charCode > 122))
		 
				    return false;
				  return true;
			}
			
			function hanyaHuruf(evt) {
				  var charCode = (evt.which) ? evt.which : event.keyCode
				   if (charCode > 32 && (charCode < 48 || charCode > 57) && (charCode < 65 || charCode > 90) && (charCode < 97 || charCode > 122))
		 
				    return false;
				  return true;
			}
			
			function loaderon(){
				$('#loader').show();
			}
			function loaderoff(){
				$('#loader').hide();
			}

			document.onreadystatechange = function() { 
            if (document.readyState !== "complete") { 
                document.querySelector( 
                  "body").style.visibility = "hidden"; 
                document.querySelector( 
                  "#loader").style.visibility = "visible"; 
            } else { 
                document.querySelector( 
                  "#loader").style.display = "none"; 
                document.querySelector( 
                  "body").style.visibility = "visible"; 
            } 
            
           

   //          function swalconfirm(){
			// 	$('#loader').hide();
			// }

			// function swalsuccess(){
			// 	$('#loader').hide();
			// }
        }; 
        </script>
        
	</body>
</html>
<?php } ?>