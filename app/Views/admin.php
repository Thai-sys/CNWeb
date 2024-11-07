<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Trị</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        nav {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 16rem;
            background-color: #1a202c;
            color: white;
        }

        main {
            margin-left: 16rem;
            padding: 1.5rem;
            height: calc(100vh - 2rem);
            overflow-y: auto;
        }

        #orderManagementSection .order-list {
            max-height: 400px;
            overflow-y: auto;
            padding-right: 15px;
        }
    </style>
    <script>
    function showSection(sectionId) {
        const sections = [
            "dashboardSection",
            "productManagementSection",
            "addProductSection",
            "userManagementSection",
            "contactManagementSection",
            "orderManagementSection"
        ];
        sections.forEach(id => {
            document.getElementById(id).style.display = (id === sectionId) ? "block" : "none";
        });
    }

    function showAddProductForm() {
        showSection("addProductSection");
    }


    </script>
</head>

<body class="bg-gray-100 font-sans">
    <div class="flex">
        <nav class="shadow-lg">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-center mb-6">Quản Trị</h2>
                <ul class="space-y-4">
                    <li><a href="#" onclick="showSection('dashboardSection')" class="block py-2 pl-3 rounded hover:bg-gray-700">Dashboard</a></li>
                    <li><a href="#" onclick="showSection('productManagementSection')" class="block py-2 pl-3 rounded hover:bg-gray-700">Quản lý sản phẩm</a></li>
                    <li><a href="#" onclick="showSection('userManagementSection')" class="block py-2 pl-3 rounded hover:bg-gray-700">Quản lý người dùng</a></li>
                    <li><a href="#" onclick="showSection('contactManagementSection')" class="block py-2 pl-3 rounded hover:bg-gray-700">Quản lý contact</a></li>
                    <li><a href="#" onclick="showSection('orderManagementSection')" class="block py-2 pl-3 rounded hover:bg-gray-700">Quản lý đơn hàng</a></li>
                </ul>
            </div>
        </nav>

        <main>
            <!-- Phần Dashboard -->
            <div id="dashboardSection" class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Dashboard</h2>
                <p class="text-gray-600 mb-6">Chào mừng đến với bảng điều khiển quản trị.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-blue-50 p-4 rounded-lg shadow-sm">
                        <h3 class="font-semibold text-blue-800">Số người dùng</h3>
                        <p class="text-3xl font-bold text-blue-600">
                            <?= isset($userCount) ? $userCount : 'N/A'; ?>
                        </p>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg shadow-sm">
                        <h3 class="font-semibold text-green-800">Số sản phẩm</h3>
                        <p class="text-3xl font-bold text-green-600">
                            <?= isset($productCount) ? $productCount : 'N/A'; ?>
                        </p>
                    </div>
                    <div class="bg-yellow-50 p-4 rounded-lg shadow-sm">
                        <h3 class="font-semibold text-yellow-800">Tổng doanh thu</h3>
                        <p class="text-3xl font-bold text-yellow-600">
                            <?= isset($totalRevenue) ? number_format($totalRevenue, 0, ',', '.') . ' VNĐ' : 'N/A'; ?>
                        </p>
                    </div>
                    <div class="bg-red-50 p-4 rounded-lg shadow-sm">
                        <h3 class="font-semibold text-red-800">Tổng đơn hàng cần xử lý</h3>
                        <p class="text-3xl font-bold text-red-600">
                            <?= isset($pendingOrderCount) ? $pendingOrderCount : 'N/A'; ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Phần Quản lý sản phẩm -->
            <div id="productManagementSection" class="bg-white rounded-lg shadow-md p-6 hidden">
                <h2 class="text-2xl font-semibold text-gray-800">Quản lý sản phẩm</h2>
                <div class="overflow-hidden mt-4">
                    <?php $this->productModel->displayAllProducts(); ?>
                </div>
            </div>

            <!-- Phần Thêm sản phẩm -->
            <div id="addProductSection" class="bg-white rounded-lg shadow-md p-6 hidden">
                <?php echo $this->productModel->renderAddProductForm(); ?>
            </div>

            <!-- Phần Quản lý người dùng -->
            <div id="userManagementSection" class="bg-white rounded-lg shadow-md p-6 hidden">
                <h2 class="text-2xl font-semibold text-gray-800">Quản lý người dùng</h2>
                <div class="overflow-hidden mt-4">
                    <?php $this->userModel->renderUserList(); ?>
                </div>
            </div>

            <!-- Phần Quản lý contact -->
            <div id="contactManagementSection" class="bg-white rounded-lg shadow-md p-6 hidden">
                <h2 class="text-2xl font-semibold text-gray-800">Quản lý contact</h2>
                <div class="overflow-hidden mt-4">
                    <?php $this->contactModel->displayContacts(); ?>
                </div>
            </div>

            <!-- Phần Quản lý đơn hàng -->
            <div id="orderManagementSection" class="bg-white rounded-lg shadow-md p-6 hidden">
                <div class="flex space-x-4">
                    <div class="flex-1 bg-white rounded-lg shadow p-4 order-list">
                        <h2 class="text-xl font-semibold text-gray-800">Đơn hàng chưa xử lý</h2>
                        <div class="mt-4">
                            <?php $this->orderModel->displayPendingOrders(); ?>
                        </div>
                    </div>

                    <div class="flex-1 bg-white rounded-lg shadow p-4 order-list">
                        <h2 class="text-xl font-semibold text-gray-800">Đơn hàng đã xử lý</h2>
                        <div class="mt-4">
                            <?php $this->orderModel->displayProcessedOrders(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>
