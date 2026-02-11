<!DOCTYPE html>
<html lang="zxx">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Bootstrap Min CSS -->
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<!-- Owl Theme Default Min CSS -->
	<link rel="stylesheet" href="assets/css/owl.theme.default.min.css">
	<!-- Owl Carousel Min CSS -->
	<link rel="stylesheet" href="assets/css/owl.carousel.min.css">
	<!-- Animate Min CSS -->
	<link rel="stylesheet" href="assets/css/animate.min.css">
	<!-- Boxicons Min CSS -->
	<link rel="stylesheet" href="assets/css/boxicons.min.css">
	<!-- Magnific Popup Min CSS -->
	<link rel="stylesheet" href="assets/css/magnific-popup.min.css">
	<!-- Flaticon CSS -->
	<link rel="stylesheet" href="assets/css/flaticon.css">
	<!-- Meanmenu Min CSS -->
	<link rel="stylesheet" href="assets/css/meanmenu.min.css">

	<!-- Odometer Min CSS-->
	<link rel="stylesheet" href="assets/css/odometer.min.css">
	<!-- Style CSS -->
	<link rel="stylesheet" href="assets/css/style.css">
	<!-- Dark CSS -->
	<link rel="stylesheet" href="assets/css/dark.css">
	<!-- Responsive CSS -->
	<link rel="stylesheet" href="assets/css/responsive.css">

	<!-- Favicon -->
	<link rel="icon" type="image/png" href="assets/img/logo.png">
	<!-- Bootstrap Icons -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
	<!-- Title -->
	<title>IMHRC</title>
	<style>
		.offices-section {
			background: #fff;
		}
.form-control {
    /* height: 50px; */
    color: #324cc5;
    border: 1px solid #b6b6b6;
    background-color: transparent;
    border-radius: 10px;
    font-size: 16px;
    /* padding: 10px 20px; */
    width: 100%;
}
		.section-title {
			font-weight: 700;
			font-size: 32px;
			letter-spacing: 1px;
			color: #222;
		}

		.office-card {
			background: #ffffff;
			padding: 25px;
			border-radius: 18px;
			text-align: center;
			box-shadow: 0 8px 18px rgba(0, 0, 0, 0.08);
			transition: .3s;
			border: 1px solid #f1f1f1;
			height: 100%;
		}

		.office-card:hover {
			transform: translateY(-7px);
			box-shadow: 0 12px 28px rgba(0, 0, 0, 0.12);
		}

		.office-icon {
			width: 70px;
			height: 70px;
			margin: 0 auto 15px;
			border-radius: 50%;
			display: flex;
			justify-content: center;
			align-items: center;
			color: #fff;
			font-size: 32px;
			box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
		}

		.office-title {
			font-size: 20px;
			font-weight: 700;
			margin-bottom: 8px;
			color: #222;
		}

		.office-text {
			font-size: 15px;
			line-height: 1.7;
			color: #555;
		}
	</style>
</head>

<body>


	<?php include 'includes/header.php'; ?>
	<!-- Start Page Title Area -->
	<div class="page-title-wave">
		<div class="container">
			<h2>Contact Us</h2>
		</div>

		<svg class="wave" viewBox="0 0 1440 320" preserveAspectRatio="none">
			<path fill="#ffffff" fill-opacity="1" d="M0,64L48,90.7C96,117,192,171,288,170.7C384,171,480,117,576,85.3C672,53,768,43,864,74.7C960,107,1056,181,1152,192C1248,203,1344,149,1392,122.7L1440,96L1440,320L1392,320C1344,320,1248,320,
	  1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
			</path>
		</svg>
	</div>

	<section class="offices-section py-5">
		<div class="container">
			<h2 class="section-title text-center mb-4 wow animate__ animate__fadeInDown animated"
				style="visibility: visible; animation-name: fadeInDown;">OUR OFFICES</h2>

			<div class="row g-4">
				<div class="col-lg-6 col-md-6">
					<div class="office-card wow animate__ animate__zoomIn animated"
						style="visibility: visible; animation-name: zoomIn;">
						<div class="office-icon bg-primary">
							<i class="bx bx-buildings"></i>
						</div>
						<h4 class="office-title">HEAD OFFICE</h4>
						<p class="office-text">
							1040 B, Sector C, Sachivalay Colony, Mahanagar, Lucknow, Uttar Pradesh-226006 India</p>
					</div>
				</div>
				<div class="col-lg-6 col-md-6">
					<div class="office-card wow animate__ animate__zoomIn animated"
						style="visibility: visible; animation-name: zoomIn;">
						<div class="office-icon bg-success">
							<i class="bx bx-map"></i>
						</div>
						<h4 class="office-title">REGIONAL OFFICE </h4>
						<p class="office-text">
							117 APR Colony, Katanga, Jabalpur, Madhya Pradesh, India </p>
					</div>
				</div>
			
			</div>
		</div>
	</section>
	<!-- Start Contact Area -->
	<section class="contact-area ptb-100">
		<div class="container">
			<div class="row align-items-center justify-content-center">

				<!-- CONTACT FORM -->
				<div class="col-lg-8">
					<div class="contacts-form">
						<div class="contact-title wow animate__animated animate__zoomInLeft">
							<h2>Drop us a message for any query</h2>
						</div>

						<form id="contactForm" method="post">
							<div class="row justify-content-center">

								<div class="col-lg-6 col-sm-6">
									<div class="form-group">
										<input type="text" name="name" class="form-control" placeholder="Name" required>
									</div>
								</div>

								<div class="col-lg-6 col-sm-6">
									<div class="form-group">
										<input type="email" name="email" class="form-control" placeholder="Email"
											required>
									</div>
								</div>

								<div class="col-lg-6 col-sm-6">
									<div class="form-group">
										<input type="text" name="msg_subject" class="form-control" placeholder="Subject"
											required>
									</div>
								</div>

								<div class="col-lg-6 col-sm-6">
									<div class="form-group">
										<input type="text" name="phone_number" class="form-control" placeholder="Phone"
											required>
									</div>
								</div>

								<div class="col-12">
									<div class="form-group">
										<textarea name="message" class="form-control" cols="30" rows="6"
											placeholder="Message" required></textarea>
									</div>
								</div>

								<div class="col-12">
									<div class="form-group checkboxs">
									<input type="checkbox" id="chb2" required>
										<p>
											Accept <a href="#">Terms & Conditions</a> and <a href="#">Privacy
												Policy</a>.
										</p>
									</div>
								</div>

								<div class="col-lg-12 col-md-12">
									<button type="submit" class="default-btn btn-two">
										<span>Send Message</span>
									</button>
								</div>

							</div>
						</form>
					</div>
				</div>

				<!-- CONTACT INFO RIGHT SIDE -->
				<div class="col-lg-4">

					<ul class="contact-info wow animate__animated animate__zoomInUp">
						<li>
							<i class="bx bxs-map"></i>
							<h3>Address</h3>
							<a href="https://maps.app.goo.gl/d5XLgyZQ7Vvbqrrq7">1040 B, Near Mount Carmel School,
								Sector-C, Mahanagar, Lucknow, Uttar Pradesh 226006</a>
						</li>
						<li>
							<i class="bx bx-envelope"></i>
							<h3>Email Us</h3>
							<a href="mailto:imhrcindia@gmail.com">imhrcindia@gmail.com</a>
						</li>

						<li>
							<i class="bx bx-phone-call"></i>
							<h3>Phone Numbers</h3>
							<a href="tel:+915223190284">+915223190284</a>
							<a href="tel:+91-9044507650">+91-9044507650</a>
						</li>

					</ul>

				</div>

			</div>
		</div>
	</section>

	<!-- Start Map Area -->
	<div class="map-area">
		<iframe
			src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d455546.915927005!2d80.961933!3d26.873003000000004!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x399bfd0775c3d577%3A0xf51448744a4618ea!2sHAUSLA%20Early%20Intervention%20and%20Neuropsychological%20Rehabilitation%20Centre!5e0!3m2!1sen!2sin!4v1766060854204!5m2!1sen!2sin"
			width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
			referrerpolicy="no-referrer-when-downgrade"></iframe>
	</div>
	<!-- End Map Area -->
	<?php include 'includes/footer.php'; ?>

	<!-- Jquery Min JS -->
	<script data-cfasync="false" src="../../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
	<script src="assets/js/jquery.min.js"></script>
	<!-- Bootstrap Bundle Min JS -->
	<script src="assets/js/bootstrap.bundle.min.js"></script>
	<!-- Meanmenu Min JS -->
	<script src="assets/js/meanmenu.min.js"></script>
	<!-- Wow Min JS -->
	<script src="assets/js/wow.min.js"></script>
	<!-- Owl Carousel Min JS -->
	<script src="assets/js/owl.carousel.min.js"></script>
	<!-- Magnific Popup Min JS -->
	<script src="assets/js/magnific-popup.min.js"></script>
	<!-- Jarallax Min JS -->
	<script src="assets/js/jarallax.min.js"></script>
	<!-- Appear Min JS -->
	<script src="assets/js/appear.min.js"></script>
	<!-- Odometer Min JS -->
	<script src="assets/js/odometer.min.js"></script>
	<!-- Smoothscroll Min JS -->
	<script src="assets/js/smoothscroll.min.js"></script>
	<!-- Form Validator Min JS -->
	<script src="assets/js/form-validator.min.js"></script>
	<!-- Contact JS -->
	<script src="assets/js/contact-form-script.js"></script>
	<!-- Ajaxchimp Min JS -->
	<script src="assets/js/ajaxchimp.min.js"></script>
	<!-- Custom JS -->
	<script src="assets/js/custom.js"></script>
<script>
document.getElementById("contactForm").addEventListener("submit", function(e){
    e.preventDefault();

    let formData = new FormData(this);

    fetch("contact-submit.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(data => {
        alert(data);
        document.getElementById("contactForm").reset();
    })
    .catch(err => {
        alert("Something went wrong. Try again!");
    });
});
</script>

</body>


</html>