{if $adverts|@count > 0}

<div class="advertising_block {$hook}">
  <ul class="bjqs">
     {foreach from=$adverts item=advert name=myLoop}
	     <li><a href="{$advert.link}" title="{$advert.desc}"><img src="{$advert.img}" alt="{$advert.desc}" /></a></li>
     {/foreach}
  </ul>
</div>
<br class="clear" />


<link rel="stylesheet" href="{$modules_dir}adverts/basic-jquery-slider.css">
<script src="{$modules_dir}adverts/basic-jquery-slider.js"></script>
<script>
{literal}
  $(document).ready(function() 
  {        
    $('.advertising_block').bjqs({
      'animation' : 'slide',
      'width' : 1025,
      'height' : 282,
			'showMarkers' : true,
			'showControls' : false,
			'centerMarkers' : false,
			'centerControls' : false      
    });    
  });
{/literal}  
</script>


  {if $hook=='homebottom'}
    <p>Fusce fringilla laoreet consectetur. Duis venenatis, justo non eleifend egestas, arcu dui pulvinar libero, ut tempus dui neque vel ligula. Nam elementum blandit erat eget tempor. Mauris facilisis libero eu lorem tempus tristique porttitor nisi venenatis. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Suspendisse quis nunc in eros placerat congue vitae eu tellus. Pellentesque gravida sapien vitae turpis dignissim eu dapibus dui rutrum. Nunc lacinia metus vel diam rutrum in consectetur urna auctor. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque porttitor viverra erat, in elementum nibh malesuada eget. Morbi blandit, lacus ac ornare luctus, nisl sapien facilisis urna, in iaculis sem ipsum a orci. Cras lorem nisi, sodales eget interdum at, varius vitae elit. Proin iaculis libero vitae dolor elementum non sagittis turpis venenatis. In euismod pellentesque nulla eget volutpat. Donec justo dolor, pretium egestas vehicula at, malesuada eu dui. Vestibulum dapibus lobortis nisl, vitae gravida quam feugiat sed.</p>
    <p>Vestibulum quam nisi, varius ornare pulvinar tempus, tempor vel justo. In vitae urna sit amet nibh scelerisque dignissim vel ac leo. Nam elementum lorem ornare enim vulputate ut elementum nulla tristique. Nam fermentum venenatis tellus at eleifend. Phasellus lacinia mi sed sem molestie aliquet. In hac habitasse platea dictumst. Phasellus dictum gravida faucibus. Sed quam libero, gravida non hendrerit vel, hendrerit ut quam. Suspendisse felis ligula, cursus eget ornare ac, sodales sed arcu. Sed posuere tincidunt sagittis. In hac habitasse platea dictumst. Sed id orci at turpis luctus rhoncus. Donec porttitor ante vitae augue imperdiet eget placerat sapien commodo. Aenean sodales adipiscing magna, vel molestie sem volutpat eu. Praesent cursus hendrerit lorem, non fermentum orci pulvinar a. Cras a justo quis magna posuere aliquam ac eu elit. Vestibulum urna sapien, bibendum et pellentesque ut, dictum vel quam. Vivamus congue euismod urna, ac viverra massa porta id. Nunc viverra porttitor sapien vitae lacinia. </p>
  {/if}
{/if}