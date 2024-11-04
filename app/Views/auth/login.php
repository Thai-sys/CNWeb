<main class="flex items-center justify-center h-screen">
    <div class="login-form bg-coffee-light p-8 rounded-lg shadow-lg w-full max-w-md">
        <!-- Hiển thị thông báo thành công -->
        <?php if (!empty($success_message)): ?>
            <p class="text-green-600 mb-4 text-center"><?php echo htmlspecialchars($success_message); ?></p>
        <?php endif; ?>

        <h2 class="text-2xl font-bold mb-4 text-center">Đăng Nhập</h2>

        <!-- Hiển thị thông báo lỗi -->
        <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($error)): ?>
            <p class="text-red-500 mb-4 text-center"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <!-- Form đăng nhập -->
        <form method="POST" action="/login" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium">Tên Đăng Nhập</label>
                <input type="text" id="username" name="username" required
                    class="mt-1 p-2 border border-gray-300  text-gray-900 rounded w-full focus:ring-2 focus:ring-coffee-light" />
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium">Mật Khẩu</label>
                <input type="password" id="password" name="password" required
                    class="mt-1 p-2 border border-gray-300 text-gray-900 rounded w-full focus:ring-2 focus:ring-coffee-light" />
            </div>

            <button type="submit"
                class="bg-coffee-dark text-coffee-light py-2 px-4 rounded w-full hover:bg-coffee-light transition duration-300">Đăng
                Nhập</button>
        </form>

        <p class="mt-4 text-center">
            Chưa có tài khoản? <a href="/register" class="text-coffee-dark underline hover:text-coffee-light">Đăng
                Ký</a>
        </p>
    </div>
</main>