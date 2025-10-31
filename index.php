<?php
include "./php-course-blog-template/include/layout/header.php";
/** @var PDO $db */

error_reporting(E_ALL);
ini_set('display_errors', 1);
if (isset($_GET['category'])) {
    $category_id = $_GET['category'];
    $query = "SELECT * FROM posts WHERE category_id = :id ORDER BY id DESC ";
    $posts = $db->prepare($query);
    $posts->execute(['id' => $category_id]);
} else {
    $query = "SELECT * FROM posts ORDER BY id DESC ";
    $posts = $db->query($query);
}

?>

    <main>

        <?php
        include "./php-course-blog-template/include/layout/slider.php"
        ?>
        <!-- Content Section -->
        <section class="mt-4">
            <div class="row">
                <!-- Posts Content -->
                <div class="col-lg-8">
                    <div class="row g-3">
                        <?php if ($posts->rowCount() > 0) : ?>
                            <?php foreach ($posts as $post): ?>
                                <div class="col-sm-6">
                                    <div class="card">
                                        <img
                                                src="./php-course-blog-template/uploads/<?= $post['image'] ?>"
                                                class="card-img-top"
                                                alt="post-image"/>
                                        <div class="card-body">
                                            <div
                                                    class="d-flex justify-content-between">
                                                <h5 class="card-title fw-bold">
                                                    <?= $post['title'] ?>
                                                </h5>
                                                <div>
                                                    <span class="badge text-bg-secondary">
                                                      <?php
                                                      $category_id = $post['category_id'];
                                                      $category = $db->query("SELECT * FROM categories WHERE id = $category_id")->fetch();
                                                      ?>
                                                      <?= $category['title'] ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <p class="card-text text-secondary pt-3">
                                                <?= substr($post['body'], 0, 500) . "..." ?>
                                            </p>
                                            <div
                                                    class="d-flex justify-content-between align-items-center">
                                                <a
                                                        href="single.html"
                                                        class="btn btn-sm btn-dark">مشاهده</a>

                                                <p class="fs-7 mb-0">
                                                    نویسنده : <?= $post['author'] ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        <?php else : ?>
                            <div class="col">
                                <div class="alert alert-danger">
                                    مقاله ای یافت نشد ....
                                </div>
                            </div>
                        <?php endif ?>
                    </div>
                </div>

                <?php
                include "./php-course-blog-template/include/layout/sidebar.php"
                ?>
            </div>
        </section>
    </main>

<?php
include "./php-course-blog-template/include/layout/footer.php"
?>