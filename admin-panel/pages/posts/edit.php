<?php
include "../../includes/layouts/header.php";

$categories = $db->query("SELECT * FROM categories");


$invalidInputTitle = '';
$invalidInputAuthor = '';
$invalidInputBody = '';
$invalidInputImage = '';

$message = '';

if (isset($_GET['id'])) {
    $post_id = $_GET['id'];
    $query = "SELECT * FROM posts WHERE id = :id";
    $post = $db->prepare($query);
    $post->execute(['id' => $post_id]);
    $post = $post->fetch();
}

if (isset($_POST['updatePost'])) {
    if (empty(trim($_POST['title']))) {
        $invalidInputTitle = 'فیلد نام الزامی است';
    }
    if (empty(trim($_POST['author']))) {
        $invalidInputAuthor = 'فیلد نویسنده الزامی است';
    }
    if (empty(trim($_FILES['image']['name']))) {
        $invalidInputImage = 'فیلد عکس الزامی است';
    }
    if (empty(trim($_POST['body']))) {
        $invalidInputBody = 'فیلد توضیحات الزامی است';
    }

    if (!empty(trim($_POST['title'])) && !empty(trim($_POST['author'])) && !empty(trim($_FILES['image']['name'])) && !empty(trim($_POST['body']))) {
        $title = $_POST['title'];
        $author = $_POST['author'];
        $category_id = $_POST['categoryId'];
        $body = $_POST['body'];

        $name_image = time() . "_" . $_FILES['image']['name'];
        $temp_name = $_FILES['image']['tmp_name'];
        $destination = __DIR__ . "/../../../php-course-blog-template/uploads/" . $name_image;
        if (move_uploaded_file($temp_name, $destination)) {
            $insertAddPost = $db->prepare("INSERT INTO posts (title,author,category_id,body,image) VALUES (:title,:author,:category_id,:body,:image)");
            $insertAddPost->execute(['title' => $title, 'author' => $author, 'category_id' => $category_id, 'body' => $body, 'image' => $name_image]);

            header("Location:index.php");
            exit();
        } else {
            echo "انتقال فایل انجام نشد!";
        }
    }
}

?>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar Section -->
            <?php
            include "../../includes/layouts/sidebar.php"
            ?>

            <!-- Main Section -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mb-5">
                <div
                        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom"
                >
                    <h1 class="fs-3 fw-bold">ویرایش مقاله</h1>
                </div>

                <!-- Posts -->
                <div class="mt-4">
                    <form class="row g-4">
                        <div class="col-12 col-sm-6 col-md-4">
                            <label class="form-label">عنوان مقاله</label>
                            <input
                                    type="text"
                                    name="title"
                                    class="form-control"
                                    value="<?= $post['title'] ?>"
                            />
                            <div class="form-text text-danger">
                                <?= $invalidInputTitle ?>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-md-4">
                            <label class="form-label">نویسنده مقاله</label>
                            <input
                                    type="text"
                                    class="form-control"
                                    value="<?= $post['author'] ?>"
                                    name="author"
                            />
                        </div>

                        <div class="col-12 col-sm-6 col-md-4">
                            <label class="form-label"
                            >دسته بندی مقاله</label
                            >
                            <select class="form-select" name="categoryId">
                                <?php if ($categories->rowCount() > 0): ?>
                                    <?php foreach ($categories as $category): ?>
                                        <option
                                            <?= ($post['category_id'] == $category['id']) ? 'selected' : '' ?>
                                                value="<?= $category['id'] ?>"><?= $category['title'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif ?>
                            </select>
                        </div>

                        <div class="col-12 col-sm-6 col-md-4">
                            <label for="formFile" class="form-label"
                            >تصویر مقاله</label
                            >
                            <input class="form-control" type="file"/>
                        </div>

                        <div class="col-12">
                            <label for="formFile" class="form-label"
                            >متن مقاله</label
                            >
                            <textarea class="form-control" rows="8" value="<?= $post['body'] ?>">
                            </textarea>
                        </div>


                        <div class="col-12 col-sm-6 col-md-4">
                            <img class="rounded" src="../../assets/images/1.jpg" width="300"/>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-dark">
                                ویرایش
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
<?php
include "../../includes/layouts/footer.php"

?>