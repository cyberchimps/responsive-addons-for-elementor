
var Back_To_Top = function (t) {

	const animateCircle = (t) => {
		const { percentage: e, onScroll: c, speed: o, element: i, size: n, background_clr: a, color: r, stroke_width: h } = t,
			l = Math.ceil(document.body.scrollHeight - window.innerHeight);
		let s = i[0];
		var ctx;
		(ctx = s.getContext("2d")), (s.width = 2 * n + h), (s.height = 2 * n + h);
		let d = s.width / 2,
			x = s.height / 2,
			g = 0;
		const m = (t) => {
			(c ? ((g = Math.floor((t / l) * 360)), Math.floor((t / l) * 100)) : ((g += o) / 360) * 100) <= e
				? (ctx.clearRect(0, 0, s.width, s.height),
				  ctx.beginPath(),
				  (ctx.lineWidth = h),
				  ctx.arc(d, x, n, 0, 2 * Math.PI),
				  (ctx.strokeStyle = a),
				  ctx.stroke(),
				  ctx.closePath(),
				  ctx.beginPath(),
				  (ctx.lineWidth = h),
				  (ctx.strokeStyle = r),
				  ctx.arc(d, x, n, 0, ((2 * Math.PI) / 360) * g),
				  ctx.stroke(),
				  ctx.closePath(),
				  c || requestAnimationFrame(m))
				: c || cancelAnimationFrame(m);
		};
		c || requestAnimationFrame(m),
			c &&
				(m(window.pageYOffset),
				document.addEventListener("scroll", () => {
					const t = Math.ceil(window.pageYOffset);
					m(t);
				}));
	};

	const n = t.find(".rael-btt__button"),
		{ offset_top: i, show_after: s, show_scroll: a, style: o, fg: l, bg: r } = t.find(".rael-btt").data("settings");
		if ("progress_indicator" === o) {
			const e = t.find("#canvas");
		animateCircle({ element: e, size: 100, percentage: 100, onScroll: !0, speed: 5, color: l, background_clr: r, stroke_width: 10 });
	}
	n.on("click", (e) => {
		e.target;
		var t;
		(t = i), window.scrollTo({ left: 0, top: t, behavior: "smooth" });
	}),
		"yes" === a &&
		document.addEventListener("scroll", () => {
				((e) => {
					let t = n.hasClass("rael-tt-show");
					e && !t && n.addClass("rael-tt-show"), !e && t && n.removeClass("rael-tt-show");
				})(Math.ceil(window.pageYOffset) > s + i);
			});
}

jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction("frontend/element_ready/rael-back-to-top.default", Back_To_Top);
});
