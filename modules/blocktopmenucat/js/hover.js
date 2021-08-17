$(document).ready(function() {
  $("#topcategorymenu ul li div").hide();
  
  $("#topcategorymenu ul li").hover(function() {
    $(this).find('div').show();
  }, function() {
    $(this).find('div').hide();
  });
});