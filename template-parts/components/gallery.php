<?php 
    $sheen_all_gallery_images = acf_photo_gallery('image_gallery', $post->ID);  
    if( count($sheen_all_gallery_images) > 0 ) :
?>
<div class="c-single__slider">
    <div class="c-single__wrapper c-single__wrapper-has-border">
        <h3 class="c-single__slider-title h3--bold"><?php sheen_get_acf_text( 'gallery_title' ); ?></h3>
        <p class="c-single__slider-desc"><?php sheen_get_acf_text( 'gallery_description' ); ?></p>
    </div>
    <?php sheen_get_gallery(get_the_ID()); ?>
</div>
<?php endif; ?>