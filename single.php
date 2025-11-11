<?php
include "./php-course-blog-template/include/layout/header.php";

if (isset($_GET['post'])) {
    $post_id = $_GET['post'];
    $query = "SELECT * FROM posts WHERE id = :id";
    $post = $db->prepare($query);
    $post->execute(['id' => $post_id]);
    $post = $post->fetch();
}
?>
<main>
    <section class="mt-4">
        <div class="row">
            <?php if (empty($post)) : ?>
                <div class="col-lg-8">
                    <div class="alert alert-danger">
                        مقاله ای یافت نشد
                    </div>
                </div>
            <?php else : ?>
                <!-- Posts & Comments Content -->
                <div class="col-lg-8">
                    <div class="row justify-content-center">
                        <!-- Post Section -->
                        <div class="col">
                            <div class="card">
                                <img
                                    src="./php-course-blog-template/uploads/<?= $post['image'] ?>"
                                    class="card-img-top"
                                    alt="post-image" />
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
                                    <p
                                        class="card-text text-secondary text-justify pt-3">
                                        <?= $post['body'] ?>
                                    </p>
                                    <div>
                                        <p class="fs-7 mb-0">
                                            نویسنده : <?= $post['author'] ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="mt-4" />

                        <!-- Comment Section -->
                        <div class="col">
                            <?php
                            $invalidInputName = '';
                            $invalidInputComment = '';
                            $message = '';
                            if (isset($_POST['submitComment'])) {
                                if (empty(trim($_POST['name']))) {
                                    $invalidInputName = 'فیلد نام الزامی است .';
                                } elseif (empty(trim($_POST['comment']))) {
                                    $invalidInputComment = 'فیلد متن کامنت الزامی است .';
                                } else {
                                    $name = $_POST['name'];
                                    $comment = $_POST['comment'];
                                    $commentInsert = $db->prepare("INSERT INTO comments (name,comment,post_id,status) VALUES (:name,:comment,:post_id,0)");
                                    $commentInsert->execute(['name' => $name, 'comment' => $comment,'post_id' => $post_id]);

                                    $message = "کامنت شما با موفقیت ثبت شد.";
                                }
                            }
                            ?>
                            <!-- Comment Form -->
                            <div class="card">
                                <div class="text-success p-3"><?= $message ?></div>
                                <div class="card-body">
                                    <p class="fw-bold fs-5">
                                        ارسال کامنت
                                    </p>

                                    <form method="post">
                                        <div class="mb-3">
                                            <label class="form-label">نام</label>
                                            <input
                                                type="text"
                                                name="name"
                                                class="form-control" />
                                            <div class="form-text text-danger">
                                                <?= $invalidInputName ?>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">متن کامنت</label>
                                            <textarea
                                                name="comment"
                                                class="form-control"
                                                rows="3"></textarea>
                                            <div class="form-text text-danger">
                                                <?= $invalidInputComment ?>
                                            </div>
                                        </div>
                                        <button
                                            name="submitComment"
                                            type="submit"
                                            class="btn btn-dark">
                                            ارسال
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <hr class="mt-4" />
                            <?php
                            $post_id = $post['id'];
                            $comments = $db->prepare("SELECT * FROM comments WHERE post_id = :id AND status = '1'");
                            $comments->execute(['id' => $post_id]);
                            ?>
                            <!-- Comment Content -->
                            <p class="fw-bold fs-6">تعداد کامنت : <?= $comments->rowCount() ?></p>
                            <?php if ($comments->rowCount() > 0) : ?>
                                <?php foreach ($comments as $comment) : ?>
                                    <div class="card bg-light-subtle mb-3">
                                        <div class="card-body">
                                            <div
                                                class="d-flex align-items-center">
                                                <img
                                                    src="./php-course-blog-template/uploads/profile.png"
                                                    width="45"
                                                    height="45"
                                                    alt="user-profle" />

                                                <h5
                                                    class="card-title me-2 mb-0">
                                                    <?= $comment['name'] ?>
                                                </h5>
                                            </div>

                                            <p class="card-text pt-3 pr-3">
                                                <?= $comment['comment'] ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            <?php else : ?>
                                <div class="alert alert-danger" role="alert">
                                    نظری برای این مقاله ثبت نشده است .
                                </div>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            <?php endif ?>

            <?php
            include "./php-course-blog-template/include/layout/sidebar.php"
            ?>
        </div>
    </section>
</main>

<?php
include "./php-course-blog-template/include/layout/footer.php"
?>