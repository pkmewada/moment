(function () {
    'use strict';

    var pageLoader = document.getElementById('pageLoader');
    if (pageLoader) {
        var loaderRevealTimer;
        var loaderSafetyTimer;

        function hidePageLoader() {
            window.clearTimeout(loaderRevealTimer);
            window.clearTimeout(loaderSafetyTimer);
            pageLoader.classList.remove('is-visible');
        }

        if (document.readyState === 'complete') {
            hidePageLoader();
        } else {
            loaderRevealTimer = window.setTimeout(function () {
                pageLoader.classList.add('is-visible');
            }, 250);
            loaderSafetyTimer = window.setTimeout(hidePageLoader, 10000);
            window.addEventListener('load', hidePageLoader, { once: true });
        }
    }

    /* -----------------------------------------------------
       MOBILE MENU
    ----------------------------------------------------- */
    var toggle = document.getElementById('mobileToggle');
    var menu = document.getElementById('mobileMenu');

    if (toggle && menu) {
        toggle.addEventListener('click', function () {
            var isOpen = menu.classList.toggle('active');
            toggle.classList.toggle('active', isOpen);
            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        menu.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                menu.classList.remove('active');
                toggle.classList.remove('active');
                toggle.setAttribute('aria-expanded', 'false');
            });
        });
    }


    /* ============================================================
PRICING TOGGLE - PROJECT BASED / QUICK ADD
============================================================ */
    document.addEventListener('DOMContentLoaded', function () {
        var toggleLabels = document.querySelectorAll('.toggle-label');
        var pricingList = document.getElementById('pricingList');
        var projectItems = document.querySelectorAll('.pricing-item.project-item');
        var quickAddItems = document.querySelectorAll('.pricing-item.quickadd-item');

        function showProjectMode() {
            // Show project items
            projectItems.forEach(function (item) {
                item.style.display = 'grid';
            });
            // Hide quick add items
            quickAddItems.forEach(function (item) {
                item.style.display = 'none';
            });
            // Update toggle
            toggleLabels.forEach(function (label) {
                label.classList.remove('active');
                if (label.getAttribute('data-mode') === 'project') {
                    label.classList.add('active');
                }
            });
        }

        function showQuickAddMode() {
            // Hide project items
            projectItems.forEach(function (item) {
                item.style.display = 'none';
            });
            // Show quick add items
            quickAddItems.forEach(function (item) {
                item.style.display = 'grid';
            });
            // Update toggle
            toggleLabels.forEach(function (label) {
                label.classList.remove('active');
                if (label.getAttribute('data-mode') === 'quickadd') {
                    label.classList.add('active');
                }
            });
        }

        // Add click handlers
        toggleLabels.forEach(function (label) {
            label.addEventListener('click', function () {
                var mode = this.getAttribute('data-mode');
                if (mode === 'project') {
                    showProjectMode();
                } else if (mode === 'quickadd') {
                    showQuickAddMode();
                }
            });
        });

        // Default: Show Project mode
        showProjectMode();
    });

    /* -----------------------------------------------------
       STICKY HEADER
    ----------------------------------------------------- */
    var header = document.getElementById('siteHeader');
    if (header) {
        var lastState = false;
        window.addEventListener('scroll', function () {
            var shouldStick = window.scrollY > 120;
            if (shouldStick !== lastState) {
                header.classList.toggle('is-sticky', shouldStick);
                lastState = shouldStick;
            }
        }, { passive: true });
    }

    /* -----------------------------------------------------
       FAQ ACCORDION
    ----------------------------------------------------- */
    document.querySelectorAll('.faq-question').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var item = this.closest('.faq-item');
            var wasActive = item.classList.contains('active');
            document.querySelectorAll('.faq-item').forEach(function (el) {
                el.classList.remove('active');
            });
            if (!wasActive) item.classList.add('active');
        });
    });

    /* -----------------------------------------------------
       STAT COUNTERS
    ----------------------------------------------------- */
    var counters = document.querySelectorAll('.stat-number');
    if (counters.length && 'IntersectionObserver' in window) {
        var counterObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;
                var el = entry.target;
                counterObserver.unobserve(el);

                var to = parseFloat(el.getAttribute('data-to')) || 0;
                var suffix = el.getAttribute('data-suffix') || '';
                var isDecimal = to % 1 !== 0;
                var duration = 1400;
                var start = null;

                function step(timestamp) {
                    if (!start) start = timestamp;
                    var progress = Math.min((timestamp - start) / duration, 1);
                    var current = to * progress;
                    el.textContent = (isDecimal ? current.toFixed(1) : Math.floor(current)) + suffix;
                    if (progress < 1) {
                        requestAnimationFrame(step);
                    } else {
                        el.textContent = (isDecimal ? to.toFixed(1) : to) + suffix;
                    }
                }
                requestAnimationFrame(step);
            });
        }, { threshold: 0.4 });

        counters.forEach(function (el) { counterObserver.observe(el); });
    }
    
    /* -----------------------------------------------------
       CURVED SLIDER (3D drag carousel)
    ----------------------------------------------------- */
    (function () {
        var track = document.getElementById('sliderTrack');
        if (!track) return;

        var RADIUS = 2000;
        var SPEED = 0.12;
        var THRESHOLD = 50;

        var videos = [
            'vedios/a.mp4',
            'vedios/b.mp4',
            'vedios/c.mp4',
            'vedios/d.mp4',
            'vedios/e.mp4',
            'vedios/f.mp4',
            'vedios/g.mp4',
            'vedios/h.mp4',
            'vedios/j.mp4',
            'vedios/k.mp4',
            'vedios/l.mp4',
            'vedios/m.mp4',
            'vedios/n.mp4',
            'vedios/o.mp4',
            'vedios/p.mp4',
            'vedios/q.mp4',
            'vedios/u.mp4',
            'vedios/r.mp4',
            'vedios/s.mp4',
            'vedios/t.mp4',
            'vedios/u.mp4',
            'vedios/v.mp4',
            'vedios/w.mp4',
            'vedios/x.mp4'
        ];

        var NUM = 25;
        var cards = [];
        var rotation = 0;
        var isDragging = false;
        var dragStartX = 0;
        var dragStartRot = 0;
        var animationId = null;
        var lastVideoIndex = -1;
        var sliderVisible = !('IntersectionObserver' in window);
        var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        for (var i = 0; i < NUM; i++) {
            var card = document.createElement('div');
            card.className = 'curved-slide';
            var angle = (i / NUM) * 360;
            card.style.transform = 'rotateY(' + angle + 'deg) translateZ(' + RADIUS + 'px)';
            card._baseAngle = angle;

            var video = document.createElement('video');
            video.muted = true;
            video.loop = true;
            video.playsInline = true;
            video.preload = 'metadata';
            video.setAttribute('aria-hidden', 'true');
            video.setAttribute('playsinline', '');
            video.setAttribute('webkit-playsinline', '');
            video.src = videos[i % videos.length];

            video.addEventListener('error', function () {
                console.error('Unable to load showcase video:', video.currentSrc || video.src);
            });

            card.appendChild(video);
            track.appendChild(card);
            cards.push({
                element: card,
                video: video,
                baseAngle: angle
            });
        }

        function pauseVideos() {
            cards.forEach(function (card) {
                card.video.pause();
            });
        }

        function syncVideos(activeIndex) {
            if (document.hidden || !sliderVisible) {
                pauseVideos();
                return;
            }

            cards.forEach(function (card, index) {
                if (index === activeIndex) {
                    if (card.video.paused) card.video.play().catch(function () {});
                } else {
                    card.video.pause();
                }
            });
        }

        function update() {
            if (document.hidden || !sliderVisible) {
                pauseVideos();
                animationId = null;
                return;
            }

            var nearestIndex = 0;
            var nearestDistance = 360;
            cards.forEach(function (card, index) {
                var eff = (card.baseAngle - rotation) % 360;
                if (eff > 180) eff -= 360;
                if (eff < -180) eff += 360;
                var distance = Math.abs(eff);
                card.element.classList.toggle('is-active', distance < THRESHOLD);
                if (distance < nearestDistance) {
                    nearestDistance = distance;
                    nearestIndex = index;
                }
            });

            if (nearestIndex !== lastVideoIndex) {
                lastVideoIndex = nearestIndex;
                syncVideos(nearestIndex);
            }

            if (!isDragging && !reducedMotion) {
                rotation += SPEED;
                if (rotation > 360) rotation -= 360;
            }
            track.style.transform = 'rotateY(' + rotation + 'deg)';

            animationId = window.requestAnimationFrame(update);
        }

        if ('IntersectionObserver' in window) {
            var sliderObserver = new IntersectionObserver(function (entries) {
                sliderVisible = entries[0].isIntersecting;
                if (sliderVisible) {
                    lastVideoIndex = -1;
                    update();
                } else {
                    pauseVideos();
                    if (animationId !== null) {
                        window.cancelAnimationFrame(animationId);
                        animationId = null;
                    }
                }
            }, { rootMargin: '100px' });
            sliderObserver.observe(track.closest('.slider-section') || track);
        } else {
            update();
        }

        function onStart(clientX) {
            isDragging = true;
            dragStartX = clientX;
            dragStartRot = rotation;
            track.style.cursor = 'grabbing';
        }

        function onMove(clientX) {
            if (!isDragging) return;
            var delta = (dragStartX - clientX) * 0.35;
            rotation = dragStartRot + delta;
            if (rotation > 360) rotation -= 360;
            if (rotation < 0) rotation += 360;
        }

        function onEnd() {
            if (!isDragging) return;
            isDragging = false;
            track.style.cursor = 'grab';
            lastVideoIndex = -1;
        }

        // Mouse events
        track.addEventListener('mousedown', function (e) {
            e.preventDefault();
            onStart(e.clientX);
        });
        window.addEventListener('mousemove', function (e) {
            onMove(e.clientX);
        });
        window.addEventListener('mouseup', onEnd);

        // Touch events
        track.addEventListener('touchstart', function (e) {
            var t = e.touches[0];
            if (t) onStart(t.clientX);
        }, { passive: true });

        track.addEventListener('touchmove', function (e) {
            var t = e.touches[0];
            if (t) onMove(t.clientX);
            // Prevent page scroll on touch devices
            e.preventDefault();
        }, { passive: false });

        track.addEventListener('touchend', onEnd, { passive: true });
        track.addEventListener('touchcancel', onEnd, { passive: true });

        track.addEventListener('keydown', function (e) {
            if (e.key === 'ArrowLeft') {
                rotation -= 6;
                if (rotation < 0) rotation += 360;
                e.preventDefault();
            }
            if (e.key === 'ArrowRight') {
                rotation += 6;
                if (rotation > 360) rotation -= 360;
                e.preventDefault();
            }
        });

        document.addEventListener('visibilitychange', function () {
            if (document.hidden) {
                pauseVideos();
                if (animationId !== null) {
                    window.cancelAnimationFrame(animationId);
                    animationId = null;
                }
            } else if (sliderVisible) {
                lastVideoIndex = -1;
                update();
            }
        });

    })();




    /* -----------------------------------------------------
       NEWSLETTER FORM
    ----------------------------------------------------- */
    var newsletterForm = document.getElementById('newsletterForm');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function (e) {
            e.preventDefault();
            var email = newsletterForm.querySelector('input[name="email"]');
            if (!email || !email.checkValidity()) {
                if (email) email.reportValidity();
                return;
            }
            // TODO: replace with a real fetch() call to your backend
            alert('Thanks for subscribing!');
            newsletterForm.reset();
        });
    }

})();
