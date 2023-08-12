<?php require( $theme_path . 'main' . $_DS . 'mini-sidebar.php' );?>
<div class="container container-fluid container_new page-margin find_matches_cont">
    <div class="row r_margin">
        <?php if(IS_LOGGED){ ?>
		<div class="col l3">
			<?php require( $theme_path . 'main' . $_DS . 'sidebar.php' );?>
		</div>
        <?php } ?>
		<div class="col <?php if(IS_LOGGED){ echo 'l6'; } else { echo 'l9'; }?>">
			<div class="valign-wrapper qd_blog_sub_hdr">
				<h4 class="bold"><?php echo __( 'Blog' );?></h4>
                <div class="qd_blog_srch">
					<input type="text" placeholder="<?php echo __( 'Search blog you want...' );?>" class="form-control" id="search-blog-input">
                    <span id="load-search-icon" class="hide">
                        <svg version="1.1" id="loader-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="25px" height="25px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve"><path fill="#333" d="M25.251,6.461c-10.318,0-18.683,8.365-18.683,18.683h4.068c0-8.071,6.543-14.615,14.615-14.615V6.461z" transform="rotate(65.2098 25 25)"><animateTransform attributeType="xml" attributeName="transform" type="rotate" from="0 25 25" to="360 25 25" dur="0.6s" repeatCount="indefinite"></animateTransform> </path></svg>
                    </span>
					<svg xmlns="http://www.w3.org/2000/svg" width="16.638" height="16.638" viewBox="0 0 16.638 16.638"> <path id="Path_6571" data-name="Path 6571" d="M3703.373,6944a7.373,7.373,0,1,1-7.373,7.373A7.374,7.374,0,0,1,3703.373,6944Zm0,13.107a5.734,5.734,0,1,0-5.734-5.734A5.73,5.73,0,0,0,3703.373,6957.107Zm6.955.057,2.31,2.318-1.155,1.155-2.318-2.31Z" transform="translate(-3696 -6944)" fill="#cc42bd"/> </svg>
                </div>
				<div class="qd_blog_cats_list">
					<a class="dropdown-trigger" href="#" data-target="blog_cat_dropdown"><span><?php echo __( 'Categories' );?>&nbsp;&nbsp;</span><svg xmlns="http://www.w3.org/2000/svg" width="19.701" height="19.698" viewBox="0 0 19.701 19.698"> <path id="Subtraction_33" data-name="Subtraction 33" d="M-5576.85,20a3.255,3.255,0,0,1-1.644-.474,3.1,3.1,0,0,1-1.044-1.425l-.035-.1h-4.127V16.3h4.127l.035-.1a3.1,3.1,0,0,1,1.044-1.425,3.269,3.269,0,0,1,1.644-.474,3.276,3.276,0,0,1,1.645.474,3.11,3.11,0,0,1,1.044,1.425l.035.1H-5564V18h-10.126l-.035.1a3.11,3.11,0,0,1-1.044,1.425A3.263,3.263,0,0,1-5576.85,20Zm0-4a1.1,1.1,0,0,0-.821.35,1.128,1.128,0,0,0-.328.8,1.134,1.134,0,0,0,.331.8,1.1,1.1,0,0,0,.818.346,1.1,1.1,0,0,0,.821-.35,1.121,1.121,0,0,0,.329-.8,1.134,1.134,0,0,0-.332-.8A1.1,1.1,0,0,0-5576.85,16Zm6-3a3.263,3.263,0,0,1-1.645-.474,3.11,3.11,0,0,1-1.044-1.425l-.035-.1H-5583.7V9.3h10.126l.035-.1a3.11,3.11,0,0,1,1.044-1.425,3.263,3.263,0,0,1,1.645-.474,3.261,3.261,0,0,1,1.644.474,3.11,3.11,0,0,1,1.044,1.425l.035.1H-5564V11h-4.127l-.035.1a3.11,3.11,0,0,1-1.044,1.425A3.26,3.26,0,0,1-5570.849,13Zm0-4a1.11,1.11,0,0,0-.823.35,1.129,1.129,0,0,0-.328.8,1.134,1.134,0,0,0,.331.8,1.109,1.109,0,0,0,.82.346,1.109,1.109,0,0,0,.821-.35,1.12,1.12,0,0,0,.328-.8,1.133,1.133,0,0,0-.331-.8A1.1,1.1,0,0,0-5570.849,9Zm-6-3a3.255,3.255,0,0,1-1.644-.474,3.1,3.1,0,0,1-1.044-1.425l-.035-.1h-4.127V2.3h4.127l.035-.1a3.1,3.1,0,0,1,1.044-1.425A3.255,3.255,0,0,1-5576.85.3a3.263,3.263,0,0,1,1.645.474,3.11,3.11,0,0,1,1.044,1.425l.035.1H-5564V4h-10.126l-.035.1a3.11,3.11,0,0,1-1.044,1.425A3.262,3.262,0,0,1-5576.85,6Zm0-4a1.1,1.1,0,0,0-.821.35,1.128,1.128,0,0,0-.328.8,1.134,1.134,0,0,0,.331.8,1.1,1.1,0,0,0,.818.346,1.1,1.1,0,0,0,.821-.35,1.121,1.121,0,0,0,.329-.8,1.134,1.134,0,0,0-.332-.8A1.1,1.1,0,0,0-5576.85,2Z" transform="translate(5583.7 -0.301)" fill="#828082"/> </svg></a>
					<ul id="blog_cat_dropdown" class="dropdown-content">
						<li><a href="<?php echo $site_url;?>/blog" data-ajax="/blog"><?php echo __('ALL');?></a></li>
						<?php
						$blog_categories = Dataset::blog_categories();
						foreach ($blog_categories as $key => $category) {
						?>
						<li><a href="<?php echo $site_url;?>/blog/<?php echo $key . '_' . url_slug(html_entity_decode($category));?>" data-ajax="/blog/<?php echo $key . '_' . url_slug(html_entity_decode($category));?>"><?php echo $category;?></a></li>
						<?php }?>
					</ul>
				</div>
			</div>
			<?php if (!empty($data['today_blog'])) { ?>
			
			<div class="article_of_the_day">
				<h3><div><?php echo __( 'Articles of the day' );?></div></h3>
				<div class="article_of_the_day_list">
					<a href="<?php echo $site_url;?>/article/<?php echo $data['today_blog']['id'] . '_' . url_slug(html_entity_decode($data['today_blog']['title']),array('delimiter' => '-'));?>" data-ajax="/article/<?php echo $data['today_blog']['id'] . '_' . url_slug(html_entity_decode($data['today_blog']['title']),array('delimiter' => '-'));?>">
						<div class="row">
							<div class="col l6">
								<div class="article_of_the_day_list_bg">
									<svg xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" xmlns:xlink="http://www.w3.org/1999/xlink" width="525" height="326" viewBox="0 0 525 326"> <defs> <linearGradient id="linear-gradient" x1="0.451" y1="0.339" x2="0.987" y2="0.981" gradientUnits="objectBoundingBox"> <stop offset="0" stop-color="#cc42bd" stop-opacity="0.22"/> <stop offset="1" stop-color="#86168b" stop-opacity="0.129"/> </linearGradient> </defs> <path id="modern-background-with-lines" d="M15,0H525a0,0,0,0,1,0,0V326a0,0,0,0,1,0,0H15A15,15,0,0,1,0,311V15A15,15,0,0,1,15,0Z" opacity="0.99" fill="url(#linear-gradient)" style="mix-blend-mode: luminosity;isolation: isolate"/> </svg>
									<div>
										<h4><?php echo($data['today_blog']['title']) ?></h4>
										<p><?php echo($data['today_blog']['description']) ?></p>
										<button type="button"><?php echo __( 'Read Now' );?></button>
									</div>
								</div>
							</div>
							<div class="col l6">
								<img src="<?php echo GetMedia($data['today_blog']['thumbnail']);?>">
							</div>
						</div>
					</a>
				</div>
			</div>
			<?php } ?>
			
			<div class="topic_match_for_you">
				<h3><div><?php echo __( 'Topic match for you' );?></div></h3>
				
				<div class="topic_match_for_you_cats">
					<a href="<?php echo $site_url;?>/blog" data-ajax="/blog"><?php echo __('ALL');?></a>
					<?php
						$blog_categories = Dataset::blog_categories();
						foreach ($blog_categories as $key => $category) {
					?>
						<a href="<?php echo $site_url;?>/blog/<?php echo $key . '_' . url_slug(html_entity_decode($category));?>" data-ajax="/blog/<?php echo $key . '_' . url_slug(html_entity_decode($category));?>"><?php echo $category;?></a>
					<?php }?>
				</div>
				
				<?php if(!empty($data['articles'])){ ?>
					<div class="row" id="articles_container">
						<?php echo $data['articles'];?>
					</div>
					<a href="javascript:void(0);" id="btn_load_more_articles" data-lang-nomore="<?php echo __('No more articles to show.');?>" data-ajax-post="/loadmore/articles" data-ajax-params="page=2" data-ajax-callback="callback_load_more_articles" class="btn waves-effect load_more"><?php echo __('Load more...');?></a>
				<?php }else{ ?>
					<?php require( $theme_path . 'partails' . $_DS . 'empty-article.php' );?>
				<?php }?>
			</div>
        </div>
		<div class="col l3">
			<div class="dt_left_sidebar">
				<div class="dt_blog_right_side">
					<h3><?php echo __( 'Continue Reading' );?></h3>
					<div class="continue_reading">
						<?php if (!empty($data['popular_blogs'])) { 
							  foreach ($data['popular_blogs'] as $key => $value) {
						?>
						<div class="continue_reading_list">
							<a href="<?php echo $site_url;?>/article/<?php echo $value['id'] . '_' . url_slug(html_entity_decode($value['title']),array('delimiter' => '-'));?>"  data-ajax="/article/<?php echo $value['id'] . '_' . url_slug(html_entity_decode($value['title']),array('delimiter' => '-'));?>">
								<img src="<?php echo GetMedia($value['thumbnail']);?>">
								<div>
									<p><?php echo($value['title']) ?></p>
									<time><?php echo get_time_ago($value['created_at']);?></time>
								</div>
							</a>
						</div>
						<?php }} ?>
					</div>
					
					<h3><?php echo __( 'More Topic' );?></h3>
					<div class="topic_match_for_you_cats side">
						<a href="<?php echo $site_url;?>/blog" data-ajax="/blog"><?php echo __('ALL');?></a>
						<?php
							$blog_categories = Dataset::blog_categories();
							foreach ($blog_categories as $key => $category) {
						?>
							<a href="<?php echo $site_url;?>/blog/<?php echo $key . '_' . url_slug(html_entity_decode($category));?>" data-ajax="/blog/<?php echo $key . '_' . url_slug(html_entity_decode($category));?>"><?php echo $category;?></a>
						<?php }?>
					</div>
				</div>
			</div>
		</div>
    </div>
</div>