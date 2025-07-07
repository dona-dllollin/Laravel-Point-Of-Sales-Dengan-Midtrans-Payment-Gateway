document.addEventListener("DOMContentLoaded", function () {
    const itemsPerPage = 5;
    const productItems = document.querySelectorAll(".product-item");
    const paginationContainer = document.querySelector(".pagination");

    let currentPage = 1;

    function showPage(page) {
        currentPage = page;
        const start = (page - 1) * itemsPerPage;
        const end = start + itemsPerPage;

        productItems.forEach((item, index) => {
            item.style.display =
                index >= start && index < end ? "block" : "none";
        });

        setupPagination(); // refresh pagination UI
    }

    function setupPagination() {
        const pageCount = Math.ceil(productItems.length / itemsPerPage);
        paginationContainer.innerHTML = "";

        const maxVisible = 5; // jumlah maksimum halaman yang ditampilkan
        const delta = 2; // berapa banyak halaman di kiri/kanan current

        const range = [];
        const left = Math.max(2, currentPage - delta);
        const right = Math.min(pageCount - 1, currentPage + delta);

        range.push(1); // selalu tampilkan halaman 1

        if (left > 2) range.push("...");

        for (let i = left; i <= right; i++) {
            range.push(i);
        }

        if (right < pageCount - 1) range.push("...");

        if (pageCount > 1) range.push(pageCount); // selalu tampilkan halaman terakhir

        // Tampilkan tombol prev
        if (currentPage > 1) {
            const prev = document.createElement("li");
            prev.classList.add("page-item");
            prev.innerHTML = `<a href="#" class="page-link">«</a>`;
            prev.addEventListener("click", function (e) {
                e.preventDefault();
                showPage(currentPage - 1);
            });
            paginationContainer.appendChild(prev);
        }

        // Tampilkan nomor halaman
        range.forEach((p) => {
            const li = document.createElement("li");
            li.classList.add("page-item");
            li.innerHTML = `<a href="#" class="page-link">${p}</a>`;

            if (p === "...") {
                li.classList.add("disabled");
            } else {
                if (p === currentPage) li.classList.add("active");
                li.addEventListener("click", function (e) {
                    e.preventDefault();
                    showPage(p);
                });
            }

            paginationContainer.appendChild(li);
        });

        // Tampilkan tombol next
        if (currentPage < pageCount) {
            const next = document.createElement("li");
            next.classList.add("page-item");
            next.innerHTML = `<a href="#" class="page-link">»</a>`;
            next.addEventListener("click", function (e) {
                e.preventDefault();
                showPage(currentPage + 1);
            });
            paginationContainer.appendChild(next);
        }
    }

    // Inisialisasi
    showPage(1);
});
