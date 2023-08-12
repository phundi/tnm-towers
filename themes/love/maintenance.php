<style>
nav{ display: none;}
footer {display:none !important;}
body { text-align: center; padding: 150px;overflow: hidden;}
body > #container {min-height: auto;height: 100%;padding: 0 !important;}
h1 { font-size: 50px;}
h2 {font-size: 30px;}
article { width: 100%;max-width: 560px;margin: auto;height: 100%;display: flex;align-items: center;justify-content: center;flex-direction: column;}
article > svg {width: 200px;height: 200px;min-height: 200px;margin-bottom: 15px;}
a { color: #3d8cfa; text-decoration: none; }
a:hover { color: #333; text-decoration: none; }
p {font-size: 14px;margin: 0 0 5px;line-height: 20px;}
@media(max-width:990px){
body{padding: 10px;	height: 100vh; width: 100%;display: table;}
footer .footer-wrapper{display:none !important;}
.content-container {margin-top: 0px;vertical-align: middle;display: table-cell;}
}

.maintenance-style {position: absolute;background-repeat: no-repeat;background-size: 100%}
.maintenance-style.maintenance-style-top-left {width: 240px;height: 218px;top: -73px;left: -37px;background-image: url("<?php echo $theme_url;?>assets/img/maintenance-style-top-left.png")}
.maintenance-style.maintenance-style-bottom-right {width: 264px;height: 83px;right: -13px;bottom: -40px;background-image: url("<?php echo $theme_url;?>assets/img/maintenance-style-bottom-right.png")}

@media (min-width: 768px) {
.maintenance-style.maintenance-style-top-left {width:480px;height: 435px;top: -146px;left: -74px}
.maintenance-style.maintenance-style-bottom-right {width:528px;height: 166px;right: -26px;bottom: -80px}
}

@media (max-width: 768px) {
.maintenance-style {display: none;}
body {overflow-y: auto;}
}
</style>

<div class="dt_get_start_circle-1"></div>
<div class="dt_get_start_circle-2"></div>
<div class="dt_get_start_circle-3"></div>

<div class="maintenance-style maintenance-style-top-left"></div>
<div class="maintenance-style maintenance-style-bottom-right"></div>

<article>
	<svg enable-background="new 0 0 32 32" height="512" viewBox="0 0 32 32" width="512" xmlns="http://www.w3.org/2000/svg"><path d="m26 32h-20c-3.314 0-6-2.686-6-6v-20c0-3.314 2.686-6 6-6h20c3.314 0 6 2.686 6 6v20c0 3.314-2.686 6-6 6z" fill="#ffe6e2"/><path d="m17.667 19.333h4.667c.512 0 .988.141 1.41.368l-.287-2.727c-.099-.936-.883-1.641-1.824-1.641h-3.266c-.941 0-1.725.705-1.824 1.641l-.287 2.727c.423-.227.898-.368 1.411-.368z" fill="#fc573b"/><path d="m14.167 20.333h-.833c-.275 0-.5-.224-.5-.5h.333c.202 0 .385-.122.462-.309s.035-.402-.109-.545l-.833-.833c-.195-.195-.512-.195-.707 0l-.833.833c-.143.143-.186.358-.109.545s.26.309.462.309h.333c0 .827.673 1.5 1.5 1.5h.833c.276 0 .5-.224.5-.5s-.223-.5-.499-.5z" fill="#fd907e"/><g fill="#fc573b"><path d="m22.333 20.667h-4.667c-.919 0-1.667.748-1.667 1.667.001.918.748 1.666 1.668 1.666h4.667c.919 0 1.666-.748 1.666-1.667s-.747-1.666-1.667-1.666zm-4.333 2.333c-.368 0-.667-.299-.667-.667s.298-.667.667-.667c.368 0 .667.298.667.667s-.299.667-.667.667z"/><path d="m14.333 12.667h-4.666c-.921 0-1.667.746-1.667 1.666 0 .921.746 1.667 1.667 1.667h4.667c.92 0 1.666-.746 1.666-1.667 0-.92-.746-1.666-1.667-1.666zm-3 2.333h-1.333c-.368 0-.667-.299-.667-.667s.299-.666.667-.666h1.333c.368 0 .667.299.667.667s-.299.666-.667.666z"/><path d="m14.333 8h-4.666c-.921 0-1.667.746-1.667 1.667 0 .92.746 1.667 1.667 1.667h4.667c.92 0 1.667-.746 1.667-1.667-.001-.921-.747-1.667-1.668-1.667zm-3 2.333h-1.333c-.368 0-.667-.299-.667-.667s.299-.666.667-.666h1.333c.368 0 .667.298.667.667 0 .368-.299.666-.667.666z"/></g></svg>
    <h2><?php echo __('We’ll be back soon!');?></h2>
    <div>
        <p><?php echo __('Sorry for the inconvenience but we&rsquo;re performing some maintenance at the moment. If you need help you can always');?> <a href="mailto:<?php echo $config->siteEmail; ?>"><?php echo __('contact us');?></a>, <?php echo __('otherwise we&rsquo;ll be back online shortly!');?></p>
        <p>&mdash; <?php echo $config->site_name;?></p>
    </div>
</article>