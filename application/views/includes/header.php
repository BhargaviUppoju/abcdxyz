<?php 
    $jsExt = $this->config->item('js_gz_extension');
    $cssExt = $this->config->item('css_gz_extension');
    $jsPublicPath = $this->config->item('js_public_path');
    $cssPublicPath = $this->config->item('css_public_path');
    $imgStaticPath = $this->config->item('images_static_path');
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <script type="text/javascript">
            var site_url = '<?php echo site_url(); ?>';
            var api_path = '<?php echo $this->config->item('api_path'); ?>';
            var images_static_path = '<?php echo $imgStaticPath; ?>';
         </script>
        <script type="text/javascript" src="<?php echo $jsPublicPath.'jquery-1.11.3.min.js';?>"></script>
         <script type='text/javascript' src="<?php echo $jsPublicPath.'jQuery'.$jsExt;?>"></script>
         <script type='text/javascript' src='<?php echo $jsPublicPath."jquery.validate".$jsExt;?>'></script>    
         <link rel='stylesheet' href='<?php echo $cssPublicPath.'jquery-ui'.$cssExt; ?>'>
         <link rel='stylesheet' href='<?php echo $cssPublicPath.'common'.$cssExt; ?>'>
         <link rel='stylesheet' href='<?php echo $cssPublicPath.'bootstrap'.$cssExt; ?>'>
        <?php if(isset($cssArray)){
            foreach($cssArray as $cssFile){?>
            <link rel='stylesheet' href="<?php echo $cssFile.$cssExt;?>">
        <?php }}?>
    <title><?php echo $pageTitle ?></title>
