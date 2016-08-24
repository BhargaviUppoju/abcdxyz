<?php 
    $jsExt = $this->config->item('js_gz_extension');
    $cssExt = $this->config->item('css_gz_extension');
    $jsPublicPath = $this->config->item('js_public_path');
    $cssPublicPath = $this->config->item('css_public_path');
    $imgStaticPath = $this->config->item('images_static_path');
?>  

      <?php  if(isset($jsArray)){
            foreach($jsArray as $jsFile){?>
            <script type='text/javascript' src="<?php echo $jsFile.$jsExt;?>"></script>
        <?php }}?>

