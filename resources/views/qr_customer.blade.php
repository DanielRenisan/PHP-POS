<!doctype html>
<html class="no-js" lang="en">

    <head>
        <!-- meta data -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

        <!--font-family-->
		<link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

		<link href="https://fonts.googleapis.com/css?family=Rufina:400,700" rel="stylesheet">
        
        <!-- title of site -->
        <title>Heavenly Bubble Tea</title>

       
        <!--font-awesome.min.css-->
        <link rel="stylesheet" href="{{asset('qr/css/font-awesome.min.css')}}">

        <!--linear icon css-->
		<link rel="stylesheet" href="{{asset('qr/css/linearicons.css')}}">

        <!--flaticon.css-->
		<link rel="stylesheet" href="{{asset('qr/css/flaticon.css')}}">

		<!--animate.css-->
        <link rel="stylesheet" href="{{asset('qr/css/animate.css')}}">

        <!--owl.carousel.css-->
        <link rel="stylesheet" href="{{asset('qr/css/owl.carousel.min.css')}}">
		<link rel="stylesheet" href="{{asset('qr/css/owl.theme.default.min.css')}}">
		
        <!--bootstrap.min.css-->
        <link rel="stylesheet" href="{{asset('qr/css/bootstrap.min.css')}}">
		
		<!-- bootsnav -->
		<link rel="stylesheet" href="{{asset('qr/css/bootsnav.css')}}" >	
        
        <!--style.css-->
        <link rel="stylesheet" href="{{asset('qr/css/style.css')}}">
        
        <!--responsive.css-->
        <link rel="stylesheet" href="{{asset('qr/css/responsive.css')}}">
        
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		
        <!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

    </head>
	
	<body>
		<!--[if lte IE 9]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
        <![endif]-->
	
		<!--welcome-hero start -->
		<section id="home" class="welcome-hero">

			<!-- top-area Start -->
			<div class="top-area">
				<div class="header-area">
					<!-- Start Navigation -->
				    <nav class="navbar navbar-default bootsnav  navbar-sticky navbar-scrollspy"  data-minus-value-desktop="70" data-minus-value-mobile="55" data-speed="1000">

				        <div class="container">

				            <!-- Start Header Navigation -->
				            <div class="navbar-header">
				                
				                <a class="navbar-brand" href="index.html">Heavenly Bubble Tea<span></span></a>

				            </div><!--/.navbar-header-->
				            <!-- End Header Navigation -->

				            <!-- Collect the nav links, forms, and other content for toggling -->
				           
				        </div><!--/.container-->
				    </nav><!--/nav-->
				    <!-- End Navigation -->
				</div><!--/.header-area-->
			    <div class="clearfix"></div>

			</div><!-- /.top-area-->
			<!-- top-area End -->

			<div class="container">
				<div class="welcome-hero-txt">
					<div class="row">
						<div class="col-md-12">
							<div class="model-search-content">
								<div class="row">
									<div class="col-md-offset-3 col-md-6 col-sm-12">
										<div class="single-model-search">
											<div style="display:flex"> 
											<input type="text"
                                    class="form-input"
                                    placeholder="CUSTOMER MOBILE/NAME" style="width: 80%"
                                    id="search_product" />
									&nbsp; &nbsp; &nbsp;
				                </button>
											<button class="btn btn-info" id="model-btn" data-toggle="modal" data-target="#row_edit_product_price_modal">+</button>
											</div>
										
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div  class="modal fade row_edit_product_price_model row_edit_product_price_modal" id="row_edit_product_price_modal" role="dialog">
		    @include('add_customer')
		    </div>
		</section>
		

		<!--contact start-->
		<footer id="contact"  class="contact">
			<div class="container">
				<div class="footer-top">
				</div>
			</div><!--/.container-->

			<div id="scroll-Top">
				<div class="return-to-top">
					<i class="fa fa-angle-up " id="scroll-top" data-toggle="tooltip" data-placement="top" title="" data-original-title="Back to Top" aria-hidden="true"></i>
				</div>
				
			</div><!--/.scroll-Top-->
			
        </footer><!--/.contact-->
		<!--contact end-->


		
		<!-- Include all js compiled plugins (below), or include individual files as needed -->

		<script src="{{asset('qr/js/jquery.js')}}"></script>
        
        <!--modernizr.min.js-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
		
		<!--bootstrap.min.js-->
        <script src="{{asset('qr/js/bootstrap.min.js')}}"></script>
		
		<!-- bootsnav js -->
		<script src="{{asset('qr/js/bootsnav.js')}}"></script>

		<!--owl.carousel.js-->
        <script src="{{asset('qr/js/owl.carousel.min.js')}}"></script>

		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>

        <!--Custom JS-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" rel="stylesheet" />
<script src="{{ asset('AdminLTE/plugins/jQuery/jquery-2.2.3.min.js') }}" crossorigin="anonymous"></script>
<script src="{{ asset('plugins/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
			base_path = "{{url('/')}}";
			var type = "{{request()->get('order_type')}}";
				var table_id = "{{request()->get('table')}}";
			$(function() {
				
				function get_url(id){
					console.log(11);
						var utrl =  "{{action('QRController@menu', ['customer_id'=> 'CUST' , 'order_type'=> 'TYPE', 'table'=> 'TABLE'])}}".replace('CUST', id)
						.replace('TYPE', type).replace('TABLE', table_id);
						const parseResult = new DOMParser().parseFromString(utrl, "text/html");
						const parsedUrl = parseResult.documentElement.textContent;
						window.location.href = parsedUrl;
				}


				if ($("#search_product").length > 0) {
					$("#search_product").autocomplete({
						source: base_path + "/get_customers",
						minLength: 6,
						response: function (event, ui) {
							if (ui.content.length == 1) {
								ui.item = ui.content[0];
								$(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);
								$(this).autocomplete('close');
							} else if (ui.content.length == 0) {
								var term = $(this).data('ui-autocomplete').term;
								$('#model-btn').trigger('click');
							}
						},
						select: function (event, ui) {
							$(this).val(null);
							get_url(ui.item.product_id);
						}
					})
					.autocomplete("instance")._renderItem = function (ul, item) {
						return $("<li>").append("<div>" + item.text + "</div>").appendTo(ul);
					};
    			}
			});
			$(document).ready(function () {
      		 	$(document).on('click', '#btn-save', function(){
      		 	    if($('#quick_name').val() == '')
					{
						Swal.fire('Name Field Required');
						return false;
					}
					if($('#quick_mobile').val() == '')
					{
						Swal.fire('Mobile Field Required');
						return false;
					}
					const quick_url = "{{action('Rest\ContactController@quickAdd')}}";
					$.ajax({
						url: quick_url,
						method: "POST",
						data: {
							_token: "{{ csrf_token() }}",
							first_name: $('#quick_name').val(),
							mobile_no: $('#quick_mobile').val(),
						},
						success: function (result) {
							if (result.success == true) 
							{
								Swal.fire(result.msg);
								var utrl =  "{{action('QRController@menu', ['customer_id'=> 'CUST' , 'order_type'=> 'TYPE', 'table'=> 'TABLE'])}}"
								.replace('CUST', result.data.id)
									.replace('TYPE', type).replace('TABLE', table_id);
									const parseResult = new DOMParser().parseFromString(utrl, "text/html");
									const parsedUrl = parseResult.documentElement.textContent;
									window.location.href = parsedUrl;
							}
							else
							{
								Swal.fire(result.msg);
							}
							
						}
					});
       			});

				
    		});
		</script>
    </body>
	
</html>