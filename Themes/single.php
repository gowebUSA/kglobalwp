<?php
/**
 * The template for displaying blog posts.
 *
 */

get_header();
$post_id = get_the_ID();
$post_type = get_post_type();?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<main class="blog-post">
  <?php
  	$hero_image = get_the_post_thumbnail_url();;
  	$hero_text = get_the_title();
  	$hero_content = get_sub_field('module_content', false, false);
  ?>

  <section id="hero" class="flex-container column justify-center hero <?php echo (!$hero_image) ? 'no-grad' : ''; ?> <?php echo true ? "hero-bottom-slant" : ""; ?>" style="background:url('<?php echo $hero_image; ?>') no-repeat center center, #2691AF;background-size:cover; background-attachment: fixed;">
  	<div class="inner-hero">
  		<h1 class="text-shadow"><?php echo $hero_text; ?></h1>
  		<h2><?php echo $hero_content; ?></h2>
  	</div>
  </section>

  <section class="flex-container module-padding pattern-bg blog-body">
    <aside class="flex-1-4 flex-col">
      <?php // getting rows of authors, if team member setting up post object, if guest then just grabbing name from text field
      if (have_rows('authors')) :  ?>
      <h5>by
        <?php while (have_rows('authors')) : the_row(); ?>

        <?php if (get_sub_field('author_type') === 'team_member') {
          $post = get_sub_field('author');
          setup_postdata($post);
          ?>

          <span><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></span>

          <?php wp_reset_postdata();
        } elseif (get_sub_field('author_type') == 'guest') { ?>
          <span><?php the_sub_field('guest_author_name'); ?></span>
        <?php } ?>
      <?php endwhile; ?>
      </h5>
      <?php endif; // end authors  ?>
      <?php if($post_type == 'post') { ?>
      <h5><?php echo get_the_date('F j, Y'); ?></h5>
      <?php } ?>
      <?php $post_categories = wp_get_post_categories( $post_id ); ?>
      <h4 class="categories"><?php
        foreach($post_categories as $c){
          $cat = get_category( $c );
           echo '<a href="/what-we-think/?cat='.$cat->slug.'">'.$cat->name.' </a>';
        }
      ?></h4>

      <div class="social">
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo get_the_permalink(); ?>" onclick="window.open(this.getAttribute('href'), 'newwindow', 'width=400,height=400'); return false;"><img src="<?php echo get_template_directory_uri(); ?>/dist/images/icon-facebook-orange.svg" alt=""></a>
        <a href="https://twitter.com/intent/tweet?text=<?php echo get_the_permalink(); ?>" onclick="window.open(this.getAttribute('href'), 'newwindow', 'width=400,height=400'); return false;"><img class="twitter-icon" src="<?php echo get_template_directory_uri(); ?>/dist/images/icon-twitter-orange.svg" alt=""></a>
        <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo get_the_permalink(); ?>&title=<?php echo get_the_title(); ?>" onclick="window.open(this.getAttribute('href'), 'newwindow', 'width=400,height=400'); return false;"><img src="<?php echo get_template_directory_uri(); ?>/dist/images/icon-linkedin-orange.svg" alt=""></a>
      </div>

      <?php $post_tags = wp_get_post_tags( $post_id ); ?>
      <h4 class="categories"><?php
        foreach($post_tags as $t){
          $tag = get_category( $t );
           echo '<a href="/what-we-think/?tag='.$tag->slug.'">'.$tag->name.' </a>';
        }
      ?></h4>

    </aside>
    <div class="flex-3-4 flex-col">
      <?php the_content(); ?>
    </div>
  </section>

  <section class="latest-blogs side-padding cream-offset-bg">
    <h2>Up Next</h2>
    <?php
    // grabbing recent posts
    // $post_type = 'post';
    $post_amount = 2;
    $cat = (isset($_GET['cat'])) ? $_GET['cat'] : '';
    $args = array(
      'post_type' => $post_type,
      'posts_per_page' => $post_amount,
      'category_name' => $cat,
      'post__not_in' => array($post_id),
      );
      $query = new WP_Query($args);
      ?>
    <div class="flex-container">

    <?php
    if($query->have_posts()) :
      while($query->have_posts() ) : $query->the_post();
      ?>

      <?php
      // template for post card that can be used elsewhere
      include('modules/components/'.$post_type.'_card.php'); ?>

      <?php endwhile; ?>
    <?php endif; ?>

    </div>

  </section>
  <?php $iterator = ''; ?>
  <?php include('modules/form.php'); ?>
  <!-- remove div (php is the original code) -->
  <div class="flex-4-5 flex-col" width="50%">
    <center>
      <?php echo do_shortcode('[wpforms id="4988"]');?>
    </center>
  </div>

</main>
<?php endwhile; endif; ?>
<?php get_footer(); ?>
