<!doctype html>
<html class="no-js" lang="zxx">

<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title>Jacknails palour</title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Place favicon.ico in the root directory -->
	<link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">
	<link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
	<script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
	{{-- Vite compiled assets --}}
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/1.4.4/base.css"
		integrity="sha512-veGx+J43NpWEe7Wu4frNIGezyfKVUadPLTk8xHZ55bg0+iPQZ18fKLa+peEJD3UyWP2I5C+pIKuYun/NU/SZCg=="
		crossorigin="anonymous" referrerpolicy="no-referrer" />
		<link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
		<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
	@vite(['resources/css/app.css', 'resources/js/app.js'])
	{{-- Google Fonts --}}
<!-- Load jQuery first -->
<script src="{{ asset('js/vendor/jquery.js') }}"></script>
<script src="{{ asset('js/range-slider.js') }}"></script>
<script src="{{ asset(path: 'js/range-slider.js') }}"></script>
<script src="{{ asset('js/swiper-bundle.js') }}"></script>
<script src="{{ asset('js/slick.js') }}"></script>
<script src="{{ asset('js/magnific-popup.js') }}"></script>
<script src="{{ asset('js/nice-select.js') }}"></script>
<script src="{{ asset('js/purecounter.js') }}"></script>
<script src="{{ asset('js/beforeafter.js') }}"></script>
<script src="{{ asset('js/isotope-pkgd.js') }}"></script>
<script src="{{ asset('js/imagesloaded-pkgd.js') }}"></script>
<script src="{{ asset('js/ajax-form.js') }}"></script>
<script src="{{ asset('js/webgl.js') }}"></script> <!-- Make sure "WebGL" var isn't duplicated -->
<script src="{{ asset('js/tp-cursor.js') }}"></script> 
<script src="{{asset('js/main.js')}}"></script>
<script src="{{ asset('js/counterup.js') }}"></script>


{{--
<script src="{{ asset('/vendor/js/main.js') }}"></script> --}}

{{-- 
<!-- Then your dependent scripts -->
<script src="{{ asset('js/vendor/bootstrap-bundle.js') }}"></script> <!-- if needed -->

<!-- Your custom/project JS -->
<script src="{{ asset('js/range-slider.js') }}"></script>
<script src="{{ asset('js/swiper-bundle.js') }}"></script>
<script src="{{ asset('js/slick.js') }}"></script>
<script src="{{ asset('js/magnific-popup.js') }}"></script>
<script src="{{ asset('js/nice-select.js') }}"></script>
<script src="{{ asset('js/purecounter.js') }}"></script>
<script src="{{ asset('js/beforeafter.js') }}"></script>
<script src="{{ asset('js/isotope-pkgd.js') }}"></script>
<script src="{{ asset('js/imagesloaded-pkgd.js') }}"></script>
<script src="{{ asset('js/ajax-form.js') }}"></script>
<script src="{{ asset('js/webgl.js') }}"></script> <!-- Make sure "WebGL" var isn't duplicated -->
<script src="{{ asset('js/tp-cursor.js') }}"></script> --}}

{{-- <script src="{{ asset('/vendor/js/main.js') }}"></script> --}}

	{{-- Public CSS assets (served from public/assets/css) --}}
	<link rel="stylesheet" href="{{ asset('/css/bootstrap.css') }}">
	<link rel="stylesheet" href="{{ asset('/css/animate.css') }}">
	<link rel="stylesheet" href="{{ asset('/css/swiper-bundle.css') }}">
	<link rel="stylesheet" href="{{ asset('/css/slick.css') }}">
	<link rel="stylesheet" href="{{ asset('/css/magnific-popup.css') }}">
	<link rel="stylesheet" href="{{ asset('/css/font-awesome-pro.css') }}">
	<link rel="stylesheet" href="{{ asset('/css/spacing.css') }}">
	<link rel="stylesheet" href="{{ asset('/css/custom-animation.css') }}">
	<link rel="stylesheet" href="{{ asset('/css/main.css') }}">

</head>

<body id="body" class="tp-magic-cursor" class="tp-smooth-scroll">

	<!-- Begin magic cursor  
   ======================== -->
	<div id="magic-cursor">
		<div id="ball"></div>
	</div>
	<!-- End magic cursor -->

	<!-- pre loader area start -->
	<div id="loading" class="preloader-wrap">
		<div class="preloader-2 text-center">
			<span class="line line-1"></span>
			<span class="line line-2"></span>
			<span class="line line-3"></span>
			<span class="line line-4"></span>
			<span class="line line-5"></span>
			<span class="line line-6"></span>
			<span class="line line-7"></span>
			<span class="line line-8"></span>
			<span class="line line-9"></span>
			<div class="loader-text">Loading ...</div>
		</div>
	</div>
	<!-- pre loader area end -->

	<!-- back to top start -->
	<div class="back-to-top-wrapper">
		<button id="back_to_top" type="button" class="back-to-top-btn">
			<svg width="12" height="7" viewBox="0 0 12 7" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M11 6L6 1L1 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
					stroke-linejoin="round" />
			</svg>
		</button>
	</div>
	<!-- back to top end -->

	<!-- offcanvas area end -->
	<div class="tp-offcanvas-2-area p-relative">
		<div class="tp-offcanvas-2-bg is-left left-box"></div>
		<div class="tp-offcanvas-2-bg is-right right-box d-none d-md-block"></div>
		<div class="tp-offcanvas-2-wrapper">
			<div class="tp-offcanvas-2-left left-box">
				<div class="tp-offcanvas-2-left-wrap d-flex justify-content-between align-items-center">
					<div class="tpoffcanvas__logo">
						<a class="logo-1" href="index.php"><img src="assets/img/logo/logo.png" alt=""></a>
						<a class="logo-2" href="index.php"><img src="assets/img/logo/logo-white.png" alt=""></a>
					</div>
					<div class="tp-offcanvas-2-close d-md-none text-end">
						<button class="tp-offcanvas-2-close-btn tp-offcanvas-2-close-btn">
							<span class="text">
								<span>close</span>
							</span>
							<span class="d-inline-block">
								<span>
									<svg width="24" height="24" viewBox="0 0 24 24" fill="none"
										xmlns="http://www.w3.org/2000/svg">
										<rect width="32.621" height="1.00918"
											transform="matrix(0.704882 0.709325 -0.704882 0.709325 1.0061 0)"
											fill="currentcolor" />
										<rect width="32.621" height="1.00918"
											transform="matrix(0.704882 -0.709325 0.704882 0.709325 0 23.2842)"
											fill="currentcolor" />
									</svg>
								</span>
							</span>

						</button>
					</div>
				</div>
				<div class="tp-main-menu-mobile menu-hover-active counter-row">
					<nav></nav>
				</div>
			</div>
			<div class="tp-offcanvas-2-right right-box d-none d-md-block p-relative">
				<div class="tp-offcanvas-2-close text-end">
					<button class="tp-offcanvas-2-close-btn">
						<span class="text">
							<span>close</span>
						</span>

						<span class="d-inline-block">
							<span>
								<svg width="38" height="38" viewBox="0 0 38 38" fill="none"
									xmlns="http://www.w3.org/2000/svg">
									<path d="M9.80859 9.80762L28.1934 28.1924" stroke="currentColor" stroke-width="1.5"
										stroke-linecap="round" stroke-linejoin="round" />
									<path d="M9.80859 28.1924L28.1934 9.80761" stroke="currentColor" stroke-width="1.5"
										stroke-linecap="round" stroke-linejoin="round" />
								</svg>
							</span>
						</span>

					</button>
				</div>
				<div class="tp-offcanvas-2-right-inner d-flex flex-column justify-content-between h-100">
					<div class="tpoffcanvas__right-info">
						<div class="tpoffcanvas__tel">
							<a href="tel:61404093954">+61404093 954</a>
						</div>
						<div class="tpoffcanvas__mail">
							<a href="mailto:hellocontact@diego.com">
								Jacknails.com</a>
						</div>
						<div class="tpoffcanvas__text">
							<p>If in doubt. reach out.</p>
						</div>
					</div>
					<div class="tpoffcanvas__social-link">
						<ul>
							<li><a href="#">Dribbble</a></li>
							<li><a href="#">Instagram</a></li>
							<li><a href="#">Linkedin</a></li>
							<li><a href="#">Behance</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- offcanvas area start -->

	<div id="smooth-wrapper">
		<div id="smooth-content">

			<header>

				<!-- header top area start -->
				<div class="tp-header-2-area tp-header-2-space tp-transparent">
					<div class="container container-1840">
						<div class="row align-items-center">
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-6">
								<div class="tp-header-logo">
									<a class="logo-1" href="index.php">
										<h1>Jacknails</h1>
									</a>
									<a class="logo-2" href="index.php">
										<h1>Jacknails</h1>
									</a>
								</div>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-6">
								<div class="tp-header-2-menu-bar text-end text-sm-center">
									<button class="tp-offcanvas-open-btn">
										<span></span>
										<span></span>
									</button>
									<div class="d-none">
										<nav class="tp-main-menu-content">
											<ul>
												<li class="has-dropdown">
													<a href="index.php">Home</a>
													<ul class="tp-submenu submenu">
														<li><a href="index.php">MAIN HOME</a></li>
														<!-- <li><a href="index-2.html">Fashion STUDIO</a></li>
                                       <li><a href="index-4.html">CREATIVE AGENCY</a></li>
                                       <li><a href="index-3.html">Digital Agency</a></li>
                                       <li><a href="index-5.html">DESIGN STUDIO</a></li>
                                       <li><a href="index-shop.html">Minimal Shop</a></li>
                                       <li><a href="index-9.html">DESIGN STUDIO</a></li>
                                       <li><a href="index-10.html">showcase carousel</a></li>
                                       <li><a href="index-11.html">INTERACTIVE LINKS</a></li>
                                       <li><a href="index-11.html">wrapper slider</a></li>
                                       <li><a href="portfolio-showcase-2.html">showcase parallax</a></li>
                                       <li><a href="index-12.html">horizontal</a></li> -->

													</ul>
												</li>
												<li class="has-dropdown">
													<a href="about-me.html">about-me</a>
												</li>
												<li class="has-dropdown">
													<a href="service.html">our services</a>
												</li>
												<li class="has-dropdown">
													<a href="contact.html">Contact</a>
													<ul class="tp-submenu submenu">
														<li><a href="contact-2.html">Contact</a></li>
														<li><a href="contact.html">Get IN touch</a></li>
													</ul>
												</li>
											</ul>
										</nav>
									</div>
								</div>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-6 d-none d-sm-block">
								<div class="tp-header-2-btn-box text-end">
									<div class="tp-header-2-button">
										<a class="tp-btn-animation" href="contact.html">
											<span>Get In Touch</span>
											<span>Get In Touch</span>
											<span>Get In Touch</span>
											<span>Get In Touch</span>
											<span>Get In Touch</span>
											<span>Get In Touch</span>
											<span>Get In Touch</span>
											<span>Get In Touch</span>
											<span>Get In Touch</span>
											<span>Get In Touch</span>
											<span>Get In Touch</span>
											<span>Get In Touch</span>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- header top area end -->

			</header>
@yield('content')
<footer>

	<!-- footer area start -->
	<div class="tp-footer-5-area black-bg pt-120 pb-120">
		<div class="container">
			<div class="row">
				<div class="col-xl-12">
					<div class="tp-footer-5-content-wrap">
						<div class="tp-footer-5-title-box">
							<span class="tp-footer-5-subtitle">Want your <br> nails Done</span>
							<h4 class="tp-footer-5-title footer-big-text">Let's Talk</h4>
						</div>
						<div
							class="tp-footer-5-info d-flex align-items-center justify-content-start justify-content-md-end">
							<a class="tp-footer-5-mail" href="mailto:info@Jacknails.co.ke">
								info@Jacknails.co.ke
							</a>
														<a  class="tp-footer-5-mail"
														href="tel:+254725214456">0725 214 456</a>

							<a class="tp-footer-5-link" href="#">
								<svg width="12" height="12" viewBox="0 0 12 12" fill="none"
									xmlns="http://www.w3.org/2000/svg">
									<path d="M1 11L11 1" stroke="#19191A" stroke-width="2" stroke-linecap="round"
										stroke-linejoin="round" />
									<path d="M1 1H11V11" stroke="#19191A" stroke-width="2" stroke-linecap="round"
										stroke-linejoin="round" />
								</svg>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- footer area end -->

	<!-- copyright area start -->
	<div class="tp-copyright-5-area tp-copyright-5-style-2 black-bg pb-50">
		<div class="container container-1560">
			<div class="row align-items-center">
				<div class="col-xl-3 col-lg-6 col-md-5 d-none d-xl-block">
					<div class="tp-copyright-5-left-info">
						<span>
							<a href="https://www.google.com/maps/@40.1001598,-74.0544407,8.83z?entry=ttu"
								target="_blank">Danka plaza kitengela</a>
						</span>
						<span>
							Phone:
							<a href="tel:+254725214456">0725 214 456</a>
						</span>
					</div>
				</div>
				<div class="col-xl-6 col-lg-6 col-md-7">
					<div class="tp-copyright-2-social text-start text-sm-center text-xl-center">
						<a class="mb-10" href="#">Linkedin</a>
						<a class="mb-10" href="#">Twitter</a>
						<a class="mb-10" href="https://www.instagram.com/jacknails254/">Instagram</a>
					</div>
				</div>
				<div class="col-xl-3 col-lg-6 col-md-5">
					<div class="tp-copyright-2-left text-center text-md-end">
						<p><script>
							document.write(new Date().getFullYear() + " &copy; Jacknails palour" +
								" - All Rights Reserved");
							</script>- </p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- copyright area end -->

</footer>

</div>
</div>


{{-- 
<!-- JS here -->
<script src="assets/js/vendor/jquery.js"></script>
<script src="assets/js/bootstrap-bundle.js"></script>

<script src="assets/js/gsap.js"></script>
<script src="assets/js/gsap-scroll-to-plugin.js"></script>
<script src="assets/js/gsap-scroll-smoother.js"></script>
<script src="assets/js/gsap-scroll-trigger.js"></script>
<script src="assets/js/gsap-split-text.js"></script>
<script src="assets/js/chroma.min.js"></script>

<script src='assets/js/three.js'></script>
<script src='assets/js/tween-max.js'></script>
<script src='assets/js/scroll-magic.js'></script>

<script src="{{ asset('js/range-slider.js') }}"></script>
<script src="{{ asset('js/swiper-bundle.js') }}"></script>
<script src="{{ asset('js/slick.js') }}"></script>
<script src="{{ asset('js/magnific-popup.js') }}"></script>
<script src="{{ asset('js/nice-select.js') }}"></script>
<script src="{{ asset('js/purecounter.js') }}"></script>
<script src="{{ asset('js/beforeafter.js') }}"></script>
<script src="{{ asset('js/isotope-pkgd.js') }}"></script>
<script src="{{ asset('js/imagesloaded-pkgd.js') }}"></script>
<script src="{{ asset('js/ajax-form.js') }}"></script>
<script src="{{ asset('js/webgl.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
<script src="{{ asset('js/tp-cursor.js') }}"></script> --}}


</body>

</html>