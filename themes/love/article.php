<?php
global $site_url;
?>
<?php require( $theme_path . 'main' . $_DS . 'mini-sidebar.php' );?>
<div class="container container-fluid container_new page-margin find_matches_cont">
    <div class="row r_margin">
        <?php if( IS_LOGGED ){ ?>
			<div class="col l3">
				<?php require( $theme_path . 'main' . $_DS . 'sidebar.php' );?>
			</div>
        <?php }?>
        <div class="col <?php if( IS_LOGGED ){ echo 'l9'; }else{ echo 'l12'; }?>">
			<div class="qd_read_blog_section">
				<div class="qd_read_blog_thumb">
					<img src="<?php echo GetMedia($data['article']['thumbnail']);?>" alt="<?php echo $data['article']['title'];?>" />
				</div>
							
				<div class="qd_read_blog_head">
					<div class="catz"><a href="<?php echo $site_url;?>/blog/<?php echo $data['article']['category'] . '_' . url_slug(html_entity_decode(Dataset::blog_categories()[$data['article']['category']]));?>" data-ajax="/blog/<?php echo $data['article']['category'] . '_' . url_slug(html_entity_decode(Dataset::blog_categories()[$data['article']['category']]));?>"><?php echo Dataset::blog_categories()[$data['article']['category']];?></a></div>
					<h1><?php echo $data['article']['title'];?></h1>
					<div class="">
						<time><svg xmlns="http://www.w3.org/2000/svg" width="19.299" height="19.301" viewBox="0 0 19.299 19.301"> <path id="Subtraction_34" data-name="Subtraction 34" d="M-7554.651,20h-18a.692.692,0,0,1-.464-.188.687.687,0,0,1-.186-.462v-16a.687.687,0,0,1,.186-.462.691.691,0,0,1,.464-.186h4.349V.7h1.3v2h6.7V.7h1.3v2h4.349a.691.691,0,0,1,.464.186.687.687,0,0,1,.186.462v16a.687.687,0,0,1-.186.462A.691.691,0,0,1-7554.651,20ZM-7572,10v8.7h16.7V10Zm0-6V8.7h16.7V4h-3.7V6h-1.3V4h-6.7V6h-1.3V4Zm14,11h-7.3V13.7h7.3V15Zm-10,0h-1.3V13.7h1.3V15Z" transform="translate(7573.3 -0.7)" fill="currentColor"/> </svg>&nbsp;&nbsp;<?php echo get_time_ago($data['article']['created_at']) ;?></time>&nbsp;&nbsp;&nbsp;&nbsp;
						<span class="views"><svg xmlns="http://www.w3.org/2000/svg" width="20.928" height="17.301" viewBox="0 0 20.928 17.301"> <path id="Subtraction_36" data-name="Subtraction 36" d="M-7562.824,18a10.653,10.653,0,0,1-10.464-8.651A10.653,10.653,0,0,1-7562.824.7a10.654,10.654,0,0,1,10.464,8.651A10.654,10.654,0,0,1-7562.824,18Zm0-15.992a9.367,9.367,0,0,0-5.828,2.038,9.393,9.393,0,0,0-3.294,5.225l-.017.078.017.078a9.46,9.46,0,0,0,3.292,5.235,9.373,9.373,0,0,0,5.83,2.038A9.373,9.373,0,0,0-7557,14.663a9.458,9.458,0,0,0,3.292-5.235l.019-.078-.019-.078A9.424,9.424,0,0,0-7557,4.047,9.37,9.37,0,0,0-7562.824,2.009Zm0,11.49a4.154,4.154,0,0,1-2.935-1.218,4.14,4.14,0,0,1-1.217-2.931,4.15,4.15,0,0,1,1.22-2.935,4.147,4.147,0,0,1,2.931-1.215,4.147,4.147,0,0,1,2.933,1.218,4.143,4.143,0,0,1,1.216,2.931,4.15,4.15,0,0,1-1.22,2.935A4.138,4.138,0,0,1-7562.824,13.5Zm0-7a2.865,2.865,0,0,0-2.019.833,2.872,2.872,0,0,0-.833,2.017,2.872,2.872,0,0,0,.833,2.017,2.865,2.865,0,0,0,2.019.833,2.865,2.865,0,0,0,2.017-.833,2.865,2.865,0,0,0,.833-2.017,2.865,2.865,0,0,0-.833-2.017A2.865,2.865,0,0,0-7562.824,6.5Z" transform="translate(7573.288 -0.7)" fill="currentColor"/> </svg>&nbsp;&nbsp;<?php echo $data['article']['view'];?> <?php echo __('Views');?></span>
					</div>
					<div class="blockquote">
						<blockquote><?php echo $data['article']['description'];?></blockquote>
					</div>
				</div>
				<article class="dt_blog_article"><?php echo br2nl( html_entity_decode( $data['article']['content'] ));?></article>
			</div>
			
			<div class="dt_blog_article_foot">
				<ul class="qd_read_blog_tags">
					<?php
						$tags = explode(',' , $data['article']['tags'] );
						foreach ($tags as $key => $tag) {
					?>
						<li><a href="<?php echo $site_url;?>/blog/<?php echo $tag;?>" data-ajax="/blog/<?php echo $tag;?>"><?php echo $tag;?></a></li>
					<?php }?>
				</ul>
				<ul class="qd_read_blog_share">
					<li>
						<a href="https://www.facebook.com/sharer.php?u=<?php echo $data['article']['url'];?>" target="_blank">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path fill="currentColor" d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/></svg>
						</a>
					</li>
					<li>
						<a href="http://twitter.com/intent/tweet?text=<?php echo $data['article']['title'];?>&amp;url=<?php echo $data['article']['url'];?>" target="_blank">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path fill="currentColor" d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"/></svg>
						</a>
					</li>
					<li>
						<a href="https://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo $data['article']['url'];?>" target="_blank">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path fill="currentColor" d="M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z"/></svg>
						</a>
					</li>
					<li>
						<a href="http://pinterest.com/pin/create/button/?url=<?php echo $data['article']['url'];?>" target="_blank">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path fill="currentColor" d="M204 6.5C101.4 6.5 0 74.9 0 185.6 0 256 39.6 296 63.6 296c9.9 0 15.6-27.6 15.6-35.4 0-9.3-23.7-29.1-23.7-67.8 0-80.4 61.2-137.4 140.4-137.4 68.1 0 118.5 38.7 118.5 109.8 0 53.1-21.3 152.7-90.3 152.7-24.9 0-46.2-18-46.2-43.8 0-37.8 26.4-74.4 26.4-113.4 0-66.2-93.9-54.2-93.9 25.8 0 16.8 2.1 35.4 9.6 50.7-13.8 59.4-42 147.9-42 209.1 0 18.9 2.7 37.5 4.5 56.4 3.4 3.8 1.7 3.4 6.9 1.5 50.4-69 48.6-82.5 71.4-172.8 12.3 23.4 44.1 36 69.3 36 106.2 0 153.9-103.5 153.9-196.8C384 71.3 298.2 6.5 204 6.5z"/></svg>
						</a>
					</li>
				</ul>
			</div>
        </div>
    </div>
</div>