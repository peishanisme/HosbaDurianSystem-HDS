<script src="{{ secure_asset('assets/site/js/main.js') }}"></script>
<script src="//unpkg.com/alpinejs" defer></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.umd.min.js"></script>
<script src="https://res.gemcoder.com/js/reload.js"></script>

<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: '#6A994E',
                    secondary: '#A7C957',
                    accent: '#F2E8CF',
                    goldLight: '#F7F3E3',
                    neutral: '#FEFAF1',
                    dark: '#386641'
                },
                fontFamily: {
                    sans: ['Inter', 'system-ui', 'sans-serif']
                }
            }
        }
    };
</script>

<script id="navbar-scroll">
    document.addEventListener('DOMContentLoaded', function() {
        var header = document.querySelector('header');
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                header.classList.add('py-2', 'shadow-md');
                header.classList.remove('py-4', 'shadow-sm');
            } else {
                header.classList.add('py-4', 'shadow-sm');
                header.classList.remove('py-2', 'shadow-md');
            }
        });

        if (window.scrollY > 50) {
            header.classList.add('py-2', 'shadow-md');
            header.classList.remove('py-4', 'shadow-sm');
        }
    });
</script>

<script id="mobile-menu-handler">
    document.addEventListener('DOMContentLoaded', function() {
        var menuButton = document.querySelector('header button.md\\:hidden');
        var mobileMenu = null;

        function createMobileMenu() {
            if (mobileMenu) return;
            mobileMenu = document.createElement('div');
            mobileMenu.className =
                'fixed inset-0 bg-white z-50 transform translate-x-full transition-transform duration-300 ease-in-out';
            mobileMenu.innerHTML =
                "<div class=\"flex justify-end p-4\">" +
                "<button id=\"close-menu\" class=\"text-gray-700\">" +
                "<i class=\"fas fa-times text-xl\"></i>" +
                "</button>" +
                "</div>" +
                "<nav class=\"flex flex-col items-center space-y-6 mt-12 text-xl\">" +
                "<a href=\"javascript:void(0);\" onclick=\"scrollToSection('home')\" class=\"moss-green hover:text-primary transition-colors\">Home</a>" +
                "<a href=\"javascript:void(0);\" onclick=\"scrollToSection('product-details')\" class=\"moss-green hover:text-primary transition-colors\">Product Details</a>" +
                "<a href=\"javascript:void(0);\" onclick=\"scrollToSection('feedback-section')\" class=\"moss-green hover:text-primary transition-colors\">Feedback</a>" +
                "<a href=\"javascript:void(0);\" onclick=\"scrollToSection('about-us')\" class=\"moss-green hover:text-primary transition-colors\">About Us</a>" +
                "</nav>";


            document.body.appendChild(mobileMenu);

            mobileMenu.querySelector('#close-menu').addEventListener('click', closeMenu);

            mobileMenu.querySelectorAll('a').forEach(function(link) {
                link.addEventListener('click', closeMenu);
            });
        }

        function openMenu() {
            createMobileMenu();
            mobileMenu.classList.remove('translate-x-full');
            document.body.style.overflow = 'hidden';
        }

        function closeMenu() {
            if (!mobileMenu) return;
            mobileMenu.classList.add('translate-x-full');
            document.body.style.overflow = '';

            setTimeout(function() {
                if (mobileMenu && mobileMenu.classList.contains('translate-x-full')) {
                    document.body.removeChild(mobileMenu);
                    mobileMenu = null;
                }
            }, 300);
        }

        menuButton.addEventListener('click', openMenu);

        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768 && mobileMenu && !mobileMenu.classList.contains(
                    'translate-x-full')) {
                closeMenu();
            }
        });
    });
</script>

<script id="scroll-animation">
    document.addEventListener('DOMContentLoaded', function() {
        var animateOnScroll = function animateOnScroll() {
            var elements = document.querySelectorAll('.card-hover, section');
            elements.forEach(function(element) {
                var elementPosition = element.getBoundingClientRect().top;
                var windowHeight = window.innerHeight;
                if (elementPosition < windowHeight * 0.85) {
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }
            });
        };

        document.querySelectorAll('.card-hover, section').forEach(function(element) {
            element.style.opacity = '0';
            element.style.transform = 'translateY(20px)';
            element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        });

        animateOnScroll();

        window.addEventListener('scroll', animateOnScroll);
    });
</script>

<script>
    // Header nav links
    document.querySelectorAll("nav a[data-target]").forEach(link => {
        link.addEventListener("click", function(e) {
            e.preventDefault();
            const target = this.getAttribute("data-target");
            scrollToSection(target);
        });
    });

    // Footer links (or any other links outside nav)
    document.querySelectorAll("footer a[data-target]").forEach(link => {
        link.addEventListener("click", function(e) {
            e.preventDefault();
            const target = this.getAttribute("data-target");
            scrollToSection(target);
        });
    });

    function scrollToSection(sectionId) {
        const element = document.getElementById(sectionId);
        if (!element) return;

        const headerHeight = document.querySelector('header')?.offsetHeight || 80;

        const position = element.getBoundingClientRect().top + window.scrollY - headerHeight;

        window.scrollTo({
            top: position,
            behavior: "smooth"
        });
    }
</script>

<script>
    document.addEventListener("DOMContentLoaded", () => {

        // --- SUCCESS MODAL ---
        Livewire.on('show-success-modal', () => {
            const modal = document.getElementById("feedback-modal");
            const content = document.getElementById("modal-content");

            modal.classList.remove("hidden");

            // animation
            setTimeout(() => {
                content.classList.remove("scale-95", "opacity-0");
                content.classList.add("scale-100", "opacity-100");
            }, 50);
        });

        // close success modal
        document.getElementById("close-modal").addEventListener("click", () => {
            const modal = document.getElementById("feedback-modal");
            const content = document.getElementById("modal-content");

            content.classList.add("scale-95", "opacity-0");

            setTimeout(() => {
                modal.classList.add("hidden");
            }, 200);
        });


        // --- ERROR MODAL ---
        Livewire.on('show-error-modal', (data) => {
            const errorModal = document.getElementById("error-modal");
            const errorContent = document.getElementById("error-modal-content");
            const errorMessage = document.getElementById("error-message");

            // Set error message sent from Livewire
            errorMessage.textContent = data.message;
            console.log(data.message);

            errorModal.classList.remove("hidden");
            // errorModal.style.display = "flex";

            setTimeout(() => {
                errorContent.classList.remove("scale-95", "opacity-0");
                errorContent.classList.add("scale-100", "opacity-100");
            }, 50);
        });

        // close error modal
        document.getElementById("close-error-modal").addEventListener("click", () => {
            const errorModal = document.getElementById("error-modal");
            const errorContent = document.getElementById("error-modal-content");

            errorContent.classList.add("scale-95", "opacity-0");

            setTimeout(() => {
                errorModal.classList.add("hidden");
            }, 200);
        });

    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        Livewire.on('feedback-reset', () => {
            document.getElementById('feedback').value = '';
        });
    });
</script>



@stack('scripts')
