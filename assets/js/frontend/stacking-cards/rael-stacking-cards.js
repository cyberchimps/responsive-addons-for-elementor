function initStackingCards($scope) {
  const cards = gsap.utils.toArray($scope.find(".rael-stacking-card"));
  const total = cards.length;

  cards.forEach((card, index) => {
    const gapValue = card.dataset.gap || "50px";
    const gapMatch = gapValue.match(/^([0-9.]+)([a-z%]*)$/);
    const gapNum = gapMatch ? parseFloat(gapMatch[1]) : 0;

    const baseX = parseFloat(card.dataset.translateX) || 0;
    const baseRotate = parseFloat(card.dataset.rotate) || 0;
    const scrollRotate = parseFloat(card.dataset.scrollrotate) || baseRotate;
    const baseBlur = parseFloat(card.dataset.blur) || 0;
    const baseGreyscale = parseFloat(card.dataset.greyscale) || 0;
    const scrollGreyscale = parseFloat(card.dataset.scrollgreyscale) || 0;
    const rawOpacity = parseFloat(card.dataset.opacity) || 1;

    // ✅ FIXED: use dataset.scrollscale and handle conditional scaling
    const rawScrollScale = parseFloat(card.dataset.scale) || 0;
    const absScrollScale = Math.abs(rawScrollScale);

    // ---- NEW LOGIC ----
    const distanceFromFront = total - 1 - index;

    // Each card slightly smaller than the one in front
    const scaleStep = absScrollScale * 0.1;
    const scrollCardScale = 1 - distanceFromFront * (scaleStep + 0.015);

    // ---- OPACITY LOGIC ----
    // If backend opacity = 1, progressively fade back cards
    let baseOpacity = 1;
    if (rawOpacity === 1) {
      baseOpacity = 1 - Math.pow(distanceFromFront / total, 1.5);
      baseOpacity = Math.max(0.05, baseOpacity); // prevent full transparency
    }

    // ✅ Build transform config conditionally
    const setConfig = {
      x: baseX,
      rotate: baseRotate,
      filter: `blur(0px) grayscale(${baseGreyscale}%)`,
      opacity: 1,
    };

    if (rawScrollScale !== 0) {
      setConfig.scaleX = 1; //baseCardScale;
      setConfig.scaleY = 1; //baseCardScale;
    }

    gsap.set(card, setConfig);

    ScrollTrigger.create({
      trigger: card,
      start: "bottom top",
      onEnter: () => {
        const enterConfig = {
          x: baseX,
          rotate: scrollRotate,
          filter: `blur(${baseBlur}px) grayscale(${scrollGreyscale}%)`,
          opacity: baseOpacity,
          overwrite: "auto",
          duration: 0.4,
          ease: "power2.out",
        };

        if (rawScrollScale !== 0) {
          enterConfig.scaleX = scrollCardScale;
          enterConfig.scaleY = scrollCardScale;
        }

        gsap.to(card, enterConfig);
      },
      onLeaveBack: () => {
        const leaveConfig = {
          x: baseX,
          rotate: baseRotate,
          filter: `blur(0px) grayscale(${baseGreyscale}%)`,
          opacity: 1,
          overwrite: "auto",
          duration: 0.4,
          ease: "power2.out",
        };

        if (rawScrollScale !== 0) {
          leaveConfig.scaleX = 1; 
          leaveConfig.scaleY = 1;  
        }

        gsap.to(card, leaveConfig);
      },
    });
  });
}

function copySectionContent() {
    jQuery('.rael-section-fetch').each(function () {

        let container = this;
        let targetId = container.dataset.targetId;
        if (!targetId) return;

        let target = document.getElementById(targetId);
        if (!target) return;

        // Clone with CSS applied as inline styles
        let clonedStyled = cloneWithAllStyles(target);

        container.innerHTML = "";
        container.appendChild(clonedStyled);
    });
}

function waitForSectionAndCopy(maxRetries = 40) {
    let attempts = 0;

    function tryCopy() {
        copySectionContent();

        attempts++;

        // If any section content copied successfully → stop retrying
        if (jQuery('.rael-section-fetch').find('*').length > 0) {
            return;
        }

        // Retry for a while until other widgets finish rendering
        if (attempts < maxRetries) {
            setTimeout(tryCopy, 250);
        }
    }

    tryCopy();
}
function cloneWithAllStyles(sourceElement) {
    let cloned = sourceElement.cloneNode(true);

    copyComputedStyles(sourceElement, cloned);

    let sourceChildren = sourceElement.children;
    let clonedChildren = cloned.children;

    for (let i = 0; i < sourceChildren.length; i++) {
        copyComputedStyles(sourceChildren[i], clonedChildren[i]);
    }

    return cloned;
}

function copyComputedStyles(source, target) {
    const computed = window.getComputedStyle(source);
    for (let prop of computed) {
        target.style[prop] = computed.getPropertyValue(prop);
    }
}


// Elementor frontend + editor support
jQuery(window).on("elementor/frontend/init", function () {
  if (
    typeof elementorFrontend !== "undefined" &&
    typeof elementorFrontend.hooks !== "undefined"
  ) {
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/rael-stacking-cards.default",
      function ($scope) {
        const wrapper = $scope.find(".rael-stacking-cards-wrapper");
        const parent = wrapper.closest(".elementor-widget-rael-stacking-cards");

        if (parent.length) {
          const cardHeight = wrapper.data("card-height");
          const cardOffset = wrapper.data("card-offset");
          parent[0].style.setProperty("--card-height", cardHeight);
          parent[0].style.setProperty("--card-top-offset", cardOffset);
        }

        initStackingCards($scope);
        waitForSectionAndCopy();

      }
    );
  }

  if (elementorFrontend.isEditMode()) {
    jQuery(window).on("load", function () {
      jQuery(".rael-stacking-cards").each(function () {
        const $scope = jQuery(this);
        const wrapper = $scope.find(".rael-stacking-cards-wrapper");
        const parent = wrapper.closest(".elementor-widget-rael-stacking-cards");

        if (parent.length) {
          const cardHeight = wrapper.data("card-height");
          const cardOffset = wrapper.data("card-offset");
          parent[0].style.setProperty("--card-height", cardHeight);
          parent[0].style.setProperty("--card-top-offset", cardOffset);
        }

        initStackingCards(jQuery(this));
      });
    });

    // Run every time a widget finishes rendering inside editor
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/global",
      function() {
        waitForSectionAndCopy();
      }
    );

    // When a control is changed (live update)
    elementor.channels.editor.on("change", function() {
        waitForSectionAndCopy();
    });
  }

});
