
{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>

    </style>
</head>
<body>
    <h1>Thành cmnr công rồi nhé, hãy chờ 1 xíu</h1>
    <h1 id="test" style="display: none">asdada</h1>

    
</body>
</html>
@vite(['resources/js/app.js'])
<script type="module">
    // Echo.channel('order-placed')
    //     .listen('InvoicePlaced', (event) => {
    //         console.log(123123123123s);
    //     });

    window.Echo.channel('order-channel')
        .listen('InvoicePlaced', (e) => {
            console.log(e);
            document.getElementById('test').style.display = 'block';
        })
</script>    
 --}}


{{-- Đẹp đấy --}}
 {{-- <!DOCTYPE html>
 <html lang="en">
 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Hoá đơn</title>
     <style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f2f2f2;
    margin: 0;
    padding: 0;
}

.invoice {
    max-width: 800px;
    margin: 30px auto;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #ddd;
}

.logo img {
    width: 100px;
}

.title h1 {
    margin: 0;
    font-size: 24px;
    color: #333;
}

.title p {
    margin: 5px 0;
    font-size: 14px;
    color: #666;
}

.title span {
    font-weight: bold;
}

.customer-info, .products, .total, .footer {
    margin-top: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    border: 1px solid #ddd;
    padding: 10px;
}

th {
    background-color: #f2f2f2;
}

.total {
    margin-top: 20px;
    padding-top: 10px;
    border-top: 1px solid #ddd;
}

.total p {
    margin: 5px 0;
    font-size: 16px;
}

.footer p {
    margin: 5px 0;
    font-size: 14px;
    color: #666;
}

     </style>
 </head>
 <body>
     <div class="invoice">
         <div class="header">
             <div class="logo">
                 <img src="logo.png" alt="Logo">
             </div>
             <div class="title">
                 <h1>Quán Nước Ép Trái Cây</h1>
                 <p>Số hoá đơn: <span>HD001</span></p>
                 <p>Ngày xuất hoá đơn: <span>02/05/2024</span></p>
             </div>
         </div>
         <div class="customer-info">
             <h2>Thông tin khách hàng</h2>
             <p><strong>Tên:</strong> Nguyễn Văn A</p>
             <p><strong>Địa chỉ:</strong> 123 Đường ABC, Quận XYZ</p>
             <p><strong>Số điện thoại:</strong> 0123 456 789</p>
         </div>
         <div class="products">
             <h2>Danh sách sản phẩm</h2>
             <table>
                 <tr>
                     <th>Sản phẩm</th>
                     <th>Số lượng</th>
                     <th>Giá thành</th>
                 </tr>
                 <tr>
                     <td>Cam ép</td>
                     <td>2</td>
                     <td>20,000đ</td>
                 </tr>
                 <!-- Thêm các sản phẩm khác nếu cần -->
             </table>
         </div>
         <div class="total">
             <p><strong>Tổng cộng:</strong> 40,000đ</p>
             <p><strong>Phương thức thanh toán:</strong> Tiền mặt</p>
         </div>
         <div class="footer">
             <p>Người xuất hoá đơn: Nguyễn Văn B</p>
             <p>Liên hệ: 0123 456 789</p>
         </div>
     </div>
 </body>
 </html> --}}
 


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hoá đơn</title>
    <style>
        body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f2f2f2;
    margin: 0;
    padding: 0;
}

.invoice {
    max-width: 400px;
    margin: 30px auto;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #ddd;
}

.logo img {
    width: 80px;
}

.title h1 {
    margin: 0;
    font-size: 20px;
    color: #333;
}

.title p {
    margin: 5px 0;
    font-size: 14px;
    color: #666;
}

.title span {
    font-weight: bold;
}

.customer-info, .products, .total, .footer {
    margin-top: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    border: 1px solid #ddd;
    padding: 10px;
}

th {
    background-color: #f2f2f2;
}

.total {
    margin-top: 20px;
    padding-top: 10px;
    border-top: 1px solid #ddd;
}

.total p {
    margin: 5px 0;
    font-size: 16px;
}

.footer p {
    margin: 5px 0;
    font-size: 14px;
    color: #666;
}

    </style>
</head>
<body>
    <div class="invoice">
        <div class="header">
            <div class="logo">
                <img src="logo.png" alt="Logo">
            </div>
            <div class="title">
                <h1>Quán Nước Ép Trái Cây</h1>
                <p>Số hoá đơn: <span>HD001</span></p>
                <p>Ngày xuất hoá đơn: <span>02/05/2024</span></p>
            </div>
        </div>
        <div class="customer-info">
            <h2>Thông tin khách hàng</h2>
            <p><strong>Tên:</strong> Nguyễn Văn A</p>
            <p><strong>Địa chỉ:</strong> 123 Đường ABC, Quận XYZ</p>
            <p><strong>Số điện thoại:</strong> 0123 456 789</p>
        </div>
        <div class="products">
            <h2>Danh sách sản phẩm</h2>
            <table>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá thành</th>
                </tr>
                <tr>
                    <td>Cam ép</td>
                    <td>2</td>
                    <td>20,000đ</td>
                </tr>
                <!-- Thêm các sản phẩm khác nếu cần -->
            </table>
        </div>
        <div class="total">
            <p><strong>Tổng cộng:</strong> 40,000đ</p>
            <p><strong>Phương thức thanh toán:</strong> Tiền mặt</p>
        </div>
        <div class="footer">
            <p>Người xuất hoá đơn: Nguyễn Văn B</p>
            <p>Liên hệ: 0123 456 789</p>
        </div>
    </div>
</body>
</html> 




