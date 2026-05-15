<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header" id="site-header">
  <button class="menu-toggle" id="menu-toggle" aria-label="Toggle menu">
    <span></span>
    <span></span>
    <span></span>
  </button>

  <div class="site-header__logo">
    <a href="<?php echo esc_url(home_url('/')); ?>">
      <img src="<?php echo esc_url(get_theme_file_uri('/assets/logo-final.png')); ?>" alt="Touris Club" />
    </a>
  </div>

  <div class="site-header__spacer"></div>
</header>

<!-- DESKTOP NAV (hidden on mobile) -->
<nav class="site-header__nav" id="desktop-nav">
  <?php
  wp_nav_menu(array(
    'theme_location' => 'primary',
    'container'      => false,
    'items_wrap'     => '<ul class="nav-menu">%3$s</ul>',
    'fallback_cb'    => 'touris_fallback_menu',
  ));
  ?>
</nav>

<!-- MOBILE SIDEBAR (hidden on desktop) -->
<div class="mobile-sidebar" id="mobile-sidebar">
  <div class="mobile-sidebar__header">
    <button class="mobile-sidebar__close" id="sidebar-close" aria-label="Close menu">
      <i class="fas fa-times"></i>
    </button>
  </div>
  <div class="mobile-sidebar__content">
    <?php touris_mobile_menu(); ?>
  </div>
</div>
<div class="mobile-sidebar__overlay" id="sidebar-overlay"></div>

<?php
// --- Desktop fallback (used when no WP menu assigned) ---
function touris_fallback_menu() {
  $menu_items = touris_get_menu_structure();

  foreach ($menu_items as $label => $value) {
    if (is_array($value) && isset($value['children'])) {
      echo '<div class="nav-dropdown">';
      echo '<a href="#" class="nav-dropdown__trigger">' . esc_html($label) . ' <span class="nav-dropdown__icon"><i class="fas fa-chevron-down"></i><i class="fas fa-plus"></i></span></a>';
      echo '<div class="nav-dropdown__menu">';
      foreach ($value['children'] as $child_label => $child_link) {
        echo '<a href="' . esc_url($child_link) . '">' . esc_html($child_label) . '</a>';
      }
      echo '</div></div>';
    } else {
      echo '<a href="' . esc_url($value) . '">' . esc_html($label) . '</a>';
    }
  }
}

// --- Mobile sidebar menu (always uses its own structure) ---
function touris_mobile_menu() {
  // If a WP menu is assigned, build from it
  $locations = get_nav_menu_locations();
  if (isset($locations['primary'])) {
    $menu = wp_get_nav_menu_object($locations['primary']);
    if ($menu) {
      $items = wp_get_nav_menu_items($menu->term_id);
      if ($items) {
        // Build parent/child structure
        $parents = array();
        $children = array();
        foreach ($items as $item) {
          if ($item->menu_item_parent == 0) {
            $parents[] = $item;
          } else {
            $children[$item->menu_item_parent][] = $item;
          }
        }

        foreach ($parents as $parent) {
          $has_children = isset($children[$parent->ID]);
          echo '<div class="msb-item' . ($has_children ? ' msb-item--has-children' : '') . '">';
          echo '<a href="' . esc_url($parent->url) . '" class="msb-item__link">' . esc_html($parent->title) . '</a>';
          if ($has_children) {
            echo '<button class="msb-item__toggle" aria-label="Toggle submenu">+</button>';
            echo '<div class="msb-item__sub">';
            foreach ($children[$parent->ID] as $child) {
              echo '<a href="' . esc_url($child->url) . '" class="msb-item__sublink">' . esc_html($child->title) . '</a>';
            }
            echo '</div>';
          }
          echo '</div>';
        }
        return;
      }
    }
  }

  // Fallback: use hardcoded structure
  $menu_items = touris_get_menu_structure();
  foreach ($menu_items as $label => $value) {
    if (is_array($value) && isset($value['children'])) {
      echo '<div class="msb-item msb-item--has-children">';
      echo '<a href="#" class="msb-item__link">' . esc_html($label) . '</a>';
      echo '<button class="msb-item__toggle" aria-label="Toggle submenu">+</button>';
      echo '<div class="msb-item__sub">';
      foreach ($value['children'] as $child_label => $child_link) {
        echo '<a href="' . esc_url($child_link) . '" class="msb-item__sublink">' . esc_html($child_label) . '</a>';
      }
      echo '</div></div>';
    } else {
      echo '<div class="msb-item">';
      echo '<a href="' . esc_url($value) . '" class="msb-item__link">' . esc_html($label) . '</a>';
      echo '</div>';
    }
  }
}

// --- Shared menu structure ---
function touris_get_menu_structure() {
  return array(
    'Restaurant' => array(
      'children' => array(
        'Monastery Hall' => '#',
        'Traditional Tavern' => '#',
        'Verandas' => '#',
        'Pool & Gardens' => '#',
      ),
    ),
    'Events' => array(
      'children' => array(
        'Weddings' => '#',
        'Christenings' => '#',
        'Corporate Events' => '#',
        'Seminars' => '#',
      ),
    ),
    'Enoteca' => array(
      'children' => array(
        'Wine Tasting' => '#',
        'Cooking Classes' => '#',
      ),
    ),
    'Farm to Table' => array(
      'children' => array(
        'Bees' => '#',
        'Olives' => '#',
        'Secret Supper' => '#',
      ),
    ),
    'Lodges' => array(
      'children' => array(
        'Country House' => '#',
        'Attic' => '#',
        'Coco Lodge' => '#',
      ),
    ),
    'Shop' => '#',
    'About' => array(
      'children' => array(
        'Our Story' => '#',
        'Contact' => '#',
      ),
    ),
  );
}
?>