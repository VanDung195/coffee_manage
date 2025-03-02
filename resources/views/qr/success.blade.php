
 <!DOCTYPE html>
 <html lang="vi">
 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Hóa đơn</title>
     <link rel="stylesheet" href="{{ asset('css/invoice-test.css') }}">
     <style>
     </style>
 </head>
 <body>
    <div class="receipt">
        <h1>PROJECT 01</h1>
        <div class="center">
            <p class="center">123ABC, Thành phố Huế, Tỉnh TT Huế</p>
        </div>
        <div class="center">
            <p class="bold center">Bàn: T1_1</p>
        </div>
        <p>Thời gian: 12.09.2023 14:44</p>
        <p>Giờ in: 10:10</p>
        <div class="amount-row">
            <p>Giờ vào: 22:59</p>
            <p style="font-weight:100;">Giờ ra: 23:59</p>
        </div>
        <p>Thu ngân: SM</p>
        <p class="bold">Số Bill: <span class="bill-number">I23509003322023</span></p>

        <table class="custom-table">
            <thead>
                <tr>
                    <th>TT</th>
                    <th>Tên món</th>
                    <th>SL</th>
                    <th>Đ.Giá</th>
                    <th>T.Tiền</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Bánh Mì Gà Gà Kim Quất</td>
                    <td>1</td>
                    <td>25,000</td>
                    <td>25,000</td>
                </tr>
                <tr style="border-bottom: 1px solid black;">
                    <td>2</td>
                    <td>Bánh Mì</td>
                    <td>1</td>
                    <td>25,000</td>
                    <td>25,000</td>
                </tr>
            </tbody>
        </table>


        <div class="amount-row">
            <p class="bold">Tổng số lượng:</p>
            <p>1</p>
        </div>
        <div class="amount-row">
            <p class="bold">Thành tiền:</p>
            <p>25,000</p>
        </div>
        {{-- <div class="amount-row">
            <p>+ Giảm giá:</p>
            <p>8,000</p>
        </div> --}}
        <div class="amount-row bold">
            <p class="bold">Thanh Toán:</p>
            <p>17,000</p>
        </div>
        <div class="amount-row">
            <p class="bold">Tiền khách đưa:</p>
            <p>17,000</p>
        </div>
        <div class="amount-row">
            <p>Tiền thừa:</p>
            <p>0</p>
        </div>
        <p>Password Wifi: asdhjasdkjgaskjdh</p>
    </div>
 </body>
 </html>



