// ============================================
// Bean & Brew Cafe - Main JavaScript
// ============================================

document.addEventListener('DOMContentLoaded', function () {

    // ========== PRELOADER ==========
    const preloader = document.getElementById('preloader');
    if (preloader) {
        window.addEventListener('load', function () {
            setTimeout(function () {
                preloader.classList.add('hide');
            }, 800);
        });
        // Fallback: hide after 3 seconds
        setTimeout(function () {
            preloader.classList.add('hide');
        }, 3000);
    }

    // ========== INITIALIZE AOS ==========
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            offset: 100
        });
    }

    // ========== NAVBAR SCROLL EFFECT ==========
    const navbar = document.getElementById('navbar');
    function handleNavbarScroll() {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    }
    window.addEventListener('scroll', handleNavbarScroll);
    handleNavbarScroll(); // Run on load

    // ========== MOBILE NAV TOGGLE ==========
    const navToggle = document.getElementById('navToggle');
    const navMenu = document.getElementById('navMenu');

    if (navToggle && navMenu) {
        navToggle.addEventListener('click', function () {
            navToggle.classList.toggle('active');
            navMenu.classList.toggle('active');
        });

        // Close menu when clicking a link
        document.querySelectorAll('.nav-link').forEach(function (link) {
            link.addEventListener('click', function () {
                navToggle.classList.remove('active');
                navMenu.classList.remove('active');
            });
        });

        // Close menu when clicking outside
        document.addEventListener('click', function (e) {
            if (!navToggle.contains(e.target) && !navMenu.contains(e.target)) {
                navToggle.classList.remove('active');
                navMenu.classList.remove('active');
            }
        });
    }

    // ========== HERO IMAGE SLIDER ==========
    const heroSlides = document.querySelectorAll('.hero-slide');
    let currentSlide = 0;

    function showSlide(index) {
        heroSlides.forEach(function (slide) {
            slide.classList.remove('active');
        });
        if (heroSlides[index]) {
            heroSlides[index].classList.add('active');
        }
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % heroSlides.length;
        showSlide(currentSlide);
    }

    if (heroSlides.length > 0) {
        showSlide(0);
        setInterval(nextSlide, 5000);
    }

    // ========== BACK TO TOP BUTTON ==========
    const backToTop = document.getElementById('backToTop');
    if (backToTop) {
        window.addEventListener('scroll', function () {
            if (window.scrollY > 400) {
                backToTop.classList.add('visible');
            } else {
                backToTop.classList.remove('visible');
            }
        });

        backToTop.addEventListener('click', function (e) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // ========== MENU FILTER ==========
    const filterBtns = document.querySelectorAll('.menu-filter-btn');
    const menuItems = document.querySelectorAll('.menu-item');

    filterBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            // Update active button
            filterBtns.forEach(function (b) {
                b.classList.remove('active');
            });
            btn.classList.add('active');

            const category = btn.getAttribute('data-category');

            menuItems.forEach(function (item) {
                if (category === 'all' || item.getAttribute('data-category') === category) {
                    item.style.display = 'block';
                    item.style.animation = 'fadeInUp 0.5s ease forwards';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    // ========== ORDER PAGE: QUANTITY CONTROLS ==========
    document.querySelectorAll('.qty-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const action = this.getAttribute('data-action');
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            let value = parseInt(input.value);

            if (action === 'increase') {
                value++;
            } else if (action === 'decrease' && value > 1) {
                value--;
            }

            input.value = value;
            updateOrderSummary();
        });
    });

    // Checkbox change for order items
    document.querySelectorAll('.order-item-checkbox').forEach(function (cb) {
        cb.addEventListener('change', function () {
            updateOrderSummary();
        });
    });

    function updateOrderSummary() {
        let subtotal = 0;
        const checkedItems = document.querySelectorAll('.order-item-checkbox:checked');

        checkedItems.forEach(function (cb) {
            const row = cb.closest('.order-item-select');
            if (row) {
                const price = parseFloat(row.querySelector('.item-price').getAttribute('data-price'));
                const qty = parseInt(row.querySelector('.item-qty-input').value);
                subtotal += price * qty;
            }
        });

        const deliveryFee = subtotal > 0 ? 350 : 0;
        const total = subtotal + deliveryFee;

        const subtotalEl = document.getElementById('orderSubtotal');
        const deliveryEl = document.getElementById('orderDelivery');
        const totalEl = document.getElementById('orderTotal');

        if (subtotalEl) subtotalEl.textContent = 'Rs. ' + subtotal.toFixed(2);
        if (deliveryEl) deliveryEl.textContent = 'Rs. ' + deliveryFee.toFixed(2);
        if (totalEl) totalEl.textContent = 'Rs. ' + total.toFixed(2);
    }

    // ========== COUNTER ANIMATION ==========
    function animateCounters() {
        const counters = document.querySelectorAll('.counter');

        counters.forEach(function (counter) {
            const target = parseInt(counter.getAttribute('data-target'));
            const increment = target / 60;
            let current = 0;

            const timer = setInterval(function () {
                current += increment;
                if (current >= target) {
                    counter.textContent = target;
                    clearInterval(timer);
                } else {
                    counter.textContent = Math.floor(current);
                }
            }, 30);
        });
    }

    // ========== SMOOTH SCROLL ==========
    document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;

            const target = document.querySelector(targetId);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

    // ========== TYPING EFFECT ==========
    const typingElement = document.querySelector('.typing-text');
    if (typingElement) {
        const texts = ['Perfect Coffee', 'Great Ambience', 'Fresh Pastries', 'Friendly Service'];
        let textIndex = 0;
        let charIndex = 0;
        let isDeleting = false;

        function type() {
            const currentText = texts[textIndex];

            if (isDeleting) {
                typingElement.textContent = currentText.substring(0, charIndex - 1);
                charIndex--;
            } else {
                typingElement.textContent = currentText.substring(0, charIndex + 1);
                charIndex++;
            }

            let typeSpeed = isDeleting ? 50 : 100;

            if (!isDeleting && charIndex === currentText.length) {
                typeSpeed = 2000;
                isDeleting = true;
            } else if (isDeleting && charIndex === 0) {
                isDeleting = false;
                textIndex = (textIndex + 1) % texts.length;
                typeSpeed = 500;
            }

            setTimeout(type, typeSpeed);
        }
        type();
    }

    // ========== PARALLAX EFFECT ==========
    window.addEventListener('scroll', function () {
        const parallaxElements = document.querySelectorAll('.parallax-section');
        parallaxElements.forEach(function (el) {
            const scrolled = window.scrollY;
            const rate = scrolled * -0.3;
            el.style.backgroundPositionY = rate + 'px';
        });
    });

});