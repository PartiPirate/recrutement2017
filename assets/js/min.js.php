<?php
header ( 'Content-type: text/javascript' );

include("../../engine/utils/JShrink.php");

ob_start();
	
include ('jquery-1.10.2.js');
include ('bootstrap/bootstrap.min.js');
include ('modernizr.custom.js');

include ('plugins/jquery.easing/jquery.easing.1.3.js');
include ('plugins/jquery.fitvids/jquery.fitvids.js');
include ('plugins/jquery.fs.wallpaper/jquery.fs.wallpaper.min.js');
include ('plugins/jquery.magnific-popup/jquery.magnific-popup.min.js');
include ('plugins/owl.carousel/owl.carousel.min.js');
include ('plugins/scrollReveal/scrollReveal.js');
include ('plugins/stellar/stellar.js');
include ('plugins/SmoothScroll/SmoothScroll.js');
include ('plugins/jqBootstrapValidation/jqBootstrapValidation.js');
include ('contact_me.js');
include ('plugins/isotope/isotope.pkgd.min.js');

include ('spectrum.nav.js');
include ('spectrum.js');
include ('pp.js');

$js = ob_get_clean();
$js = \JShrink\Minifier::minify($js, array('flaggedComments' => false));

echo $js;

?>