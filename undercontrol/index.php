<!DOCTYPE html>
<html lang="en" class="no-js">
    <head>
		<link rel="icon" href="img/fire_icon.png">
    	<!-- meta charec set -->
        <meta charset="utf-8">
		<!-- Always force latest IE rendering engine or request Chrome Frame -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<!-- Page Title -->
        <title>FireAway Home</title>
		<!-- Meta Description -->
        <meta name="description" content="Blue One Page Creative HTML5 Template">
        <meta name="keywords" content="one page, single page, onepage, responsive, parallax, creative, business, html5, css3, css3 animation">
        <meta name="author" content="Muhammad Morshed">
		<!-- Mobile Specific Meta -->
        <meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- Google Font -->

		<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>

		<!-- CSS
		================================================== -->
		<!-- Fontawesome Icon font -->
        <link rel="stylesheet" href="css/font-awesome.min.css">
		<!-- Twitter Bootstrap css -->
        <link rel="stylesheet" href="css/bootstrap.min.css">
		<!-- jquery.fancybox  -->
        <link rel="stylesheet" href="css/jquery.fancybox.css">
		<!-- animate -->
        <link rel="stylesheet" href="css/animate.css">
		<!-- Main Stylesheet -->
        <link rel="stylesheet" href="css/main.css">
		<!-- media-queries -->
        <link rel="stylesheet" href="css/media-queries.css">

		<!-- Modernizer Script for old Browsers -->
        <script src="js/modernizr-2.6.2.min.js"></script>
    </head>

    <body id="body" onload=" loadInformation();">

		<!-- preloader -->
		<div id="preloader">
			<img src="img/preloader.gif" alt="Preloader">
		</div>
		<!-- end preloader -->

        <!--
        Fixed Navigation
        ==================================== -->
        <?php include('header.php'); ?>
        <!--
        End Fixed Navigation
        ==================================== -->

        <!--
        Home Slider
        ==================================== -->

		<section id="slider">
			<div id="home" class="carousel slide" data-ride="carousel">

				<!-- Indicators bullet -->
				<ol class="carousel-indicators">
					<li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
					<li data-target="#carousel-example-generic" data-slide-to="1"></li>
				</ol>
				<!-- End Indicators bullet -->

				<!-- Wrapper for slides -->
				<div class="carousel-inner" role="listbox">

					<!-- single slide -->
					<div class="item active" style="background-image: url(img/banner_1.jpg);">
						<div class="carousel-caption">
							<h2 data-wow-duration="700ms" data-wow-delay="500ms" class="wow bounceInDown animated">Meet<span> FireAway</span>!</h2>
							<h3 data-wow-duration="1000ms" class="wow slideInLeft animated"><span class="color">Award Winning</span> Fire-Based Consultancy.</h3>
							<p data-wow-duration="1000ms" class="wow slideInRight animated">We are a small team of professional consultancy</p>
						</div>
					</div>
					<!-- end single slide -->

					<!-- single slide -->
					<div class="item" style="background-image: url(img/banner_2.jpg);">
						<div class="carousel-caption">
							<h2 data-wow-duration="500ms" data-wow-delay="500ms" class="wow bounceInDown animated">About<span> FireAway</span>!</h2>
							<h3 data-wow-duration="500ms" class="wow slideInLeft animated"><span class="color">100% certified</span> Fire-Based Consultancy</h3>
							<p data-wow-duration="500ms" class="wow slideInRight animated">People First, Safety Always</p>
						</div>
					</div>
					<!-- end single slide -->

				</div>
				<!-- End Wrapper for slides -->

			</div>
		</section>

        <!--
        End Home SliderEnd
        ==================================== -->

        <!--
        Features
        ==================================== -->

		<section id="features" class="features">
			<div class="container">
				<div class="row">

					<div class="sec-title text-center mb50 wow bounceInDown animated" data-wow-duration="500ms">
						<h2>About Us</h2>
						<div class="devider"><i class="fa fa-fire fa-lg"></i></div>
					</div>

					<!-- service item -->
					<div class="col-md-3 wow fadeInLeft" data-wow-duration="500ms">
						<div class="service-item">
							<div class="service-icon">
								<i class="fa fa-bullseye fa-2x"></i>
							</div>

							<div class="service-desc">
								<h3>Our Mission</h3>
								<h4>We ensure <strong>100%</strong> quality</h4>
								<p>- To ensure both companies and general public must be <strong>100%</strong> prepared when there is a fire outbreak</p>

							</div>
						</div>
					</div>
					<!-- end service item -->

					<!-- service item -->
					<div class="col-md-3 wow fadeInDown" data-wow-duration="500ms"  data-wow-delay="400ms">
						<div class="service-item">
							<div class="service-icon">
								<i class="fa fa-rocket fa-2x"></i>
							</div>

							<div class="service-desc">
								<h3>Motto</h3>
								<h4><strong>People First, Safety Always</strong></h4>
								<p>We emphasise the utmost importance of safety towards our people against the threats of fire</p>
							</div>
						</div>
					</div>

					<!-- service item -->
					<div class="col-md-3 wow fadeInUp" data-wow-duration="500ms" data-wow-delay="800ms">
						<div class="service-item">
							<div class="service-icon">
								<i class="fa fa-trophy fa-2x"></i>
							</div>

							<div class="service-desc">
							<h3>Awards</h3>
								<p>Reader's Digest Trusted Brands Years: 2014-2020</p>
								<p>Certified Fire Consultancy Years: 2015-2020</p>
								<p>Top 10 Best Educational Centre in Singapore Year: 2018</p>

							</div>
						</div>
					</div>
					<!-- end service item -->

					<!-- service item -->
					<div class="col-md-3 wow fadeInRight" data-wow-duration="500ms"  data-wow-delay="1200ms">
						<div class="service-item">
							<div class="service-icon">
								<i class="fa fa-bullhorn fa-2x"></i>
							</div>

							<div class="service-desc">
								<h3>Consulting</h3>
								<p>We provide face-to-face consultations with over 100+ companies of many diverse industries, but now we are transitioning to offer monthly virtual programme sessions!</p>
							</div>
						</div>
					</div>
					<!-- end service item -->

				</div>
			</div>
		</section>

        <!--
        End Features
        ==================================== -->


        <!--
        Our Works
        ==================================== -->

		<section id="works" class="works clearfix">
			<div class="container">
				<div class="row">

					<div class="sec-title text-center"  id="category">
						<h2>Introduction</h2>
						<div class="devider"><i class="fa fa-fire fa-lg"></i></div>
					</div>

					<div class="sec-sub-title text-center">
						<p>We are a fire safety consultancy organization that provides professional fire-consulting services to companies about fire safety information such as Fire Classes and Fire Extinguishers types. The main focus is to introduce the public to general knowledge on fire safety guidelines and educating companies in the event a fire does break out within their premises. This would allow spreading of awareness to the general public on the importance of the dangers of fire. Our core services include Consultancy, Training, On-Site Practicals.</p>
					</div>

					<div class="work-filter wow fadeInRight animated" data-wow-duration="500ms">
						<ul class="text-center">
							<li><a href="javascript:;" data-filter=".fireInfo" class="filter">Fire Information</a></li>
								<li><a href="javascript:;" data-filter=".tut" class="filter">Extinguisher Tutorial</a></li>
								<li><a href="javascript:;" data-filter=".case" class="filter">Case Studies</a></li>
								<li><a href="javascript:;" data-filter=".faq" class="filter">FAQ</a>
							</li>
						</ul>
					</div>

				</div>
			</div>

			<div class="project-wrapper">
				<figure class="mix work-item fireInfo">
				<img src="img/works/classa_fire.png" alt="">
				<figcaption class="overlay">
					<a class="fancybox" rel="works" title="Class A Fires Common Examples: Wood, Paper or Textiles" href="img/works/wood-1.jpg"><i class="fa fa-info-circle fa-lg"></i></a>
					<h4>Class A Fire</h4>
					<p>Fires involving ordinary combustible materials</p>
				</figcaption>
				</figure>

				<figure class="mix work-item fireInfo">
					<img src="img/works/classb_fire.png" alt="">
					<figcaption class="overlay">
						<a class="fancybox" rel="works" title="Class B Fires Common Examples: Gasoline, Solvents" href="img/works/gasoline-1.jpg"><i class="fa fa-info-circle fa-lg"></i></a>
						<h4>Class B Fire</h4>
						<p>Fires involving flammable liquids</p>
					</figcaption>
				</figure>

				<figure class="mix work-item fireInfo">
					<img src="img/works/classc_fire.png" alt="">
					<figcaption class="overlay">
						<a class="fancybox" rel="works" title="Class C Fires Common Examples: Propane, Buthane" href="img/works/propane-1.jpg"><i class="fa fa-info-circle fa-lg"></i></a>
						<h4>Class C Fire</h4>
						<p>Fires involving flammable gases</p>
					</figcaption>
				</figure>

				<figure class="mix work-item fireInfo">
					<img src="img/works/classd_fire.png" alt="">
					<figcaption class="overlay">
						<a class="fancybox" rel="works" title="Class D Fires Common Examples: Magnesium, Aluminium" href="img/works/magnesium-1.jpg"><i class="fa fa-info-circle fa-lg"></i></a>
						<h4>Class D Fire</h4>
						<p>Fires involving combustible metals</p>
					</figcaption>
				</figure>

				<figure class="mix work-item fireInfo">
					<img src="img/works/classe_fire.png" alt="">
					<figcaption class="overlay">
						<a class="fancybox" rel="works" title="Class E Fires Common Examples: Computers, Phone Chargers, E-Scooters" href="img/works/electrical-1.jpg"><i class="fa fa-info-circle fa-lg"></i></a>
						<h4>Class E Fire</h4>
						<p>Fires involving live electrical equipment</p>
					</figcaption>
				</figure>

				<figure class="mix work-item fireInfo">
					<img src="img/works/classf_fire.png" alt="">
					<figcaption class="overlay">
						<a class="fancybox" rel="works" title="Class F Fires Common Examples: Cooking oils, Fats" href="img/works/cooking-1.jpg"><i class="fa fa-info-circle fa-lg"></i></a>
						<h4>Class F Fire</h4>
						<p>Fires involving commerical cooking equipment</p>
					</figcaption>
				</figure>

				<figure class="mix work-item tut">
					<img src="img/works/water-extinguisher.png" alt="">
					<figcaption class="overlay">
						<a class="fancybox" rel="works" title="Effective Against: Class A Fires" href="img/works/water-details.jpg"><i class="fa fa-info-circle fa-lg"></i></a>
						<h4>Water Extinguisher</h4>
						<p>Color Code: Red Band</p>
						<p>Contains Water + Other Additives</p>
					</figcaption>
				</figure>

				<figure class="mix work-item tut">
					<img src="img/works/foam-extinguisher.png" alt="">
					<figcaption class="overlay">
						<a class="fancybox" rel="works" title="Effective Against: Class A & B Fires" href="img/works/foam-details.png"><i class="fa fa-info-circle fa-lg"></i></a>
						<h4>Foam Extinguisher</h4>
						<p>Color Code: Cream Band</p>
						<p>Contains Foam</p>
					</figcaption>
				</figure>

				<figure class="mix work-item tut">
					<img src="img/works/powder-extinguisher.png" alt="">
					<figcaption class="overlay">
						<a class="fancybox" rel="works" title="Effective Against: Class A - E Fires" href="img/works/dcp-details.jpg"><i class="fa fa-info-circle fa-lg"></i></a>
						<h4>Dry Powder Extinguisher</h4>
						<p>Color Code: Dark Blue Band</p>
						<p>Contains very fine Chemical Powder of of monoammonium phosphate</p>
					</figcaption>
				</figure>

				<figure class="mix work-item tut">
					<img src="img/works/co2-extinguisher.png" alt="">
					<figcaption class="overlay">
						<a class="fancybox" rel="works" title="Effective Against: Class B & E Fires" href="img/works/co2-details.jpg"><i class="fa fa-info-circle fa-lg"></i></a>
						<h4>Carbon dioxide (Co2) Extinguisher</h4>
						<p>Color Code: Black Band</p>
						<p>Contains Carbon Dioxide, discharged in form of snow/cloud</p>
					</figcaption>
				</figure>

				<figure class="mix work-item tut">
					<img src="img/works/water-mist-extinguisher.png" alt="">
					<figcaption class="overlay">
						<a class="fancybox" rel="works" title="Effective Against: Class A, B, C, F Fires" href="img/works/water-mist-details.png"><i class="fa fa-info-circle fa-lg"></i></a>
						<h4>Water Mist Extinguisher</h4>
						<p>Color Code: White Band</p>
						<p>Contains dry demineralised water particles</p>
					</figcaption>
				</figure>

				<figure class="mix work-item tut">
					<img src="img/works/chemical-extinguisher.png" alt="">
					<figcaption class="overlay">
						<a class="fancybox" rel="works" title="Effective Against: Class A & F Fires" href="img/works/wet-chemical-details.jpg"><i class="fa fa-info-circle fa-lg"></i></a>
						<h4>Wet Chemical Extinguisher</h4>
						<p>Color Code: Yellow Band</p>
						<p>Contain alkali salts in water form</p>
					</figcaption>
				</figure>

				<figure class="mix work-item tut">
				<img src="img/works/extinguisher-pictoral.png" alt="">
					<figcaption class="overlay">
						<a class="fancybox" rel="works" title="" href="img/works/extinguisher-pictoral-extended.png"><i class="fa fa-eye fa-lg"></i></a>
						<h4>Pictorial Representation of Fire Extinguisher Uses</h4>
         			</figcaption>
				</figure>

				<figure class="mix work-item tut">
				<img src="img/works/extinguisher-steps.png" alt="">
					<figcaption class="overlay">
						<a class="fancybox" rel="works" title="Follow the 4-step PASS actions" href="img/works/extinguisher-steps-extended.jpg"><i class="fa fa-eye fa-lg"></i></a>
						<h4>Fire Extinguisher Steps</h4>
						<p></p>
					</figcaption>
				</figure>

				<figure class="mix work-item case">
				<img src="img/works/tuas-fire.jpg" alt="">
					<figcaption class="overlay">
						<a class="fancybox" title="View Link Here" target="_blank" rel="noopener noreferrer" href="https://www.channelnewsasia.com/singapore/tuas-fire-loud-explosion-industrial-building-8-hospital-burn-371801"><i class="fa fa-eye fa-lg"></i></a>
						<h4>Case Study #1: Tuas Industrial Fire</h4>
						<p>3 workers died and 7 injuries from fire and explosion of the Industrial Building</p>
					</figcaption>
				</figure>

				<figure class="mix work-item case">
				<img src="img/works/tampines-fire.jpg" alt="">
					<figcaption class="overlay">
						<a class="fancybox" title="View Link Here" target="_blank" rel="noopener noreferrer" href="https://www.channelnewsasia.com/singapore/tampines-st-81-823a-coffee-shop-fire-scdf-2098646"><i class="fa fa-eye fa-lg"></i></a>
						<h4>Case Study #2: Tampines Street 81 Fire</h4>
						<p>No injures reported, fire spreads to entire coffeeshop.</p>
					</figcaption>
				</figure>

				<figure class="mix work-item case">
				<img src="img/works/pmd-fire.jpg" alt="">
					<figcaption class="overlay">
						<a class="fancybox" title="View Link Here" target="_blank" rel="noopener noreferrer" href="https://www.channelnewsasia.com/singapore/pmd-fire-lift-537-woodlands-drive-16-scdf-man-dies-1832186"><i class="fa fa-eye fa-lg"></i></a>
						<h4>Case Study #3: Increase in PMD-related Electrical Fires</h4>
						<p>PMDs have non-UL2272-certified devices that poses fire risk and threaten public safety.</p>
						<p></p>
					</figcaption>
				</figure>

				<figure class="mix work-item case">
					<img src="img/works/ck-fire.jpg" alt="">
					<figcaption class="overlay">
						<a class="fancybox" rel="noopener noreferrer" title="View Link Here" target="_blank" href="https://www.straitstimes.com/singapore/fire-at-ck-building-at-tampines-street-92"><i class="fa fa-eye fa-lg"></i></a>
						<h4>Case Study #4: CK Building Fire</h4>
						<p>According to the SCDF, the premises contain highly flammable goods such as paper products and aerosol cans.</p>
					</figcaption>
      			</figure>

				<figure class="mix work-item faq">
					<img src="img/works/question.jpg" alt="">
					<figcaption class="overlay">
						<h4>Question: When is the monthly FireAway fire-safety programme be held?</h4>
						<p>Answer: It will be a 3-day programme from 9am-6pm, happening every Friday-Sunday on our livestream!</p>
					</figcaption>
				</figure>

				<figure class="mix work-item faq">
					<img src="img/works/question.jpg" alt="">
					<figcaption class="overlay">
						<h4>Question: What is the brief overview of the fire-safety programme?</h4>
						<p>Answer: We cover the following: Fire Classes & Extinguisher Introduction, Workplace Fire Safety, Dangers and Consequences</p>
					</figcaption>
				</figure>

				<figure class="mix work-item faq">
					<img src="img/works/question.jpg" alt="">
					<figcaption class="overlay">
						<h4>Question: Which industries are involved in consultations/trainings?</h4>
						<p>Answer: We are at a heart of a large array of industries pertaining to fire safety: Business, Construction, Education, Engineering, F&B, Health, Manufacturing</p>
					</figcaption>
				</figure>

				<figure class="mix work-item faq">
					<img src="img/works/question.jpg" alt="">
					<figcaption class="overlay">
						<h4>Question: Who will be the main instructor for the different aspects of programme?</h4>
						<p>Answer: Fire Classes & Extinguisher Introduction (Jerone & Joel), Workplace Fire Safety (Aaron & Brandon), Dangers and Consequences (Zulfadli)</p>
					</figcaption>
				</figure>

			</div>
		</section>

        <!--
        End Our Works
        ==================================== -->

        <!--
        Meet Our Team
        ==================================== -->

		<section id="team" class="team">
			<div class="container">
				<div class="row">

					<div class="sec-title text-center wow fadeInUp animated" data-wow-duration="700ms" id="Team">
						<h2>Meet Our Team</h2>
						<div class="devider"><i class="fa fa-fire fa-lg"></i></div>
					</div>

					<div class="sec-sub-title text-center wow fadeInRight animated" data-wow-duration="500ms">
						<p>The organization consists of 5 motivating individuals internationally who have the valuable expertise in providing fire consulting services to the companies worldwide, and spreading out the awareness towards the dangers of different types of fires!</p>
					</div>

					<!-- single member #1 -->
					<figure class="team-member col-md-4 col-sm-6 col-xs-12  wow fadeInUp animated" data-wow-duration="500ms">
						<div class="member-thumb">
							<img src="img/team/Jerone.png" alt="Team Member" class="img-responsive">
							<figcaption class="overlay">
								<h5>About Jerone Poh</h5>
								<p>Born in Singapore</p>
								<p>Formed the brainchild of FireAway with 5+ years of instructor experience</p>
								<p>Providing online content and advisories to the public 24/7!</p>
							</figcaption>
						</div>
						<h4>Jerone Poh</h4>
						<span>Founder / Web Developer </span>
					</figure>
					<!-- end single member #1 -->

					<!-- single member #2 -->
					<figure class="team-member col-md-4 col-sm-6 col-xs-12 wow fadeInUp animated" data-wow-duration="500ms" data-wow-delay="300ms">
						<div class="member-thumb">
							<img src="img/team/Joel.png" alt="Team Member" class="img-responsive">
							<figcaption class="overlay">
								<h5>About Joel Wong</h5>
								<p>Born in Singapore</p>
								<p>Former firefighting trained personnel</p>
								<p>5+ years of firefighting experience</p>
								<p>Trained 20+ companies in fire safety</p>
							</figcaption>
						</div>
						<h4>Joel Wong</h4>
						<span>Co-Founder / Fire Instructor</span>
					</figure>
					<!-- end single member #2 -->

					<!-- single member #3 -->
					<figure class="team-member col-md-4 col-sm-6 col-xs-12  wow fadeInUp animated" data-wow-duration="500ms" data-wow-delay="600ms">
						<div class="member-thumb">
							<img src="img/team/Aaron.png" alt="Team Member" class="img-responsive">
							<figcaption class="overlay">
								<h5>About Aaron Chua</h5>
								<p>Born in Singapore</p>
								<p>5+ years of consulting experience</p>
								<p>Advise and consult 20+ companies in workplace fire safety</p>
							</figcaption>
						</div>
						<h4>Aaron Chua</h4>
						<span>Co-Founder / Advisor</span>
					</figure>
					<!-- end single member #3 -->

					<!-- single member #4 -->
					<figure class="team-member col-md-4 col-sm-6 col-xs-12 wow fadeInUp animated" data-wow-duration="500ms" data-wow-delay="900ms">
						<div class="member-thumb">
							<img src="img/team/Zul.png" alt="Team Member" class="img-responsive">
							<figcaption class="overlay">
								<h5>About Zulfadli</h5>
								<p>Born in Singapore</p>
								<p>5+ years of consulting experience</p>
								<p>Offer and conduct fire information courses for 20+ companies</p>

							</figcaption>
						</div>
						<h4>Zulfadli</h4>
						<span>Co-Founder / Advisor</span>
					</figure>
					<!-- end single member #4 -->

					<!-- single member #5 -->
					<figure class="team-member col-md-4 col-sm-6 col-xs-12 wow fadeInUp animated" data-wow-duration="500ms">
						<div class="member-thumb">
							<img src="img/team/Brandon.png" alt="Team Member" class="img-responsive">
							<figcaption class="overlay">
								<h5>About Brandon Neo</h5>
								<p>Born in Singapore</p>
								<p>5+ years of fire-safety instructor experience</p>
								<p>In charge of maintaining and updating FireAway website to the public 24/7!</p>
							</figcaption>
						</div>
						<h4>Brandon Neo</h4>
						<span> Co-Founder / Web Developer </span>
					</figure>
					<!-- end single member #5 -->

				</div>
			</div>
		</section>

        <!--
        End Meet Our Team
        ==================================== -->

		<!--
        Some fun facts
        ==================================== -->

		<section id="facts" class="facts">
			<div class="parallax-overlay">
				<div class="container">
					<div class="row number-counters">

						<div class="sec-title text-center mb50 wow rubberBand animated" data-wow-duration="1000ms">
							<h2>FireAway's Fun Facts</h2>
							<div class="devider"><i class="fa fa-fire fa-lg"></i></div>
						</div>

						<div class="col-md-3 col-sm-6 col-xs-12 text-center wow fadeInUp animated" data-wow-duration="500ms">
							<div class="counters-item">
								<i class="fa fa-trophy fa-3x"></i>
								<strong data-to="50">0</strong> & counting
								<!-- Set Your Number here. i,e. data-to="56" -->
								<p>Awards Won</p>
							</div>
						</div>
						<!-- first count item -->
						<div class="col-md-3 col-sm-6 col-xs-12 text-center wow fadeInUp animated" data-wow-duration="500ms" data-wow-delay="300ms">
							<div class="counters-item">
								<i class="fa fa-users fa-3x"></i>
								<strong data-to="100">0</strong> & counting
								<!-- Set Your Number here. i,e. data-to="56" -->
								<p>Companies Consulted</p>
							</div>
						</div>
						<div class="col-md-3 col-sm-6 col-xs-12 text-center wow fadeInUp animated" data-wow-duration="500ms" data-wow-delay="600ms">
							<div class="counters-item">
								<i class="fa fa-rocket fa-3x"></i>
								<strong data-to="500">0</strong> & counting
								<!-- Set Your Number here. i,e. data-to="56" -->
								<p>Fire Programmes Conducted</p>
							</div>
						</div>
						<div class="col-md-3 col-sm-6 col-xs-12 text-center wow fadeInUp animated" data-wow-duration="500ms" data-wow-delay="900ms">
							<div class="counters-item">
								<i class="fa fa-users fa-3x"></i>
								<strong data-to="1000">0</strong> & counting
								<!-- Set Your Number here. i,e. data-to="56" -->
								<p>Satisfied Clients</p>
							</div>
						</div>
						<!-- end first count item -->

					</div>
				</div>
			</div>
		</section>

        <!--
        End Some fun facts
        ==================================== -->


		<!--
        Contact Us
        ==================================== -->

		<section id="contact" class="contact">
			<div class="container">
				<div class="row mb50">

					<div class="sec-title text-center mb50 wow fadeInDown animated" data-wow-duration="500ms">
						<h2>CONTACT US</h2>
						<div class="devider"><i class="fa fa-fire fa-lg"></i></div>
					</div>

					<div class="sec-sub-title text-center wow rubberBand animated" data-wow-duration="1000ms">
						<p>Our dedicated staff is readily available 24/7 on the go!</p>
					</div>

					<!-- feedback form -->
					<div class="col-lg-12 col-md-12 col-sm-7 col-xs-12 wow fadeInDown animated" data-wow-duration="500ms" data-wow-delay="300ms">
						<div class="contact-form">
							<h3>Enquiries/Feedback</h3>
							<form action="" id="contact-form" method="POST">
								<div class="input-group name-email">
									<div class="input-field">
										<input type="text" name="subject" id="subject" placeholder="Enter the feedback subject" class="form-control" value="">
										<span class="error" id="errorSubject"></span>
									</div>
									<div class="input-field">
										<input type="text" name="fullname" id="fullname" placeholder="Enter your full name" class="form-control" value="">
										<span class="error" id="errorFullName"></span>
									</div>
									<div class="input-field">
										<select id="ddCountryCode" name="ddCountryCode" class="form-select form-control" aria-label="Choose country code">
											<option value="+65" selected>+65 (Singapore/SG)</option>
											<option value="+60">+60 (Malaysia/MY)</option>
											<option value="+62">+62 (Indonesia/ID)</option>
											<option value="+63">+63 (Phillipines/PH)</option>
											<option value="+66">+66 (Thailand/TH)</option>
										</select>
									</div>
									<div class="input-field">
										<input type="text" name="mobile_no" id="mobile_no" placeholder="Enter your mobile number" class="form-control" value="">
										<span class="error" id="errorMobileNo"></span>
									</div>
									<div class="input-field">
										<input type="email" name="email" id="email" placeholder="Enter your email" class="form-control" value="">
										<span class="error" id="errorEmail"></span>
									</div>
									<div class="input-field">
										<select id="ddFeedbackType" name="ddFeedbackType" class="form-select form-control" aria-label="Choose feedback type">
											<option value="Feedback" selected>Feedback</option>
											<option value="Enquiry">Enquiry</option>
										</select>
									</div>
								</div>
								<div class="input-group">
									<textarea name="message" id="message" placeholder="Enter feedback/enquiry content here" class="form-control" value=""></textarea>
									<span class="error" id="errorMessage"></span>
								</div>
								<div class="input-group">
									<input type="submit" name="submitFB" id="submitFB" onclick="return feedbackInsert()" class="pull-right btn btn-success btn-md" value="Submit Feedback">
								</div>
							</form>
						</div>
					</div>
					<!-- end contact form -->
				</div>
			</div>
		</section>

        <!--
        End Contact Us
        ==================================== -->

		<?php include('footer.php'); ?>

		<a href="javascript:void(0);" id="back-top"><i class="fa fa-angle-up fa-3x"></i></a>

		<!-- Essential jQuery Plugins
		================================================== -->
		<!-- Main jQuery -->
        <script src="js/jquery-1.11.1.min.js"></script>
		<!-- Single Page Nav -->
        <script src="js/jquery.singlePageNav.min.js"></script>
		<!-- Twitter Bootstrap -->
        <script src="js/bootstrap.min.js"></script>
		<!-- jquery.fancybox.pack -->
        <script src="js/jquery.fancybox.pack.js"></script>
		<!-- jquery.mixitup.min -->
        <script src="js/jquery.mixitup.min.js"></script>
		<!-- jquery.parallax -->
        <script src="js/jquery.parallax-1.1.3.js"></script>
		<!-- jquery.countTo -->
        <script src="js/jquery-countTo.js"></script>
		<!-- jquery.appear -->
        <script src="js/jquery.appear.js"></script>
        <!-- feedback ajax -->
            <script src="js/feedback.js"></script>
            <script src="js/validationInput.js"></script>
		<!-- Contact form validation -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/3.32/jquery.form.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.11.1/jquery.validate.min.js"></script>
		<!-- Google Map -->
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCcABaamniA6OL5YvYSpB3pFMNrXwXnLwU&libraries=places"></script>
		<!-- jquery easing -->
        <script src="js/jquery.easing.min.js"></script>
		<!-- jquery easing -->
        <script src="js/wow.min.js"></script>
		<script>
			var wow = new WOW ({
				boxClass:     'wow',      // animated element css class (default is wow)
				animateClass: 'animated', // animation css class (default is animated)
				offset:       120,          // distance to the element when triggering the animation (default is 0)
				mobile:       false,       // trigger animations on mobile devices (default is true)
				live:         true        // act on asynchronously loaded content (default is true)
			  }
			);
			wow.init();
		</script>
		<!-- Custom Functions -->
        <script src="js/custom.js"></script>

		<!-- Header Functions -->
        <script type="text/javascript" src="js/header.js"></script>
    </body>
</html>
