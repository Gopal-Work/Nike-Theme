(function () {
  function init() {
    var banner = document.querySelector('.homepage_banner');
    if (!banner) return;

    var wrapper = banner.querySelector('.e-con-inner');
    if (!wrapper) return;

    // Only direct <div> children of e-con-inner count as slides
    var slides = Array.prototype.slice
      .call(wrapper.children)
      .filter(function (el) { return el.tagName === 'DIV'; });

    if (slides.length < 2) return; // nothing to carousel

    var current = 0;
    var timer = null;
    var playing = true;
    var interval = 7000; // ms between auto-advances, tweak as needed
    var progressRemaining = interval; // how much time is left on the ring for the current slide
    var progressStartedAt = null; // timestamp when the current progress run began

    // Build controls
    var controls = document.createElement('div');
    controls.className = 'hb-controls';
    controls.innerHTML =
      '<button type="button" class="hb-btn hb-prev" aria-label="Previous slide">&#10094;</button>' +
      '<div class="hb-play-wrap">' +
        '<svg class="hb-progress-ring" width="40" height="40">' +
          '<circle class="hb-progress-track" cx="20" cy="20" r="17"></circle>' +
          '<circle class="hb-progress-bar" cx="20" cy="20" r="17"></circle>' +
        '</svg>' +
        '<button type="button" class="hb-btn hb-play" aria-label="Pause">&#10073;&#10073;</button>' +
      '</div>' +
      '<div class="hb-dots"></div>' +
      '<button type="button" class="hb-btn hb-next" aria-label="Next slide">&#10095;</button>';
    banner.appendChild(controls);

    // Progress ring setup
    var progressBar = controls.querySelector('.hb-progress-bar');
    var RADIUS = 17;
    var CIRCUMFERENCE = 2 * Math.PI * RADIUS;
    progressBar.style.strokeDasharray = CIRCUMFERENCE;
    progressBar.style.strokeDashoffset = CIRCUMFERENCE;

    function startProgress(duration) {
      progressRemaining = duration;
      progressStartedAt = Date.now();
      progressBar.style.transition = 'none';
      progressBar.style.strokeDashoffset = CIRCUMFERENCE;
      // force reflow so the browser registers the reset before animating again
      progressBar.getBoundingClientRect();
      progressBar.style.transition = 'stroke-dashoffset ' + duration + 'ms linear';
      progressBar.style.strokeDashoffset = 0;
    }

    function pauseProgress() {
      // work out how much time was actually left when pause was hit
      var elapsed = Date.now() - progressStartedAt;
      progressRemaining = Math.max(progressRemaining - elapsed, 0);

      var currentOffset = getComputedStyle(progressBar).strokeDashoffset;
      progressBar.style.transition = 'none';
      progressBar.style.strokeDashoffset = currentOffset; // freeze exactly where it is
    }

    function resumeProgress() {
      progressStartedAt = Date.now();
      // continue from the frozen offset down to 0, but only over whatever time is left
      progressBar.style.transition = 'stroke-dashoffset ' + progressRemaining + 'ms linear';
      progressBar.style.strokeDashoffset = 0;
    }

    var dotsWrap = controls.querySelector('.hb-dots');
    slides.forEach(function (_, i) {
      var dot = document.createElement('span');
      dot.className = 'hb-dot';
      dot.addEventListener('click', function () {
        goTo(i);
        resetTimer();
      });
      dotsWrap.appendChild(dot);
    });
    var dots = dotsWrap.querySelectorAll('.hb-dot');

    function playActiveVideo(i) {
      slides.forEach(function (slide, idx) {
        var v = slide.querySelector('video');
        if (!v) return;
        if (idx === i) {
          v.currentTime = 0;
          v.muted = true;
          v.play().catch(function () {});
        } else {
          v.pause();
        }
      });
    }

    function goTo(i) {
      slides[current].classList.remove('hb-active');
      dots[current].classList.remove('hb-active-dot');

      current = (i + slides.length) % slides.length;

      slides[current].classList.add('hb-active');
      dots[current].classList.add('hb-active-dot');
      playActiveVideo(current);

      if (playing) {
        startProgress(interval); // reset ring on EVERY slide change, manual or automatic
      }
    }

    function next() { goTo(current + 1); }
    function prev() { goTo(current - 1); }

    function startTimer(duration) {
      var wait = (duration === undefined) ? interval : duration;
      timer = setTimeout(function () {
        next(); // goTo() inside next() resets progress to a full fresh interval
        startTimer(interval);
      }, wait);
    }
    function stopTimer() {
      clearTimeout(timer);
    }
    function resetTimer() {
      if (playing) {
        stopTimer();
        startTimer(interval);
      }
    }

    controls.querySelector('.hb-next').addEventListener('click', function () {
      next();
      resetTimer();
    });
    controls.querySelector('.hb-prev').addEventListener('click', function () {
      prev();
      resetTimer();
    });

    var playBtn = controls.querySelector('.hb-play');
    playBtn.addEventListener('click', function () {
      playing = !playing;
      var activeVideo = slides[current].querySelector('video');
      if (playing) {
        playBtn.innerHTML = '&#10073;&#10073;';
        if (activeVideo) activeVideo.play().catch(function () {});
        resumeProgress(); // continues the ring from where it was frozen, not from 0
        startTimer(progressRemaining); // next auto-advance fires after only the remaining time
      } else {
        playBtn.innerHTML = '&#9654;';
        stopTimer();
        pauseProgress(); // freezes the ring AND records how much time is left
        if (activeVideo) activeVideo.pause();
      }
    });

    // Kick things off
    goTo(0);
    startTimer(interval);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);

    setInterval(() => {
         jQuery('.homepage_banner').append(jQuery('.hb-controls').find('.hb-dots'));
    }, 2000);
   

  } else {
    init();
    setInterval(() => {
        // alert("aaa");
         jQuery('.homepage_banner').append(jQuery('.hb-controls').find('.hb-dots'));
    }, 2000);
    
  }
})();

// category slider js

jQuery(document).ready(function($) {
    var $slider = $('.category-slider-wrapper');

    function updatePaddingState(slick, targetSlide) {
        var $slickList = $slider.find('.slick-list');
        var maxSlide = slick.slideCount - Math.floor(slick.options.slidesToShow);

        if (targetSlide > 0) {
            $slickList.addClass('has-scrolled');
        } else {
            $slickList.removeClass('has-scrolled');
        }

        if (targetSlide >= maxSlide) {
            $slickList.addClass('is-at-end');
        } else {
            $slickList.removeClass('is-at-end');
        }
    }

    $slider.on('init', function(event, slick) {
        updatePaddingState(slick, 0);
    });

    // Fire state update right as the slide animation begins for synchronized movement
    $slider.on('beforeChange', function(event, slick, currentSlide, nextSlide) {
        updatePaddingState(slick, nextSlide);
    });

    $slider.slick({
        dots: false,
        infinite: false,
        speed: 450, // Slightly longer duration for smoother motion
        cssEase: 'cubic-bezier(0.25, 1, 0.5, 1)', // Smooth Nike-style fluid easing
        slidesToShow: 3,
        slidesToScroll: 1,
        swipeToSlide: true,
        useTransform: true,
        useCSS: true,
        prevArrow: '<button type="button" class="slick-prev custom-arrow">❮</button>',
        nextArrow: '<button type="button" class="slick-next custom-arrow">❯</button>',
        responsive: [
            {
                breakpoint: 1024,
                settings: { slidesToShow: 2 }
            },
            {
                breakpoint: 600,
                settings: { slidesToShow: 1 }
            }
        ]
    });

    // Ensure layout alignment on load
    setTimeout(function() {
        window.dispatchEvent(new Event('resize'));
    }, 60);
});

// category slider js 


// our_trending_category section 

jQuery(document).ready(function($) {
  function runslick(){
    var width=window.innerWidth;
    if(width<767){
     $('.our_trending_category .inner').slick({
          slidesToShow: 2,
          slidesToScroll: 1,
          arrows: false,
          dots: true,
          responsive: [
            {
              breakpoint: 767,
              settings: {
                  slidesToShow: 1,
              }
            }
          ]
        });
    }else{
      if($('.our_trending_category .inner').hasClass('slick-initialized')){
        $('.our_trending_category .inner').slick('unslick');
      }
      // $('.our_trending_category .inner').slick('unslick');
    }
     
  }
  var width=window.innerWidth;
  window.addEventListener("resize", () => {
    runslick();
  });
  runslick();
});