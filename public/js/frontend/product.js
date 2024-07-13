const loadProducts = (page = 1, search = "") => {
    const baseUrl = `/products`;
    const url = `${baseUrl}?page=${page}&search=${search}`;

    $.ajax({
        url: url,
        type: "GET",
        success: function (response) {
            if (page === 1) {
                $("#products-container").html(response.html);
            } else {
                $("#products-container").append(response.html);
            }

            $("#pagination-links").html(response.pagination);
            if ($("#products-container").children().length === 0) {
                $("#products-container").html(
                    '<div class="col-12"><p class="text-center">No products found.</p></div>'
                );
            }
        },
        error: function (xhr) {
            console.log(xhr.responseText);
        },
    });
};

$(document).ready(function () {
    loadProducts();

    $(document).on("click", "#load-more", function (e) {
        e.preventDefault();
        const nextPage = $(this).data("next-page");
        const search = $('input[type="search"]').val();
        loadProducts(nextPage, search);
    });

    $('input[type="search"]').on("input", function () {
        const search = $(this).val();
        loadProducts(1, search);
    });

    $(document).on("click", ".add-to-cart", function (e) {
        e.preventDefault();
        const productId = $(this).data("product-id");

        $.ajax({
            url: "/cart/add",
            type: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr("content"),
                product_id: productId,
            },
            success: function (response) {
                if (response.status === "success") {
                    // alert(response.message);
                    window.Toast.fire({
                        icon: "success",
                        title: response.message,
                    });
                } else {
                    alert("Something went wrong. Please try again.");
                }
            },
            error: function (xhr) {
                alert("Something went wrong. Please try again.");
            },
        });
    });
});
