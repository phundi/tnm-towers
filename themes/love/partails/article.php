<?php
global $site_url;
?>
<div class="col l6 m6">
	<div class="dt_sections articles qd_blog_lists">
		<div class="qd_blog_list_img">
			<a href="<?php echo $site_url;?>/article/<?php echo $article->id . '_' . url_slug(html_entity_decode($article->title),array('delimiter' => '-'));?>" data-ajax="/article/<?php echo $article->id . '_' . url_slug(html_entity_decode($article->title),array('delimiter' => '-'));?>">
				<img src="<?php echo GetMedia($article->thumbnail);?>" alt="<?php echo $article->title;?>">
			</a>
			<p><a href="<?php echo $site_url;?>/blog/<?php echo $article->category . '_' . url_slug(html_entity_decode(Dataset::blog_categories()[$article->category]));?>" data-ajax="/blog/<?php echo $article->category . '_' . url_slug(html_entity_decode(Dataset::blog_categories()[$article->category]));?>"><?php echo Dataset::blog_categories()[$article->category];?></a></p>
		</div>
		<div class="qd_blog_list_info">
			<h5><a href="<?php echo $site_url;?>/article/<?php echo $article->id . '_' . url_slug(html_entity_decode($article->title),array('delimiter' => '-'));?>" data-ajax="/article/<?php echo $article->id . '_' . url_slug(html_entity_decode($article->title),array('delimiter' => '-'));?>"><?php echo $article->title;?></a></h5>
			<p><?php echo $article->description;?></p>
			<div class="foot">
				<em><?php echo get_time_ago($article->created_at);?></em>
			</div>
		</div>
	</div>
</div>