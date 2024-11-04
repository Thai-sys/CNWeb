<main class="flex items-center justify-center h-screen">
    <div class="bg-coffee-light p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold mb-4 text-center">Đăng Ký</h2>

        <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($error)): ?>
            <p class="text-red-500 mb-4 text-center"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form method="POST" action="/register" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium">Tên Đăng Nhập</label>
                <input type="text" id="username" name="username" required
                    class="mt-1 p-2 border border-gray-300 text-gray-900 rounded w-full" />
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium">Mật Khẩu</label>
                <input type="password" id="password" name="password" required
                    class="mt-1 p-2 border border-gray-300 text-gray-900 rounded w-full" />
            </div>
            <div class="mb-4">
                <label for="confirm_password" class="block text-sm font-medium">Xác Nhận Mật Khẩu</label>
                <input type="password" id="confirm_password" name="confirm_password" required
                    class="mt-1 p-2 border border-gray-300 text-gray-900 rounded w-full" />
            </div>
            <div class="mb-4">
                <label for="avatar" class="block text-sm font-medium">Ảnh Đại Diện (dưới 500kb)</label>
                <input type="file" id="avatar" name="avatar" accept="image/*"
                    class="mt-1 p-2 border border-gray-300 rounded w-full" />
            </div>
            <button type="submit"
                class="w-full bg-coffee-dark text-white py-2 px-4 rounded hover:bg-coffee transition duration-200">Đăng
                Ký</button>
        </form>

        <p class="mt-4 text-center">
            Đã có tài khoản? <a href="/login" class="text-coffee-dark font-bold">Đăng Nhập</a>
        </p>
    </div>
</main>