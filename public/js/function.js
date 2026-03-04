(function ($) {
	"use strict";

	var $window = $(window);
	var $body = $('body');

	/* Preloader Effect */
	$window.on('load', function () {
		$(".preloader").fadeOut(600);
	});

	/* Sticky Header */
	if ($('.active-sticky-header').length) {
		$window.on('resize', function () {
			setHeaderHeight();
		});

		function setHeaderHeight() {
			$("header.main-header").css("height", $('header .header-sticky').outerHeight());
		}

		$(window).on("scroll", function () {
			var fromTop = $(window).scrollTop();
			setHeaderHeight();
			var headerHeight = $('header .header-sticky').outerHeight()
			$("header .header-sticky").toggleClass("hide", (fromTop > headerHeight + 100));
			$("header .header-sticky").toggleClass("active", (fromTop > 600));
		});
	}

	/* Slick Menu JS */
	$('#menu').slicknav({
		label: '',
		prependTo: '.responsive-menu'
	});

	if ($("a[href='#top']").length) {
		$("a[href='#top']").click(function () {
			$("html, body").animate({ scrollTop: 0 }, "slow");
			return false;
		});
	}

	/* Typed subtitle */
	if ($('.typed-title').length) {
		$('.typed-title').typed({
			stringsElement: $('.typing-title'),
			backDelay: 2000,
			typeSpeed: 0,
			loop: true
		});
	}

	/* Hero Slider Layout JS */
	const $heroSlider = $('.hero-slider-layout .swiper');
	if ($heroSlider.length) {
		const hero_loop = $heroSlider.find('.swiper-slide').length > 1;
		const hero_slider_layout = new Swiper('.hero-slider-layout .swiper', {
			effect: 'fade',
			slidesPerView: 1,
			speed: 1000,
			spaceBetween: 0,
			loop: hero_loop,
			autoplay: hero_loop ? {
				delay: 4000,
			} : false,
			pagination: {
				el: '.hero-pagination',
				clickable: true,
			},
		});
	}

	if ($('.agency-supports-slider').length) {
		const $suppSlider = $('.agency-supports-slider .swiper');
		const supp_loop = $suppSlider.find('.swiper-slide').length > 6;
		const agency_supports_slider = new Swiper('.agency-supports-slider .swiper', {
			slidesPerView: 2,
			speed: 2000,
			spaceBetween: 30,
			loop: supp_loop,
			autoplay: supp_loop ? {
				delay: 1000,
			} : false,
			breakpoints: {
				768: {
					slidesPerView: 4,
				},
				991: {
					slidesPerView: 6,
				}
			}
		});
	}

	/* testimonial Slider JS */
	if ($('.testimonial-slider').length) {
		const testimonial_slider = new Swiper('.testimonial-slider .swiper', {
			slidesPerView: 1,
			speed: 1000,
			spaceBetween: 30,
			loop: true,
			autoplay: {
				delay: 5000,
			},
			pagination: {
				el: '.swiper-pagination',
				clickable: true,
			},
			navigation: {
				nextEl: '.testimonial-button-next',
				prevEl: '.testimonial-button-prev',
			},
			breakpoints: {
				768: {
					slidesPerView: 1,
				},
				991: {
					slidesPerView: 1,
				}
			}
		});
	}

	/* Youtube Background Video JS */
	if ($('#herovideo').length) {
		var myPlayer = $("#herovideo").YTPlayer();
	}

	/* Init Counter */
	if ($('.counter').length) {
		$('.counter').counterUp({ delay: 6, time: 3000 });
	}

	/* Image Reveal Animation */
	if ($('.reveal').length) {
		gsap.registerPlugin(ScrollTrigger);
		let revealContainers = document.querySelectorAll(".reveal");
		revealContainers.forEach((container) => {
			let image = container.querySelector("img");
			let tl = gsap.timeline({
				scrollTrigger: {
					trigger: container,
					toggleActions: "play none none none"
				}
			});
			tl.set(container, {
				autoAlpha: 1
			});
			tl.from(container, 1, {
				xPercent: -100,
				ease: Power2.out
			});
			tl.from(image, 1, {
				xPercent: 100,
				scale: 1,
				delay: -1,
				ease: Power2.out
			});
		});
	}

	/* Text Effect Animation */
	if ($('.text-anime-style-1').length) {
		let staggerAmount = 0.05,
			translateXValue = 0,
			delayValue = 0.5,
			animatedTextElements = document.querySelectorAll('.text-anime-style-1');

		animatedTextElements.forEach((element) => {
			let animationSplitText = new SplitText(element, { type: "chars, words" });
			gsap.from(animationSplitText.words, {
				duration: 1,
				delay: delayValue,
				x: 20,
				autoAlpha: 0,
				stagger: staggerAmount,
				scrollTrigger: { trigger: element, start: "top 85%" },
			});
		});
	}

	if ($('.text-anime-style-2').length) {
		let staggerAmount = 0.03,
			translateXValue = 20,
			delayValue = 0.1,
			easeType = "power2.out",
			animatedTextElements = document.querySelectorAll('.text-anime-style-2');

		animatedTextElements.forEach((element) => {
			let animationSplitText = new SplitText(element, { type: "chars, words" });
			gsap.from(animationSplitText.chars, {
				duration: 1,
				delay: delayValue,
				x: translateXValue,
				autoAlpha: 0,
				stagger: staggerAmount,
				ease: easeType,
				scrollTrigger: { trigger: element, start: "top 85%" },
			});
		});
	}

	if ($('.text-anime-style-3').length) {
		let animatedTextElements = document.querySelectorAll('.text-anime-style-3');

		animatedTextElements.forEach((element) => {
			//Reset if needed
			if (element.animation) {
				element.animation.progress(1).kill();
				element.split.revert();
			}

			element.split = new SplitText(element, {
				type: "lines,words,chars",
				linesClass: "split-line",
			});
			gsap.set(element, { perspective: 400 });

			gsap.set(element.split.chars, {
				opacity: 0,
				x: "50",
			});

			element.animation = gsap.to(element.split.chars, {
				scrollTrigger: { trigger: element, start: "top 90%" },
				x: "0",
				y: "0",
				rotateX: "0",
				opacity: 1,
				duration: 1,
				ease: Back.easeOut,
				stagger: 0.02,
			});
		});
	}

	/* Parallaxie js */
	var $parallaxie = $('.parallaxie');
	if ($parallaxie.length && ($window.width() > 991)) {
		if ($window.width() > 768) {
			$parallaxie.parallaxie({
				speed: 0.55,
				offset: 0,
			});
		}
	}

	/* Zoom Gallery screenshot */
	$('.gallery-items').magnificPopup({
		delegate: 'a',
		type: 'image',
		closeOnContentClick: false,
		closeBtnInside: false,
		mainClass: 'mfp-with-zoom',
		image: {
			verticalFit: true,
		},
		gallery: {
			enabled: true
		},
		zoom: {
			enabled: true,
			duration: 300, // don't foget to change the duration also in CSS
			opener: function (element) {
				return element.find('img');
			}
		}
	});

	/* Contact form validation */
	var $contactform = $("#contactForm");
	$contactform.validator({ focus: false }).on("submit", function (event) {
		if (!event.isDefaultPrevented()) {
			event.preventDefault();
			submitForm();
		}
	});

	function submitForm() {
		/* Ajax call to submit form */
		$.ajax({
			type: "POST",
			url: "form-process.php",
			data: $contactform.serialize(),
			success: function (text) {
				if (text == "success") {
					formSuccess();
				} else {
					submitMSG(false, text);
				}
			}
		});
	}

	function formSuccess() {
		$contactform[0].reset();
		submitMSG(true, "Message Sent Successfully!")
	}

	function submitMSG(valid, msg) {
		if (valid) {
			var msgClasses = "h4 text-success";
		} else {
			var msgClasses = "h4 text-danger";
		}
		$("#msgSubmit").removeClass().addClass(msgClasses).text(msg);
	}
	/* Contact form validation end */


	/************************************/
	/* 02. Theme Toggle with Persistence */
	/************************************/
	(function () {
		const savedTheme = localStorage.getItem("theme");
		if (savedTheme) {
			document.documentElement.setAttribute("data-theme", savedTheme);
		}
	})();

	document.addEventListener("DOMContentLoaded", function () {
		const themeToggle = document.getElementById("themeToggle");
		if (themeToggle) {
			themeToggle.addEventListener("click", () => {
				const current = document.documentElement.getAttribute("data-theme") || "dark";
				const nextTheme = current === "light" ? "dark" : "light";
				document.documentElement.setAttribute("data-theme", nextTheme);
				localStorage.setItem("theme", nextTheme);
			});
		}
	});



	/* Our Project (filtering) Start */

	$(window).on("load", function () {
		const $container = $(".project-item-boxes");

		if ($container.length) {
			$container.imagesLoaded(function () {
				const $grid = $container.isotope({
					itemSelector: ".project-item-box",
					layoutMode: "fitRows"
				});

				const $filters = $(".our-Project-nav li a");

				// Initial filter to show all
				$grid.isotope({ filter: "*" });

				// Click filter links
				$filters.on("click", function (e) {
					const filterValue = $(this).attr("data-filter");
					if (!filterValue) return; // Allow normal link behavior if no data-filter exists

					e.preventDefault();

					$filters.removeClass("active-btn");
					$(this).addClass("active-btn");

					$grid.isotope({ filter: filterValue });
				});
			});
		}
	});
	/* Our Project (filtering) End */


	/* Our shop (filtering) Start */
	$window.on("load", function () {
		if ($(".project-item-boxes2").length) {
			var $container2 = $(".project-item-boxes2");

			$container2.imagesLoaded(function () {
				var $menuitem2 = $container2.isotope({
					itemSelector: ".project-item-box2",
					layoutMode: "masonry",
					masonry: {
						columnWidth: 1,
					}
				});

				var $menudisesnav2 = $(".our-Project-nav2 li a");

				// Initial filter: Show all items
				$menuitem2.isotope({ filter: "*" });

				// Filtering on click
				$menudisesnav2.on('click', function (e) {
					e.preventDefault();

					var filterValue = $(this).attr('data-filter');
					$menuitem2.isotope({ filter: filterValue });

					$menudisesnav2.removeClass("active-btn2");
					$(this).addClass("active-btn2");
				});
			});
		}
	});
	/* Our shop (filtering) End */
	/* Animated Wow Js */
	new WOW().init();

	/* Popup Video */
	if ($('.popup-video').length) {
		$('.popup-video').magnificPopup({
			type: 'iframe',
			mainClass: 'mfp-fade',
			removalDelay: 160,
			preloader: false,
			fixedContentPos: true
		});
	}

	/* Why Choose us active Start */
	if ($('.why-choose-content').length) {
		var element = $('.why-choose-content');
		var items = element.find('.why-choose-item');
		if (items.length) {
			items.on({
				mouseenter: function () {
					if ($(this).hasClass('active')) return;

					items.removeClass('active');
					$(this).addClass('active');

				},
				mouseleave: function () {
					//stuff to do on mouse leave
				}
			});
		}
	}
	/* Why Choose us active End */


	$window.on("load", function () {
		/* Init Swiper (unique container, using default wrapper/slide classes) */
		const $portSlider = $('.portfolio-swiper');
		if ($portSlider.length) {
			const port_loop = $portSlider.find('.swiper-slide').length > 4;
			const portfolioSwiper = new Swiper('.portfolio-swiper', {
				centeredSlides: true,
				loop: port_loop,
				speed: 500,
				slidesPerView: 1.5,
				spaceBetween: 40,
				autoplay: port_loop ? {
					delay: 3000,
					disableOnInteraction: false,
				} : false,
				navigation: {
					nextEl: '.portfolio-next',
					prevEl: '.portfolio-prev',
				},
				breakpoints: {
					640: { slidesPerView: 2.5 },
					768: { slidesPerView: 2.75 },
					1080: { slidesPerView: 3.25 },
					1280: { slidesPerView: 3.5 },
				},
			});
		}
	})

})(jQuery);