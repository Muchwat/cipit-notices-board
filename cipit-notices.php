<?php
/**
 * Plugin Name: CIPIT Custom Notices Board
 * Plugin URI: https://github.com/Muchwat/cipit-notices-board
 * Description: Implements a structured, responsive notice board using a custom post type, taxonomy, and blog-matching card design.
 * Version: 1.0.3
 * Author: Kevin Muchwat
 * Author URI: https://github.com/Muchwat
 * Text Domain: cipit-notices
 */

if (!defined('ABSPATH'))
    exit;

/*
|--------------------------------------------------------------------------
| 1. REGISTER CUSTOM POST TYPE: notice
|--------------------------------------------------------------------------
*/
function cipit_register_notice_cpt()
{

    $labels = array(
        'name' => __('CIPIT Notices', 'cipit-notices'),
        'singular_name' => __('Notice', 'cipit-notices'),
        'menu_name' => __('CIPIT Notices Board', 'cipit-notices'),
    );

    $args = array(
        'label' => __('CIPIT Notices', 'cipit-notices'),
        'public' => true,
        'menu_icon' => 'dashicons-megaphone',
        'supports' => array('title', 'editor'),
        'has_archive' => true,
        'show_in_rest' => true,
    );

    register_post_type('notice', $args);
}
add_action('init', 'cipit_register_notice_cpt');


/*
|--------------------------------------------------------------------------
| 2. REGISTER TAXONOMY: notice_category
|--------------------------------------------------------------------------
*/
function cipit_register_notice_taxonomy()
{

    $labels = array(
        'name' => __('Notice Categories', 'cipit-notices'),
        'singular_name' => __('Notice Category', 'cipit-notices'),
    );

    register_taxonomy('notice_category', 'notice', array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'rewrite' => array('slug' => 'notice-category'),
    ));
}
add_action('init', 'cipit_register_notice_taxonomy');


/*
|--------------------------------------------------------------------------
| 3. META BOX: Card Details
|--------------------------------------------------------------------------
*/
function cipit_add_card_details_meta_box()
{
    add_meta_box(
        'cipit_card_details_box',
        __('Notice Card Details', 'cipit-notices'),
        'cipit_render_card_details_box',
        'notice',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'cipit_add_card_details_meta_box');

function cipit_render_card_details_box($post)
{

    wp_nonce_field('cipit_save_card_details', 'cipit_card_nonce');

    $icon = get_post_meta($post->ID, '_cipit_icon_class', true) ?: 'fas fa-bullhorn';
    $text = get_post_meta($post->ID, '_cipit_button_text', true);
    $link = get_post_meta($post->ID, '_cipit_button_link', true);
    $target = get_post_meta($post->ID, '_cipit_button_target', true);

    ?>
    <p>
        <label><strong>Icon Class (Font Awesome)</strong></label>
        <input type="text" name="cipit_icon_class" value="<?php echo esc_attr($icon); ?>" style="width:100%;">
    </p>
    <p>
        <label><strong>Button Text</strong></label>
        <input type="text" name="cipit_button_text" value="<?php echo esc_attr($text); ?>" style="width:100%;">
    </p>
    <p>
        <label><strong>Button Link (Optional)</strong></label>
        <input type="url" name="cipit_button_link" value="<?php echo esc_url($link); ?>" style="width:100%;">
    </p>
    <p>
        <input type="checkbox" name="cipit_button_target" <?php checked($target, 'on'); ?>>
        Open link in new tab
    </p>
    <?php
}

function cipit_save_card_details($post_id)
{
    // Fix: Ensure correct nonce key is checked for both `ipit_card_nonce` and `cipit_card_nonce`
    if (
        !isset($_POST['cipit_card_nonce']) ||
        !wp_verify_nonce($_POST['cipit_card_nonce'], 'cipit_save_card_details')
    ) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;

    // Removed duplicated/typo fields in the array, using standard `cipit_` prefix
    $fields = [
        'cipit_icon_class' => '_cipit_icon_class',
        'cipit_button_text' => '_cipit_button_text',
        'cipit_button_link' => '_cipit_button_link',
        'cipit_button_target' => '_cipit_button_target',
    ];

    foreach ($fields as $field => $meta) {
        if (isset($_POST[$field])) {
            // Checkbox value needs special handling for delete_post_meta on unchecked
            if ($field === 'cipit_button_target') {
                update_post_meta($post_id, $meta, sanitize_text_field($_POST[$field]));
            } else {
                update_post_meta($post_id, $meta, sanitize_text_field($_POST[$field]));
            }
        } elseif ($field === 'cipit_button_target') {
            // Delete meta if the checkbox is not checked (since unchecked checkboxes aren't in $_POST)
            delete_post_meta($post_id, $meta);
        } else {
            // Delete meta for other fields if they are submitted blank
            delete_post_meta($post_id, $meta);
        }
    }
}
add_action('save_post', 'cipit_save_card_details');


/*
|--------------------------------------------------------------------------
| 4. SHORTCODE: [cipit_notices]
|--------------------------------------------------------------------------
*/
function cipit_notices_shortcode($atts)
{

    $atts = shortcode_atts([
        'count' => 3,
        'title' => 'Latest Notices',
        'description' => '', // <-- ADDED: New description attribute
        'category' => '',
    ], $atts);

    $query_args = [
        'post_type' => 'notice',
        'posts_per_page' => intval($atts['count']),
        'orderby' => 'date',
        'order' => 'DESC',
        'suppress_filters' => false, // Ensure hooks on queries still run
    ];

    if (!empty($atts['category'])) {
        $query_args['tax_query'] = [
            [
                'taxonomy' => 'notice_category',
                'field' => 'slug',
                'terms' => sanitize_text_field($atts['category']),
            ]
        ];
    }

    $q = new WP_Query($query_args);

    if (!$q->have_posts()) {
        return '';
    }

    ob_start();
    ?>
    <style>
        /* Section */
        .latest-news-section {
            padding-top: var(--section-padding);
            text-align: center;
        }

        .latest-news-section h2 {
            font-size: var(--h2-font-size);
            color: var(--primary-color);
            text-align: center;
            margin-bottom: 2rem;
            display: inline-block;
            padding-bottom: 5px;
            border-bottom: 3px solid var(--primary-color);
        }

        /* Style for the description */
        .latest-news-section .section-description {
            font-size: 1.1rem;
            color: var(--dark-gray);
            margin-top: 0;
            margin-bottom: var(--section-padding-small);
            max-width: 800px;
            /* Limit width for readability */
            margin-left: auto;
            margin-right: auto;
            line-height: 1.5;
        }


        /* Grid layout */
        .news-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            max-width: 1200px;
        }

        /* Card */
        .news-card {
            background: #fff;
            border-radius: var(--border-radius);
            padding: 2rem;
            border: 1px solid #eee;
            box-shadow: var(--card-shadow);
            transition: var(--card-transition);
            display: flex;
            flex-direction: column;
            height: 100%;
            border-top: 5px solid var(--primary-color);
        }

        .news-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.08);
        }

        /* HEADER: ICON LEFT + TAG RIGHT */
        .news-header-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        /* Icon */
        .news-icon {
            font-size: 2rem;
            color: var(--primary-color);
            opacity: 0.9;
            flex-shrink: 0;
        }

        /* Category tag using THEME STYLE (.post-tags a) */
        .news-category {
            /* Matching .tag-btn style for pill-tag look */
            background: #fff;
            border: 1px solid #ddd;
            padding: 0.25rem 0.9rem;
            border-radius: 30px;
            /* Use button-radius for a more rounded pill, matching .tag-btn */
            font-size: 0.85rem;
            color: var(--secondary-color);
            /* Use secondary color for text */
            font-weight: 600;

        }

        /* Content alignment */
        .news-content {
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
            flex-grow: 1;
        }

        .news-card h3 {
            font-size: var(--h4-font-size);
            color: var(--primary-color);
            margin: 0;
            font-weight: 700;
            text-align: left;
        }

        /* Justified text without ugly spacing */
        .news-card .notice-description {
            font-size: 1rem;
            color: var(--dark-gray);
            line-height: 1.55;
            text-align: justify;
            text-align-last: left;
            margin: 0;
            flex-grow: 1;
        }

        /* Button */
        .news-card .read-more {
            display: inline-flex;
            align-items: center;
            background: var(--secondary-color);
            color: #fff;
            padding: .7rem 1.8rem;
            border-radius: var(--button-radius);
            text-decoration: none;
            font-weight: 600;
            font-size: 1.05rem;
            margin-top: 1rem;
            align-self: flex-start;
            transition: var(--card-transition);
        }

        .news-card .read-more:hover {
            background: var(--primary-hover);
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(181, 5, 9, 0.25);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .news-list {
                grid-template-columns: 1fr;
            }

            .news-card {
                padding: 1.5rem;
            }

            .news-icon {
                font-size: 2rem;
            }

            .news-header-row {
                gap: 0.6rem;
            }

            .news-card h3 {
                font-size: calc(var(--h4-font-size) * 1.05);
            }

            .news-card .read-more {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
    <section class="latest-news-section">
        <h2><?php echo esc_html($atts['title']); ?></h2>

        <?php
        // Display the description if it's set
        if (!empty($atts['description'])): ?>
            <p class="section-description"><?php echo esc_html($atts['description']); ?></p>
        <?php endif; ?>

        <div class="news-list">

            <?php while ($q->have_posts()):
                $q->the_post(); ?>

                <?php
                $icon = get_post_meta(get_the_ID(), '_cipit_icon_class', true) ?: 'fas fa-bullhorn';
                $text = get_post_meta(get_the_ID(), '_cipit_button_text', true) ?: 'Read More';
                $link = get_post_meta(get_the_ID(), '_cipit_button_link', true);
                $target = get_post_meta(get_the_ID(), '_cipit_button_target', true);
                $url = $link ?: get_permalink();
                $target_attr = $target ? ' target="_blank" rel="noopener"' : '';

                $cat = get_the_terms(get_the_ID(), 'notice_category');
                $cat_name = $cat ? $cat[0]->name : 'General';
                ?>

                <div class="news-card">

                    <div class="news-header-row">
                        <i class="news-icon <?php echo esc_attr($icon); ?>"></i>
                        <span class="news-category"><?php echo esc_html($cat_name); ?></span>
                    </div>

                    <div class="news-content">
                        <h3><?php the_title(); ?></h3>
                        <p class="notice-description"><?php echo wp_trim_words(strip_tags(get_the_excerpt()), 25); ?></p>
                    </div>

                    <a href="<?php echo esc_url($url); ?>" class="read-more" <?php echo $target_attr; ?>>
                        <?php echo esc_html($text); ?>
                    </a>

                </div>

            <?php endwhile;
            wp_reset_postdata(); ?>

        </div>
    </section>

    <?php
    return ob_get_clean();
}
add_shortcode('cipit_notices', 'cipit_notices_shortcode');