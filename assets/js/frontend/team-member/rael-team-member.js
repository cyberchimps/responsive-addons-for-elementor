var Team_Member = function(e) {
    var t = e.find(".rael-team-popup-modal");
    t.magnificPopup({
        type: "inline",
        fixedContentPos: !0,
        fixedBgPos: !0,
        overflowY: "auto",
        closeBtnInside: !0,
        prependTo: e.find(".rael-team-member"),
        showCloseBtn: !1,
        callbacks: {
            beforeOpen: function () {
                this.st.mainClass = "my-mfp-slide-bottom rael-team-modal"
            }
        }
    });
    e.find(".rael-team-modal-close").on("click", (function () {
        t.magnificPopup("close")
    }));
}

jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction("frontend/element_ready/rael-team-member.default", Team_Member);
});