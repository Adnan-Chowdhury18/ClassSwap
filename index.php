<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/auth.php';

$pageTitle = 'Home';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<section class="cs-hero">
  <div class="container text-center">
    <h1>Buy & Sell Student Essentials <br> Near You.</h1>
    <p class="lead mb-4">ClassSwap connects students to trade textbooks, electronics, and hostel essentials safely on campus.</p>
    <a href="<?= BASE_URL ?>products.php" class="btn cs-btn-primary btn-lg me-2">Browse Products</a>
    <a href="<?= BASE_URL ?>add-product.php" class="btn btn-light btn-lg">Sell an Item</a>
  </div>
</section>

<div class="container my-5">
  <h2 class="cs-section-title">Popular Categories</h2>
  <div class="row g-3">
    <?php
    $cats = getDB()->query("SELECT * FROM categories WHERE is_active = 1 ORDER BY category_name ASC")->fetchAll();
    foreach ($cats as $cat):
    ?>
      <div class="col-6 col-md-3 col-lg-2">
        <a class="cs-category-card" href="<?= BASE_URL ?>products.php?category_id=<?= (int)$cat['category_id'] ?>">
          <i class="bi <?= e($cat['icon'] ?: 'bi-tag') ?>"></i>
          <span><?= e($cat['category_name']) ?></span>
        </a>
      </div>
    <?php endforeach; ?>
  </div>

  <h2 class="cs-section-title mt-5">Recently Listed </h2>
  <div class="row g-4">
    <?php
    $stmt = getDB()->query(
        "SELECT p.*, c.category_name, a.area_name
         FROM products p
         JOIN categories c ON c.category_id = p.category_id
         JOIN areas a ON a.area_id = p.area_id
         WHERE p.status = 'Available'
         ORDER BY p.created_at DESC
         LIMIT 8"
    );
    $products = $stmt->fetchAll();

    if (empty($products)):
    ?>
      <div class="cs-empty-state">
        <i class="bi bi-inbox"></i>
        <p>No listings yet. Be the first to sell something on ClassSwap!</p>
      </div>
    <?php else: foreach ($products as $p): ?>
      <div class="col-6 col-md-4 col-lg-3">
        <div class="cs-product-card">
          <div class="cs-product-img-wrap">
            <img src="<?= PLACEHOLDER_PRODUCT_IMAGE ?>" alt="<?= e($p['title']) ?>" data-fallback="<?= PLACEHOLDER_PRODUCT_IMAGE ?>">
          </div>
          <div class="cs-product-body">
            <div class="cs-product-title"><?= e($p['title']) ?></div>
            <div class="cs-product-price mb-1"><?= formatPrice($p['price']) ?></div>
            <div class="cs-product-meta mb-2">
              <span class="cs-badge-condition"><?= e($p['item_condition']) ?></span>
              &middot; <?= e($p['area_name']) ?>
            </div>
            <a href="<?= BASE_URL ?>product-details.php?id=<?= (int)$p['product_id'] ?>" class="btn btn-sm cs-btn-outline mt-auto">View Details</a>
          </div>
        </div>
      </div>
    <?php endforeach; endif; ?>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>