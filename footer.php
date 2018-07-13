<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package ShotStyle
 */
?>
		</div><!-- close .row -->
	</div><!-- close .container -->
    <div class="container">
        <div class="row">
        <!-- Place somewhere in the <body> of your page -->
        <div class="flexsliderSponsorsFooter carousel">
            <ul class="slides">
                <li class="col-sm-6 col-md-3">
                    <img src="http://localhost/wp-content/uploads/2018/05/300x225-300x225.png" />
                </li>
                <li class="col-sm-6 col-md-3">
                    <img src="http://localhost/wp-content/uploads/2018/05/300x225-300x225.png" />
                </li>
                <li class="col-sm-6 col-md-3">
                    <img src="http://localhost/wp-content/uploads/2018/05/300x225-300x225.png" />
                </li>
                <li class="col-sm-6 col-md-3">
                    <img src="http://localhost/wp-content/uploads/2018/05/300x225-300x225.png" />
                </li>
                <li class="col-sm-6 col-md-3">
                    <img src="http://localhost/wp-content/uploads/2018/05/300x225-300x225.png" />
                </li>
                <li class="col-sm-6 col-md-3">
                    <img src="http://localhost/wp-content/uploads/2018/05/300x225-300x225.png" />
                </li>
                <li class="col-sm-6 col-md-3">
                    <img src="http://localhost/wp-content/uploads/2018/05/300x225-300x225.png" />
                </li>
                <li class="col-sm-6 col-md-3">
                    <img src="http://localhost/wp-content/uploads/2018/05/300x225-300x225.png" />
                </li>
                <li class="col-sm-6 col-md-3">
                    <img src="http://localhost/wp-content/uploads/2018/05/300x225-300x225.png" />
                </li>
                <li class="col-sm-6 col-md-3">
                    <img src="http://localhost/wp-content/uploads/2018/05/300x225-300x225.png" />
                </li>
                <li class="col-sm-6 col-md-3">
                    <img src="http://localhost/wp-content/uploads/2018/05/300x225-300x225.png" />
                </li>
                <li class="col-sm-6 col-md-3">
                    <img src="http://localhost/wp-content/uploads/2018/05/300x225-300x225.png" />
                </li>
                <li class="col-sm-6 col-md-3">
                    <img src="http://localhost/wp-content/uploads/2018/05/300x225-300x225.png" />
                </li>
                <li class="col-sm-6 col-md-3">
                    <img src="http://localhost/wp-content/uploads/2018/05/300x225-300x225.png" />
                </li>
                <li class="col-sm-6 col-md-3">
                    <img src="http://localhost/wp-content/uploads/2018/05/300x225-300x225.png" />
                </li>
                <li class="col-sm-6 col-md-3">
                    <img src="http://localhost/wp-content/uploads/2018/05/300x225-300x225.png" />
                </li>
                <!-- items mirrored twice, total of 12 -->
            </ul>
        </div>
        </div>
    </div>
</div><!-- close .site-content -->

	<div id="footer-area">
		<div class="container footer-inner">
			<div class="row">
				<?php get_sidebar( 'footer' ); ?>
			</div>
		</div>

		<footer id="colophon" class="site-footer" role="contentinfo">
			<div class="site-info container">
				<div class="row">
					<?php if( of_get_option('footer_social') ) sparkling_social_icons(); ?>
					<nav role="navigation" class="col-md-6">
						<?php sparkling_footer_links(); ?>
					</nav>
					<div class="copyright col-md-6">
						<?php echo of_get_option( 'custom_footer_text', 'sparkling' ); ?>
						<?php /* sparkling_footer_info(); */ ?>
					</div>
				</div>
			</div><!-- .site-info -->
			<div class="scroll-to-top"><i class="fa fa-angle-up"></i></div><!-- .scroll-to-top -->
		</footer><!-- #colophon -->
	</div>
</div><!-- #page -->

<?php wp_footer(); ?>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-44704887-1', 'auto');
  ga('send', 'pageview');

</script>

</body>
</html>