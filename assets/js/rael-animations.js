(function ($) {
	'use strict';
console.log('in main RAE Animations funciton js====');
	const RaelAnimations = {

		init() {
			this.cache();
			this.bind();
			this.update();
		},

		cache() {
			//this.$elements = $('.rael-scroll-effects');
			this.windowHeight = window.innerHeight;
		},

		bind() {
			$(window).on('scroll resize', () => {
				this.windowHeight = window.innerHeight;
				this.update();
			});
		},

		update() {
                    console.log('in animationsss update----');

			const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            console.log('scrollTop=='+scrollTop);

			$('.rael-scroll-effects').each((i, el) => {
				this.apply(el, scrollTop);
			});
		},

		apply(element, scrollTop) {
            console.log('in animationsss apply----');
			const raw = element.dataset.raelScrollEffects;
			if (!raw) return;

			let config;
			try {
				config = JSON.parse(raw);
			} catch (e) {
				return;
			}

			const effects = config.effects || {};
			const rect = element.getBoundingClientRect();

			const elementTop = rect.top + scrollTop;
			const elementHeight = rect.height;

			const viewportStart = scrollTop + this.windowHeight;
			const total = elementHeight + this.windowHeight;
			const progress = Math.min(
				Math.max((viewportStart - elementTop) / total, 0),
				1
			);

			/* HORIZONTAL SCROLL */
			if (effects.translateX) {

				const start = effects.translateX.start / 100;
				const end   = effects.translateX.end / 100;

				// Apply ONLY when scrolling within viewport range
				if (progress > start && progress < end) {
					const p = this.range(
						progress,
						effects.translateX.start,
						effects.translateX.end
					);

					let value = (1 - p) * effects.translateX.speed * 50;

					// Elementor-style direction handling
					if (effects.translateX.direction === 'to_right') {
						value *= -1;
					}

					element.style.setProperty(
						'--translateX',
						value.toFixed(3) + 'px'
					);
				} 

			}

			/* Vertical Scroll */

			if (effects.translateY) {

				const start = effects.translateY.start / 100;
				const end   = effects.translateY.end / 100;

				// Apply ONLY when scrolling within viewport range
				if (progress > start && progress < end) {
					const p = this.range(
						progress,
						effects.translateY.start,
						effects.translateY.end
					);

					let value = (1 - p) * effects.translateY.speed * 50;

					// Elementor-style direction handling
					if (effects.translateY.direction === 'up') {
						value *= -1;
					}
					element.style.setProperty(
						'--translateX',
						value.toFixed(3) + 'px'
					);
					element.style.setProperty(
						'--translateY',
						-value.toFixed(3) + 'px'
					);
				} 

			}
			/* Opacity */
			if (effects.opacity) {
				const p = this.range(progress, effects.opacity.start, effects.opacity.end);
				opacity = effects.opacity.direction === 'fade_in'
					? p
					: 1 - p;
			}

			/* Blur logic */
			if (effects.blur) {
				const p = this.range(progress, effects.blur.start, effects.blur.end);
				filter += ` blur(${p * effects.blur.level}px)`;
			}

			/* Scale */
			if (effects.scale) {
				const start = effects.scale.start / 100;
				const end   = effects.scale.end / 100;

				if (progress > start && progress < end) {
					const p = this.range(progress, effects.scale.start, effects.scale.end);
					const delta = p * effects.scale.speed * 0.1;

					const scale =
						effects.scale.direction === 'scale_down'
							? 1 - delta
							: 1 + delta;

					element.style.setProperty('--scale', scale.toFixed(3));
					element.style.transformOrigin =
						`${effects.scale.origin_x} ${effects.scale.origin_y}`;
				} else {
					element.style.setProperty('--scale', '1');
				}
			}

			/* Rotate logic */
			if (effects.rotate) {
	const start = effects.rotate.start / 100;
	const end   = effects.rotate.end / 100;

	if (progress >= start && progress <= end) {
		const p = this.range(
			progress,
			effects.rotate.start,
			effects.rotate.end
		);

		let deg = p * effects.rotate.speed * 10;

		if (effects.rotate.direction === 'to_right') {
			deg *= -1;
		}

		/* ----------------------------------
		 * Elementor-style anchor handling
		 * ---------------------------------- */

		const originX = effects.rotate.origin_x || 'center';
		const originY = effects.rotate.origin_y || 'center';

		// Store origin vars (CSS swaps X/Y)
		element.style.setProperty('--e-transform-origin-x', originX);
		element.style.setProperty('--e-transform-origin-y', originY);

		// Translate compensation (Elementor math-lite)
		const translateMap = {
			left:   -50,
			right:  50,
			top:    -50,
			bottom: 50,
			center: 0
		};

		const tx = translateMap[originX] || 0;
		const ty = translateMap[originY] || 0;

		element.style.setProperty('--translateX', tx + 'px');
		element.style.setProperty('--translateY', ty + 'px');

		// Apply rotation
		element.style.setProperty(
			'--rotateZ',
			deg.toFixed(2) + 'deg'
		);
	}
}

		
		},

		range(progress, start, end) {
			const s = start / 100;
			const e = end / 100;

			if (progress <= s) return 0;
			if (progress >= e) return 1;

			return (progress - s) / (e - s);
		}
	};

	$(window).on('elementor/frontend/init', function () {

		const initAnimations = () => {
			RaelAnimations.init();
		};

		// Frontend
		initAnimations();

		// Editor live preview (CRITICAL)
		if (elementorFrontend.isEditMode()) {
			elementorFrontend.hooks.addAction(
				'frontend/element_ready/global',
				initAnimations
			);
		}
	});
})(jQuery);