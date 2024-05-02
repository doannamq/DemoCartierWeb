<?php
include('server/connection.php');
if (isset($_GET['sort'])) {
    $sort = $_GET['sort'];
} else {
    $sort = 'default';
}
//use the search section
if (isset($_POST['search'])) {
    //1.determine page no
    if (isset($_GET['page_no']) && $_GET['page_no'] != "") {
        $page_no = $_GET['page_no'];
    } else {
        $page_no = 1;
    }

    $total_no_of_page;

    $timkiem = $_POST['search_box'];
    $searchPattern = "%$timkiem%";
    $stmt1 = $conn->prepare("SELECT COUNT(*) AS total_records FROM products WHERE product_name LIKE ? OR product_category LIKE ?");
    $stmt1->bind_param('ss', $searchPattern, $searchPattern);
    $stmt1->execute();
    $stmt1->bind_result($total_records);
    $stmt1->store_result();
    $stmt1->fetch();

    $total_records_per_page = 8;
    $offset = 0;

    // Sửa đổi câu lệnh SQL khi tìm kiếm
    $stmt2 = $conn->prepare("SELECT * FROM products WHERE product_name LIKE ? OR product_category LIKE ? LIMIT $offset, $total_records_per_page");
    $stmt2->bind_param('ss', $searchPattern, $searchPattern);
    $stmt2->execute();
    $products = $stmt2->get_result();
} else if (isset($_POST['category'])) {
    if (isset($_GET['page_no']) && $_GET['page_no'] != "") {
        $page_no = $_GET['page_no'];
    } else {
        $page_no = 1;
    }

    $total_records_per_page = 8;
    $offset = 0;

    $category = $_POST['category'];
    $stmt3 = $conn->prepare("SELECT * FROM products WHERE product_category = ? LIMIT $offset, $total_records_per_page");
    $stmt3->bind_param('s', $category);
    $stmt3->execute();
    $products = $stmt3->get_result();
} else {

    //1.determine page no
    // Trong trường hợp không tìm kiếm
    if (isset($_GET['page_no']) && $_GET['page_no'] != "") {
        $page_no = $_GET['page_no'];
    } else {
        $page_no = 1;
    }

    $stmt1 = $conn->prepare("SELECT COUNT(*) AS total_records FROM products");
    $stmt1->execute();
    $stmt1->bind_result($total_records);
    $stmt1->store_result();
    $stmt1->fetch();

    $total_records_per_page  = 8;
    $offset = ($page_no - 1) * $total_records_per_page;
    $previous_page = $page_no - 1;
    $next_page = $page_no + 1;
    $adjacent = "2";
    $total_no_of_page = ceil($total_records / $total_records_per_page);

    if ($sort !== 'default') {
        $stmt2 = $conn->prepare("SELECT *, 
                                        CASE 
                                            WHEN product_special_offer > 0 THEN product_price - (product_price * product_special_offer / 100)
                                            ELSE product_price
                                        END AS discounted_price
                                 FROM products
                                 ORDER BY discounted_price $sort
                                 LIMIT $offset, $total_records_per_page");
    } else {
        $stmt2 = $conn->prepare("SELECT * FROM products LIMIT $offset, $total_records_per_page");
    }
    $stmt2->execute();
    $products = $stmt2->get_result();
}
?>

<?php include('layouts/header.php'); ?>
<!--Product-list -->
<div style="display: flex; margin: 150px auto 0 auto; justify-content: space-around">
    <div class="dropdown">
        <button class="dropbtn">Sắp xếp theo</button>
        <div class="dropdown-content">
            <a href="shop.php?sort=ASC">Giá: Thấp đến cao</a>
            <a href="shop.php?sort=DESC">Giá: Cao đến thấp</a>
            <a href="shop.php">Đề xuất</a>
        </div>
    </div>

    <form method="POST" action="shop.php">
        <ul class="product-list">
            <li><button type="submit" class="filter-category" name="category" value="ring">Nhẫn</button></li>
            <li><button type="submit" class="filter-category" name="category" value="necklace">Vòng cổ</button></li>
            <li><button type="submit" class="filter-category" name="category" value="bracelet">Vòng tay</button></li>
            <li><button type="submit" class="filter-category" name="category" value="watches">Đồng hồ</button></li>
            <li><button type="submit" class="filter-category" name="category" value="bag">Túi xách</button></li>
            <li><button type="submit" class="filter-category" name="category" value="glasses">Kính mắt</button></li>
            <li><button type="submit" class="filter-category" name="category" value="perfume">Nước hoa</button></li>
            <div>
                <i class="fa-solid fa-magnifying-glass" id="toggle-icon"></i>
            </div>
        </ul>
    </form>

</div>
<!--Search-->
<section id="search">
    <form action="shop.php" id="search-form" method="POST" style="width: 900px;">
        <div class="container-search-box">
            <div id="search-box"
                style="display: <?php echo isset($_POST['search']) ? 'block' : 'none'; ?>; width: 900px;">
                <input type="text" name="search_box" class="search-box" id="searchInput" placeholder="Tìm kiếm"
                    onkeyup="javascript:load_data(this.value)" autocomplete="off" style="width: 100%;" />
            </div>
        </div>
        <div class="form-group my-3 mx-3">
            <input type="hidden" name="search" value="Search" class="btn btn_primary" />
        </div>
        <span id="search_result" class="search-suggestions"></span>
    </form>
</section>

<!--Shop-->
<section id="shop" class="my-5 pb-5 mt-5">
    <div style="margin-top: -30px; text-align: center;">
        <h3>Sản phẩm</h3>
        <hr class="mx-auto">
        <p>Bạn có thể xem sản phẩm của chúng tôi tại đây</p>
    </div>
    <div class="row mx-auto container-fluid">

        <?php while ($row = $products->fetch_assoc()) { ?>

        <div class="product text-center col-lg-3 col-md-4 col-sm-12 pb-4" style="position: relative;">
            <?php if ($row['product_special_offer'] > 0) { ?>
            <div id="product-special-offer-shop">
                <h4><?php echo $row['product_special_offer'] ?> %</h4>
            </div>
            <?php } ?>

            <?php if ($row['product_quantity'] <= 0) { ?>
            <div id="product-quantity">
                <h4>SOLD OUT</h4>
            </div>
            <?php } ?>

            <img class="img-fluid mb-3" src="assets/imgs/<?php echo $row['product_image']; ?>" />
            <div class="star">
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
            </div>
            <h5 class="p-name"><?php echo $row['product_name']; ?></h5>

            <?php if ($row['product_special_offer'] > 0) { ?>
            <h5 class="p-price"><del>$ <?php echo number_format($row['product_price'], 2, '.', ','); ?></del></h5>
            <h4 class="p-price">$
                <?php echo number_format($row['product_price'] - $row['product_price'] * ($row['product_special_offer'] / 100), 2, '.', ','); ?>
            </h4>
            <?php } else { ?>
            <h4 class="p-price">$ <?php echo number_format($row['product_price'], 2, '.', ','); ?></h4>
            <?php } ?>

            <?php if ($row['product_quantity'] > 0) { ?>
            <a class="btn buy-btn" href="<?php echo "single_product.php?product_id=" . $row['product_id']; ?>">
                Mua ngay
            </a>
            <?php } else { ?>
            <input type="submit" value="Mua ngay" class="btn buy-btn" disabled />
            <?php } ?>
            <!-- <a class="btn buy-btn" href="<?php echo "single_product.php?product_id=" . $row['product_id']; ?>">
                    Mua ngay
                </a> -->
        </div>

        <?php } ?>
        <nav aria-label="Page navigation example">
            <ul class="pagination mt-5">

                <li class="page-item <?php if ($page_no <= 1) echo 'disable'; ?>">
                    <a class="page-link"
                        href="<?php if ($page_no <= 1) echo '#';
                                                else echo "?page_no=" . ($page_no - 1) . "&sort=" . $sort; ?>">Trước</a>
                </li>

                <li class="page-item"><a class="page-link" href="?page_no=1&sort=<?php echo $sort; ?>">1</a></li>
                <li class="page-item"><a class="page-link" href="?page_no=2&sort=<?php echo $sort; ?>">2</a></li>
                <?php if ($page_no >= 3) { ?>
                <li class="page-item"><a class="page-link" href="#">...</a></li>
                <li class="page-item"><a class="page-link"
                        href="<?php echo "?page_no=" . $page_no . "&sort=" . $sort; ?>"><?php echo $page_no; ?></a></li>
                <?php } ?>

                <li class="page-item">
                    <a class="page-link" href="<?php if ($page_no >= $total_no_of_page) echo '#';
                                                else echo "?page_no=" . ($page_no + 1) . "&sort=" . $sort; ?>">
                        Tiếp theo
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</section>
<script>
function get_text(event) {
    var string = event.textContent;
    document.getElementsByName('search_box')[0].value = string;
    document.getElementById('search_result').innerHTML = '';
    document.querySelector('#search-form').submit();
    document.getElementById('search_result').classList.remove('show');
}


function load_data(query, showSearchBox) {
    if (query.length > 0) {
        var form_data = new FormData();

        form_data.append('query', query);

        var ajax_request = new XMLHttpRequest();

        ajax_request.open('POST', 'process_data.php');

        ajax_request.send(form_data);

        ajax_request.onreadystatechange = function() {
            if (ajax_request.readyState == 4 && ajax_request.status == 200) {
                var response = JSON.parse(ajax_request.responseText);

                var html = '<div class="list-group">';

                if (response.length > 0) {
                    for (var count = 0; count < response.length; count++) {
                        html +=
                            '<a href="#" class="list-group-item list-group-item-action" onClick="get_text(this)">' +
                            response[count].product_name + '</a>';
                        document.getElementById('search_result').classList.add('show');
                    }
                } else {
                    html +=
                        '<a href="#" class="list-group-item list-group-item-action disabled">Không tìm thấy sản phẩm</a>'
                }

                html += '</div>'

                document.getElementById('search_result').innerHTML = html;
            }
        }
    } else {
        document.getElementById('search_result').innerHTML = '';
    }

    if (showSearchBox !== undefined) {
        toggleSearchBox(showSearchBox);
    }
}

let toggle = false;
const searchBox = document.getElementById('search-box');
const toggleIcon = document.getElementById('toggle-icon');
const loadMoreBtn = document.getElementById('btn-load-more');
const loadData = document.getElementById("load-data");


function toggleSearchBox(showSearchBox) {
    if (showSearchBox) {
        searchBox.style.display = 'block';
        toggleIcon.className = 'fa-solid fa-times';
        document.getElementsByName('search_box')[0].focus();
        load_data(document.getElementsByName('search_box')[0].value);
    } else {
        searchBox.style.display = 'none';
        toggleIcon.className = 'fa-solid fa-magnifying-glass';
        document.getElementById('search_result').innerHTML = '';
    }
    toggle = showSearchBox;
}


document.getElementById('toggle-icon').addEventListener('click', function() {
    toggleSearchBox(!toggle);
});
</script>
<?php include('layouts/footer.php'); ?>