
  {l s='Please wait, submitting...'}
  <br />
  <div style="margin: 10px 0px 15px 160px;">
    <img src="{$img_dir}ajax-loader.gif" />
  </div>
  <div id="manual_payment">
   {l s='If you are not redirected in 10 seconds, please click link:'} <br />
   <a href="{$method_link}" title="Submit payment">{$method_desc}</a>
  </div>

  <div style="display: none;">{$method_content}</div>



<script language="javascript">

  var method_link = "{$method_link}";
  {literal}
  $(document).ready(function(){
    //$('#testlink').click();
    window.location.href=method_link;
    $('#manual_payment').hide();
    $('#manual_payment').fadeTo(10000, 1,function() {$(this).show()});
  });
  {/literal}

</script>
