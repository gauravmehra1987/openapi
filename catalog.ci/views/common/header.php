<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $title; ?></title>
        <meta name="description" content="<?php echo $meta_description; ?>" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="msvalidate.01" content="BDB70F9E25CA08E3DDC36AD146CA48A3" />

        <?php if(isset($ogtitle)): ?><meta property="og:title" content="<?php echo $ogtitle; ?>" /><?php endif; ?>
        <?php if(isset($ogurl)): ?><meta property="og:url" content="<?php echo $ogurl; ?>" /><?php endif; ?>
        <?php if(isset($ogimage)): ?><meta property="og:image" content="<?php echo $ogimage; ?>" /><?php endif; ?>
        
        <?php add_stylesheet(array('css/style'));add_stylesheet(array('js/jqueryui/css/ui-lightness/jquery-ui-1.10.3.custom.min'),false); add_stylesheet(array('css/star-rating'));  ?>
        <?php add_scripts(array('static/js/jquery','static/js/jqueryui/js/jquery-ui-1.10.3.custom.min')); ?>
        <?php enque_script('_/js'); ?>
    <!--[if IE]>
     <?php add_stylesheet(array('css/ie'));?>
    <![endif]-->
    <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');  ga('create', 'UA-46082295-1', 'priceoye.com');  ga('send', 'pageview');
</script>
</head>
<body>
<div id="wrap">
    <div id="cw">
        <div id="hd">
            <div id="logo"><a href="http://priceoye.com"/>priceoye</a></div>
            <?php if(!$user['status']): ?>
            <div class="welcome hide">Welcome visitor you can <a class="fba" href="#">login</a> or <a href="#">create an account</a>.</div>
            <?php else: ?>
                <div class="welcome hide">Welcome <a><?php echo $user['firstname']; ?></a>. <img src="<?php echo site_url('image/c/?src=../'.$user['image'].'&w=30&h=30'); ?>" />
                    <a href="<?php echo site_url('auth/logout'); ?>">Logout</a>
                </div>
            <?php endif; ?>
               <div class="menu">
                    <ul class="s-menu fl">                               

                          <li><a href="<?php echo site_url('mobile/samsung-mobile-phones-price-list'); ?>">Samsung</a></li>
                          <li><a href="<?php echo site_url('mobile/apple-mobile-phones-price-list'); ?>">Apple</a></li>
                          <li><a href="<?php echo site_url('mobile/nokia-mobile-phones-price-list'); ?>">Nokia</a></li>
                          <li><a href="<?php echo site_url('mobile/micromax-mobile-phones-price-list'); ?>">Micromax</a> </li>
                          <li><a href="<?php echo site_url('mobile/htc-mobile-phones-price-list'); ?>">HTC</a></li>
                          <li><a href="<?php echo site_url('mobile/lg-mobile-phones-price-list'); ?>">LG</a></li>
                          <li><a href="<?php echo site_url('mobile/sony-mobile-phones-price-list'); ?>">Sony</a></li>
                         <li><a href="<?php echo site_url('mobile/blackberry-mobile-phones-price-list'); ?>">BlackBerry</a></li>
                      </ul>
               </div>
        </div>
            
            
       