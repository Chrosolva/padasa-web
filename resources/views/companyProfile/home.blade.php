
@extends('companyProfile.app')

@section('header-title')
    Home
@endsection


@section('main-content')

	<div class="default-color-container">
	    <div class="container">
	        <div class="row">
	            <div class="col-sm-12 sm-margin-b-60 text-center">
	                <div class="margin-b-30" style="padding: 20px 0;">
	                    <h1 class="promo-block-title">Welcome to PT. Padasa Enam Utama</h1>
	                    <!-- <p class="promo-block-text">Our site is currently under construction.</p>	 -->

						<div class="owl-carousel owl-theme owl-loaded" style ="width:900px;height:600px; margin:0 auto;">
							<div class="owl-stage-outer">
								<div class="owl-stage">
									<div class="owl-item">
										<img src="{{url('assets/home/PEU1.jpg')}}" alt="First slide">
									</div>
									<div class="owl-item">
										<img src="{{url('assets/home/PEU2.jpg')}}" alt="Second slide">
									</div>
									<div class="owl-item">
										<img src="{{url('assets/home/PEU3.jpg')}}" alt="Third slide">
									</div>
								</div>
							</div>
							<div class="owl-nav">
								<div class="owl-prev">prev</div>
								<div class="owl-next">next</div>
							</div>
							<!-- <div class="owl-dots">
								<div class="owl-dot active"><span></span></div>
								<div class="owl-dot"><span></span></div>
								<div class="owl-dot"><span></span></div>
							</div> -->
						</div>
	                </div>
					
	            </div>
	        </div>
	    </div>
	</div>
@endsection

@section('script-content')
    <script type="text/javascript">
		var owl = $('.owl-carousel');
		owl.owlCarousel({
			items:1,
			loop:true,
			margin:10,
			autoplay:true,
			autoplayTimeout:2800,
			autoplayHoverPause:false,
			animateOut: 'fadeOut'
		});

		$('.owl-next').click(function() {
			owl.trigger('next.owl.carousel');
		})
		// Go to the previous item
		$('.owl-prev').click(function() {
			// With optional speed parameter
			// Parameters has to be in square bracket '[]'
			owl.trigger('prev.owl.carousel', [300]);
		})
    </script>
@endsection
