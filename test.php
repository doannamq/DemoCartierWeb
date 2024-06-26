<?php

include('server/connection.php');

//use the search section
if (isset($_POST['search'])) {

    //1.determine page no
    if (isset($_GET['page_no']) && $_GET['page_no'] != "") {
        $page_no = $_GET['page_no'];
    } else {
        $page_no = 1;
    }

    $timkiem = $_POST['search_box'];
    $searchPattern = "%$timkiem%";
    $stmt1 = $conn->prepare("SELECT COUNT(*) AS total_records FROM products WHERE product_name LIKE ? OR product_category LIKE ?");
    $stmt1->bind_param('ss', $searchPattern, $searchPattern);
    $stmt1->execute();
    $stmt1->bind_result($total_records);
    $stmt1->store_result();
    $stmt1->fetch();


    $stmt2 = $conn->prepare("SELECT * FROM products WHERE product_name LIKE ? OR product_category LIKE ?");
    $stmt2->bind_param('ss', $searchPattern, $searchPattern);
    $stmt2->execute();
    $products = $stmt2->get_result();


    //return all products 
} else {

    //1.determine page no
    if (isset($_GET['page_no']) && $_GET['page_no'] != "") {
        $page_no = $_GET['page_no'];
    } else {
        $page_no = 1;
    }

    //2.return number of products
    $stmt1 = $conn->prepare("SELECT COUNT(*) AS total_records FROM products");
    $stmt1->execute();
    $stmt1->bind_result($total_records);
    $stmt1->store_result();
    $stmt1->fetch();


    //3.products per page
    $total_records_per_page  = 8;

    $offset = ($page_no - 1) * $total_records_per_page;

    $previous_page = $page_no - 1;
    $next_page = $page_no + 1;

    $adjacent = "2";

    $total_no_of_page = ceil($total_records / $total_records_per_page);


    //4.get all products
    $stmt2 = $conn->prepare("SELECT * FROM products LIMIT $offset, $total_records_per_page");
    $stmt2->execute();
    $products = $stmt2->get_result();
}

?>

<?php include('layouts/header.php'); ?>
<!--Product-list -->
<div style="display: flex; margin: 150px auto 0 auto; justify-content: space-around">
    <div class="dropdown">
        <button class="dropbtn">Sort by price</button>
        <div class="dropdown-content">
            <a href="#">Low to high</a>
            <a href="#">High to low</a>
            <a href="#">Recommended</a>
        </div>
    </div>
    <ul class="product-list">
        <li onclick="filterByCategory('ring')">
            <a>Nhẫn</a>
        </li>
        <li onclick="filterByCategory('necklace')">
            <a>Vòng cổ</a>
        </li>
        <li onclick="filterByCategory('bracelet')">
            <a>Vòng tay</a>
        </li>
        <li onclick="filterByCategory('watches')">
            <a>Đồng hồ</a>
        </li>
        <li onclick="filterByCategory('bag')">
            <a>Túi xách</a>
        </li>
        <li onclick="filterByCategory('glass')">
            <a>Kính mắt</a>
        </li>
        <li onclick="filterByCategory('perfume')">
            <a>Nước hoa</a>
        </li>
        <div>
            <i class="fa-solid fa-magnifying-glass" id="toggle-icon"></i>
        </div>
    </ul>
</div>
<!--Search-->
<section id="search">
    <form action="shop.php" method="POST" style="width: 900px;">
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

        <div class="product text-center col-lg-3 col-md-4 col-sm-12 pb-4">
            <img class="img-fluid mb-3" src="assets/imgs/<?php echo $row['product_image']; ?>" />
            <div class="star">
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
            </div>
            <h5 class="p-name"><?php echo $row['product_name']; ?></h5>
            <h4 class="p-price">$ <?php echo number_format($row['product_price'], 2, '.', ','); ?></h4>
            <a class="btn buy-btn" href="<?php echo "single_product.php?product_id=" . $row['product_id']; ?>">
                Mua ngay
            </a>
        </div>

        <?php } ?>

        <nav aria-label="Page navigation example">
            <ul class="pagination mt-5">

                <li class="page-item <?php if ($page_no <= 1) echo 'disable'; ?>">
                    <a class="page-link" href="<?php if ($page_no <= 1) echo '#';
                                                else echo "?page_no=" . ($page_no - 1); ?>">Trước</a>
                </li>

                <li class="page-item"><a class="page-link" href="?page_no=1">1</a></li>
                <li class="page-item"><a class="page-link" href="?page_no=2">2</a></li>
                <?php if ($page_no >= 3) { ?>
                <li class="page-item"><a class="page-link" href="#">...</a></li>
                <li class="page-item"><a class="page-link"
                        href="<?php echo "?page_no=" . $page_no; ?>"><?php echo $page_no; ?></a></li>
                <?php } ?>

                <li class="page-item <?php if ($page_no >= $total_no_of_page) echo 'disable'; ?>">
                    <a class="page-link" href="<?php if ($page_no >= $total_no_of_page) echo '#';
                                                else echo "?page_no=" . ($page_no + 1); ?>">
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
    document.querySelector('form').submit();
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

function filterByCategory(category) {
    var form_data = new FormData();
    form_data.append('category', category);

    var ajax_request = new XMLHttpRequest();
    ajax_request.open('POST', 'filter_product.php');
    ajax_request.send(form_data);

    ajax_request.onreadystatechange = function() {
        if (ajax_request.readyState == 4 && ajax_request.status == 200) {
            // Xử lý phản hồi từ máy chủ
            document.getElementById('shop').innerHTML = ajax_request.responseText;
        }
    }
}
</script>
<?php include('layouts/footer.php'); ?>