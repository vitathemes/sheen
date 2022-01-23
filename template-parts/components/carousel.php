<?php 
    $brilliance_all_carousel_images = acf_photo_gallery('image_carousel', $post->ID);  
    if( count($brilliance_all_carousel_images) > 0 ) :
?>
<div class="c-single__slider c-single__slider--carousel">
    <div class="c-single__wrapper c-single__wrapper-has-border c-single__wrapper--carousel">
        <h3 class="c-single__slider-title h3--bold"><?php brilliance_get_acf_text( 'carousel_title' ); ?></h3>
        <p class="c-single__slider-desc"><?php brilliance_get_acf_text( 'carousel_description' ); ?></p>
    </div>
    <?php brilliance_get_carousel(get_the_ID()); ?>
</div>
<?php endif; ?>