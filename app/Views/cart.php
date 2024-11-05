 <!-- app/Views/cart/cart.php -->
 <?php include '../app/Views/partials/header.php'; // Bao gồm header 
    ?>

 <body class="bg-gray-100">
     <div class="container mx-auto px-4 py-6">
         <h1 class="text-2xl font-semibold mb-4">Giỏ Hàng </h1>

         <!-- thông báo cập nhật, xóa sản phẩm, đặt hàng thành công -->
         <?php if (isset($_SESSION['success'])): ?>
             <div class="flex items-center p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                 <svg class="w-5 h-5 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20"
                     xmlns="http://www.w3.org/2000/svg">
                     <path
                         d="M10 0a10 10 0 1 1 0 20 10 10 0 0 1 0-20zm1 15l-5-5 1.414-1.414L10 12.586l4.586-4.586L16 8l-5 5z" />
                 </svg>
                 <span><?php echo $_SESSION['success']; ?></span>
                 <button type="button"
                     class="ml-auto -mx-1.5 -my-1.5 rounded-md focus:ring-2 focus:ring-green-600 p-1.5 hover:bg-green-200 inline-flex items-center justify-center"
                     aria-label="Close" onclick="this.parentElement.remove()">
                     <span class="sr-only">Close</span>
                     <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                         <path fill-rule="evenodd"
                             d="M10 9.293l-3.646-3.646-1.414 1.414L8.586 10l-3.646 3.646 1.414 1.414L10 10.414l3.646 3.646 1.414-1.414L11.414 10l3.646-3.646-1.414-1.414L10 9.293z"
                             clip-rule="evenodd" />
                     </svg>
                 </button>
             </div>
             <?php unset($_SESSION['success']); // Xóa thông báo sau khi hiển thị 
                ?>
         <?php endif; ?>

         <!-- thông báo lỗi -->
         <?php if (isset($_SESSION['error'])): ?>
             <div class="flex items-center p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                 <svg class="w-5 h-5 mr-2 text-red-600" fill="currentColor" viewBox="0 0 20 20"
                     xmlns="http://www.w3.org/2000/svg">
                     <path
                         d="M10 0a10 10 0 1 1 0 20 10 10 0 0 1 0-20zm1 15l-5-5 1.414-1.414L10 12.586l4.586-4.586L16 8l-5 5z" />
                 </svg>
                 <span><?php echo $_SESSION['error']; ?></span>
                 <button type="button"
                     class="ml-auto -mx-1.5 -my-1.5 rounded-md focus:ring-2 focus:ring-red-600 p-1.5 hover:bg-red-200 inline-flex items-center justify-center"
                     aria-label="Close" onclick="this.parentElement.remove()">
                     <span class="sr-only">Close</span>
                     <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                         <path fill-rule="evenodd"
                             d="M10 9.293l-3.646-3.646-1.414 1.414L8.586 10l-3.646 3.646 1.414 1.414L10 10.414l3.646 3.646 1.414-1.414L11.414 10l3.646-3.646-1.414-1.414L10 9.293z"
                             clip-rule="evenodd" />
                     </svg>
                 </button>
             </div>
             <?php unset($_SESSION['error']); // Xóa thông báo sau khi hiển thị 
                ?>
         <?php endif; ?>

         <?php if ($_SESSION['cart_count'] == 0): ?>
             <div class="text-center p-4 border border-red-500 bg-red-100 text-red-700">
                 <h2 class="text-xl font-semibold">Hiện không có sản phẩm nào trong giỏ hàng.</h2>
                 <p>Hãy đến trang sản phẩm để mua sắm ngay!</p>
                 <a href="/product" class="mt-4 inline-block bg-blue-500 text-white rounded px-4 py-2">Xem Sản Phẩm</a>
             </div>
         <?php else: ?>
             <table class="min-w-full bg-coffee-light border border-[#a0a0a0]">
                 <thead>
                     <tr style="background-color: #4a2c2a;color: white;">
                         <th class="py-2 px-4 border">Hình ảnh</th>
                         <th class="py-2 px-4 border">Tên sản phẩm</th>
                         <th class="py-2 px-4 border">Giá</th>
                         <th class="py-2 px-4 border">Số lượng</th>
                         <th class="py-2 px-4 border">Tổng</th>
                         <th class="py-2 px-4 border">Thao tác</th>
                     </tr>
                 </thead>
                 <tbody>
                     <?php
                        $totalAmount = 0; // Tổng tiền cần thanh toán
                        foreach ($cartItems as $item):
                            $subtotal = $item['price'] * $item['quantity']; // Tính tổng cho từng sản phẩm
                            $totalAmount += $subtotal; // Cộng vào tổng tiền
                        ?>
                         <tr>
                             <td class="py-2 px-4 border">
                                 <img src="<?php echo $item['image_url']; ?>" alt="<?php echo $item['product_name']; ?>"
                                     class="h-16 w-16 object-cover">
                             </td>
                             <td class="py-2 px-4 border"><?php echo $item['product_name']; ?></td>
                             <td class="py-2 px-4 border"><?php echo number_format($item['price'], 0, ',', '.'); ?> VNĐ</td>
                             <td class="py-2 px-4 border">
                                 <form action="/cart/update" method="POST">
                                     <input type="hidden" name="cart_item_id" value="<?php echo $item['id']; ?>">
                                     <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1"
                                         class="w-16 border rounded p-1 bg-coffee-light">
                                     <button type="submit" class="bg-blue-500 text-white rounded px-2 py-1">Cập nhật</button>
                                 </form>
                             </td>
                             <td class="py-2 px-4 border"><?php echo number_format($subtotal, 0, ',', '.'); ?> VNĐ</td>
                             <td class="py-2 px-4 border">
                                 <form action="/cart/remove" method="POST">
                                     <input type="hidden" name="cart_item_id" value="<?php echo $item['id']; ?>">
                                     <button type="submit" class="bg-red-500 text-white rounded px-2 py-1">Xóa</button>
                                 </form>
                             </td>
                         </tr>
                     <?php endforeach; ?>
                 </tbody>
             </table>
             <div class="mt-4">
                 <h2 class="text-xl font-semibold">Tổng tiền cần thanh toán: <span
                         class="text-green-600"><?php echo number_format($totalAmount, 0, ',', '.'); ?> VNĐ</span></h2>
                 <!-- Button "Mua Hàng" để mở modal -->
                 <a href="javascript:void(0)" onclick="openOrderModal()"
                     class="mt-4 inline-block bg-green-500 text-white rounded px-4 py-2">Mua Hàng</a>

                 <!-- Modal đặt hàng -->
                 <div id="orderModal"
                     class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
                     <div class="bg-white w-96 p-6 rounded-lg shadow-lg">
                         <h2 class="text-xl font-semibold mb-4">Thông Tin Đặt Hàng</h2>
                         <form id="orderForm" action="/cart/Orders" method="POST">
                             <div class="mb-4">
                                 <label for="name" class="block text-gray-700 font-medium">Họ và Tên:</label>
                                 <input type="text" name="name" id="name" required class="w-full border rounded p-2 mt-1">
                             </div>
                             <div class="mb-4">
                                 <label for="address" class="block text-gray-700 font-medium">Địa Chỉ:</label>
                                 <textarea name="address" id="address" required
                                     class="w-full border rounded p-2 mt-1"></textarea>
                             </div>
                             <div class="mb-4">
                                 <label for="phone" class="block text-gray-700 font-medium">Số Điện Thoại:</label>
                                 <input type="tel" name="phone" id="phone" required class="w-full border rounded p-2 mt-1">
                             </div>
                             <div class="text-gray-700 font-medium mb-4">
                                 Tổng tiền cần thanh toán: <span
                                     id="totalAmountDisplay"><?php echo number_format($totalAmount, 0, ',', '.'); ?>
                                     VNĐ</span>
                             </div>
                             <div class="flex justify-end">
                                 <button type="button" onclick="closeOrderModal()"
                                     class="bg-gray-500 text-white rounded px-4 py-2 mr-2">Hủy</button>
                                 <button type="submit" class="bg-green-500 text-white rounded px-4 py-2">Đặt Hàng</button>
                             </div>
                         </form>
                     </div>
                 </div>

                 <!-- JavaScript để mở và đóng modal -->
                 <script>
                     function openOrderModal() {
                         document.getElementById('orderModal').classList.remove('hidden');
                     }

                     function closeOrderModal() {
                         document.getElementById('orderModal').classList.add('hidden');
                     }
                 </script>

             <?php endif; ?>
             </div>
 </body>

 <?php include '../app/Views/partials/footer.php'; // Bao gồm footer 
    ?>