<div class="live_comments" live_comment_id="<?php echo $comment['id']; ?>"> 
  
  <a class="left" href="<?php echo $site_url;?>/@<?php echo $comment['publisher']->username?>"> 
    <img class="live_avatar left" src="<?php echo($comment['publisher']->avater->avater) ?>" alt="avatar"> 
  </a> 
  <?php if (!empty($post) && $post->live_ended != 0 && ($comment['user_id'] == auth()->id || auth()->admin == 1 || $post->user_id == auth()->id)) { ?>
      <button type="button" class="btn btn-flat close waves-effect modal-close right" onclick="RemoveCommentVideo(<?php echo $comment['id'];?>,'hide')">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z"></path></svg>
      </button>
  <?php } ?>

  <div class="comment-body" style="/*float: left;*/"> 
    <div class="comment-heading"> 
      <span> 
        <a href="<?php echo $site_url;?>/@<?php echo $comment['publisher']->username?>" data-ajax="/@<?php echo $comment['publisher']->username?>"> 
          <h4 class="live_user_h"> <?php echo ($comment['publisher']->first_name !== '' ) ? $comment['publisher']->first_name . ' ' . $comment['publisher']->last_name : $comment['publisher']->username;?></h4> 
        </a> 
      </span>
      <div class="comment-text"><?php echo $comment['text']; ?></div> 
    </div> 
  </div>
  <div class="clear"></div>
</div>