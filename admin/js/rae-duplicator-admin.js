jQuery( document ).ready( function(){

  // Detect Quick Edit open
  $(document).on("click", ".editinline", function () {
    let postId = $(".inline-edit-row").attr("id").replace("edit-", "");

    setTimeout(function () {
      // Detect post type
      let postType = $("body")
        .attr("class")
        .match(/post-type-([a-zA-Z0-9_-]+)/);
      postType = postType ? postType[1] : "";

      const allowed = raeDuplicatorjs.allowed_types;
      const enabled = allowed.includes("all") || allowed.includes(postType);

      // Remove old button
      $(".rae-duplicate-btn").remove();

      if (enabled) {
        $(".inline-edit-save .save").after(
          '<button type="button" class="button rae-duplicate-btn" style="margin-left:8px;">RAE Duplicator</button>'
        );

      } 
    }, 50);
  });

  // Handle click
  $(document).on("click", ".rae-duplicate-btn", function () {
    let postId = $(".inline-edit-row").attr("id").replace("edit-", "");

    // Find nonce for this post
    let nonce = $('.rae-dup-nonce[data-post="' + postId + '"]').val();

    if (!nonce) {
      console.log("Nonce missing!");
      return;
    }

    let url =
      RAEDup.duplicate_url + "&post=" + postId + "&_wpnonce=" + nonce;

    window.location.href = url; // perform duplication
  });
});