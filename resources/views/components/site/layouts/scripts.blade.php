<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"
    integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
</script>
<script src="{{ secure_asset('assets/site/js/main.js') }}"></script>
<script src="//unpkg.com/alpinejs" defer></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.umd.min.js"></script>
<script src="https://res.gemcoder.com/js/reload.js"></script>
<script src="https://cdn.tailwindcss.com"></script>

<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: '#6A994E',
                    // 柔和橄榄绿 - 主色调
                    secondary: '#A7C957',
                    // 淡黄绿色 - 辅助色
                    accent: '#F2E8CF',
                    // 米黄色 - 强调色
                    goldLight: '#F7F3E3',
                    // 浅米色
                    neutral: '#FEFAF1',
                    // 温暖米色背景
                    dark: '#386641' // 深橄榄绿
                },
                fontFamily: {
                    sans: ['Inter', 'system-ui', 'sans-serif']
                }
            }
        }
    };
</script>

<script id="navbar-scroll">
    // 页面滚动时改变导航栏样式
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

        // 初始化导航栏状态
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

        // 创建移动端菜单
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

            // 关闭菜单按钮事件
            mobileMenu.querySelector('#close-menu').addEventListener('click', closeMenu);

            // 菜单项点击后关闭菜单
            mobileMenu.querySelectorAll('a').forEach(function(link) {
                link.addEventListener('click', closeMenu);
            });
        }

        // 打开菜单
        function openMenu() {
            createMobileMenu();
            mobileMenu.classList.remove('translate-x-full');
            document.body.style.overflow = 'hidden';
        }

        // 关闭菜单
        function closeMenu() {
            if (!mobileMenu) return;
            mobileMenu.classList.add('translate-x-full');
            document.body.style.overflow = ''; // 恢复背景滚动

            // 移除菜单以保持DOM清洁（可选）
            setTimeout(function() {
                if (mobileMenu && mobileMenu.classList.contains('translate-x-full')) {
                    document.body.removeChild(mobileMenu);
                    mobileMenu = null;
                }
            }, 300);
        }

        // 菜单按钮点击事件
        menuButton.addEventListener('click', openMenu);

        // 响应式处理 - 当窗口尺寸变化时关闭移动菜单
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
        // 添加滚动动画效果
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

        // 初始化元素样式
        document.querySelectorAll('.card-hover, section').forEach(function(element) {
            element.style.opacity = '0';
            element.style.transform = 'translateY(20px)';
            element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        });

        // 初始检查
        animateOnScroll();

        // 滚动时检查
        window.addEventListener('scroll', animateOnScroll);

        // 反馈模态框动画
        var feedbackForm = document.getElementById('feedback-form');
        var feedbackModal = document.getElementById('feedback-modal');
        var modalContent = document.getElementById('modal-content');
        var closeModal = document.getElementById('close-modal');
        if (feedbackForm && feedbackModal && modalContent && closeModal) {
            feedbackForm.addEventListener('submit', function(e) {
                e.preventDefault();
                feedbackModal.classList.remove('hidden');
                setTimeout(function() {
                    modalContent.classList.remove('scale-95', 'opacity-0');
                    modalContent.classList.add('scale-100', 'opacity-100');
                }, 10);
            });
            closeModal.addEventListener('click', function() {
                modalContent.classList.remove('scale-100', 'opacity-100');
                modalContent.classList.add('scale-95', 'opacity-0');
                setTimeout(function() {
                    feedbackModal.classList.add('hidden');
                }, 300);
            });
        }
    });
</script>

<script id="form-handler">
    document.addEventListener('DOMContentLoaded', function() {
        var feedbackForm = document.getElementById('feedback-form');
        var feedbackModal = document.getElementById('feedback-modal');
        var modalContent = document.getElementById('modal-content');
        var closeModal = document.getElementById('close-modal');

        // 表单提交处理
        feedbackForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // 在实际应用中，这里会有AJAX请求发送表单数据到服务器

            // 显示成功弹窗
            feedbackModal.classList.remove('hidden');

            // 添加动画效果
            setTimeout(function() {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
        });

        // 关闭弹窗
        closeModal.addEventListener('click', function() {
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');
            setTimeout(function() {
                feedbackModal.classList.add('hidden');
                feedbackForm.reset(); // 重置表单
            }, 300);
        });

        // 点击弹窗外部关闭弹窗
        feedbackModal.addEventListener('click', function(e) {
            if (e.target === feedbackModal) {
                closeModal.click();
            }
        });

        // ESC键关闭弹窗
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !feedbackModal.classList.contains('hidden')) {
                closeModal.click();
            }
        });
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

@stack('scripts')
