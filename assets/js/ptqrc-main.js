(function($) {
  $(document).ready(function() {
    var current_state = $("#ptqrc_mini_toggle").val();
    $("#toggle_button").minitoggle();
    if (current_state == 1) {
      $("#toggle_button .minitiggle").addClass("active");
    }
    $("#toggle_button").on("toggle", function(e) {
      if (e.isActive()) {
        $("#ptqrc_mini_toggle").val(1);
      } else {
        $("#ptqrc_mini_toggle").val(0);
      }
    });
  });
})(jQuery);
