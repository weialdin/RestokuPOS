document.addEventListener("DOMContentLoaded", function () {
    const categorySelect = document.getElementById("categorySelect");
    const menuContainer = document.getElementById("menuContainer");
    const tableNumber = document.getElementById("tableNumber");
    const selectedTableId = document.getElementById("selectedTableId");
    const selectedTableNumber = document.getElementById("selectedTableNumber");
    const checkoutForm = document.getElementById("checkoutForm");
    const cartInput = document.getElementById("cartInput");
    const cartTable = document.getElementById("cartTable");
    const totalAmount = document.getElementById("totalAmount");

    let cart = []; // Untuk menyimpan produk di keranjang

    // Fungsi untuk memperbarui keranjang
    const updateCart = () => {
        cartTable.innerHTML = ""; // Kosongkan tabel keranjang
        let total = 0;

        cart.forEach((item, index) => {
            const row = document.createElement("tr");

            // Buat baris keranjang dengan item
            row.innerHTML = `
                <td>${item.name}</td>
                <td>Rp${item.price.toLocaleString("id-ID")}</td>
                <td>
                    <input type="number" class="form-control form-control-sm quantity-input" 
                           data-index="${index}" value="${
                item.quantity
            }" min="1" />
                </td>
                <td>Rp${(item.price * item.quantity).toLocaleString(
                    "id-ID"
                )}</td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" data-index="${index}">Hapus</button>
                </td>
            `;
            cartTable.appendChild(row);

            total += item.price * item.quantity;
        });

        // Update total amount
        totalAmount.textContent = `Rp${total.toLocaleString("id-ID")}`;

        // Update hidden input dengan data cart dalam format JSON
        cartInput.value = JSON.stringify(cart);
    };

    // Event listener untuk kategori
    categorySelect.addEventListener("change", function () {
        const selectedCategoryId = this.value;

        // Sembunyikan semua kategori menu
        document.querySelectorAll(".menu-category").forEach((category) => {
            category.style.display = "none";
        });

        // Tampilkan kategori menu yang dipilih
        if (selectedCategoryId) {
            const categoryToShow = document.querySelector(
                `.menu-category[data-category-id="${selectedCategoryId}"]`
            );
            if (categoryToShow) categoryToShow.style.display = "block";
        }
    });

    // Event listener untuk menambah ke keranjang
    menuContainer.addEventListener("click", function (event) {
        if (event.target.classList.contains("add-to-cart")) {
            const menuId = event.target.dataset.id;
            const menuName = event.target.dataset.name;
            const menuPrice = parseInt(event.target.dataset.price);

            // Cari produk di keranjang
            const existingItem = cart.find((item) => item.id === menuId);

            if (existingItem) {
                // Jika sudah ada, tambahkan jumlahnya
                existingItem.quantity += 1;
            } else {
                // Jika belum ada, tambahkan item baru
                cart.push({
                    id: menuId,
                    name: menuName,
                    price: menuPrice,
                    quantity: 1,
                });
            }

            updateCart();
        }
    });

    // Event listener untuk menghapus dari keranjang
    cartTable.addEventListener("click", function (event) {
        if (event.target.classList.contains("btn-danger")) {
            const index = parseInt(event.target.dataset.index);
            cart.splice(index, 1);
            updateCart();
        }
    });

    // Event listener untuk mengubah jumlah item di keranjang
    cartTable.addEventListener("input", function (event) {
        if (event.target.classList.contains("quantity-input")) {
            const index = parseInt(event.target.dataset.index);
            const quantity = parseInt(event.target.value);

            // Validasi input jumlah
            if (quantity < 1) {
                alert("Jumlah tidak bisa kurang dari 1");
                event.target.value = 1;
                return;
            }

            // Update jumlah item dalam keranjang
            cart[index].quantity = quantity;
            updateCart();
        }
    });

    // Event listener untuk memilih nomor meja
    tableNumber.addEventListener("change", function () {
        const selectedOption = this.options[this.selectedIndex];
        selectedTableId.value = this.value; // ID meja
        selectedTableNumber.value = selectedOption.text; // Nomor meja
    });

    // Validasi form sebelum submit
    checkoutForm.addEventListener("submit", function (event) {
        // Pastikan meja dipilih
        if (!selectedTableId.value || !selectedTableNumber.value) {
            alert("Harap pilih nomor meja sebelum melakukan checkout!");
            event.preventDefault();
        } else if (cart.length === 0) {
            alert(
                "Keranjang belanja kosong! Tambahkan produk sebelum checkout."
            );
            event.preventDefault();
        }
    });
});

document
    .getElementById("checkoutForm")
    .addEventListener("submit", function (event) {
        // Ambil data keranjang dari tabel
        const cartItems = [];
        const cartRows = document.querySelectorAll("#cartTable tr");

        cartRows.forEach((row) => {
            const productId = row.getAttribute("data-id");
            const productName = row.querySelector(".product-name").innerText;
            const productPrice = parseFloat(
                row
                    .querySelector(".product-price")
                    .innerText.replace("Rp", "")
                    .replace(".", "")
            );
            const productQuantity = parseInt(
                row.querySelector(".product-quantity").value
            );

            if (productId && productQuantity > 0) {
                cartItems.push({
                    id: productId,
                    name: productName,
                    price: productPrice,
                    quantity: productQuantity,
                });
            }
        });

        // Masukkan data keranjang ke input tersembunyi
        document.getElementById("cartInput").value = JSON.stringify(cartItems);

        // Masukkan nomor meja ke input tersembunyi
        const tableNumberSelect = document.getElementById("tableNumber");
        const selectedTableId = tableNumberSelect.value;
        const selectedTableNumber =
            tableNumberSelect.options[tableNumberSelect.selectedIndex]?.text ||
            "";

        document.getElementById("selectedTableId").value = selectedTableId;
        document.getElementById("selectedTableNumber").value =
            selectedTableNumber;

        // Validasi jika keranjang kosong
        if (cartItems.length === 0) {
            event.preventDefault();
            alert("Keranjang tidak boleh kosong!");
        }
    });
