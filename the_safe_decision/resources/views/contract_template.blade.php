<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عقد   
 تأجير سيارة (Rent A Car Contract)</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: black;
            direction: rtl;
            text-align: right;
        }

        .contract-container {
            width: 100%;
            max-width: 210mm;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border: 2px solid #000;
            page-break-after: always;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            position: relative;
        }

        .header img.logo {
            max-width: 200px;
        }

        .header img.center-top-image {
            max-width: 10%;  /* Set max width to 10% */
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
        }

        .header h1 {
            margin: 0;
            color: #D6B046;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 12px;
        }

        table, th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: right;
        }

        th {
            background-color: #D6B046;
            color: white;
        }

        .fuel-section {
            margin-top: 20px;
        }

        .fuel-labels {
            display: flex;
            justify-content: space-between;
            padding-top: 5px;
            font-size: 14px;
        }

        .fuel-label {
            display: flex;
            align-items: center;
        }

        .fuel-checkbox {
            margin-left: 10px;
        }

        .print-button {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background-color: #D6B046;
            color: white;
            border: none;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }

        .print-button:hover {
            background-color: #B59634;
        }
    </style>
</head>

<body>
    <button class="print-button" onclick="window.print()">Print Contract</button>

    <div class="contract-container">
        <div class="header">
            <img class="center-top-image" src="https://via.placeholder.com/150" alt="Center Top Image">
            <img class="logo" src={{ $rentalContract->institution->logo_image }} alt="شعار تأجير سيارات (Rent A Car Logo)">
            <h1>عقد تأجير سيارة (Rent A Car Contract)</h1>
            <p>اسم الشركة، الفرع، تفاصيل الاتصال ({{  $rentalContract->institution->name }}, {{ $rentalContract->institution->address_ar }}, {{  $rentalContract->institution->emergency_number }})</p>
            <p>رقم العقد: <span class="bold">{{ $rentalContract->id }}</span> (Contract No: {{ $rentalContract->id }})</p>
        </div>

        <table>
            <tr>
                <th>اسم المستأجر (Renter's Name)</th>
                <th>رقم الهاتف (Phone Number)</th>
                <th>واتساب (WhatsApp)</th>
                <th>العنوان (Address)</th>
            </tr>
            <tr>
                <td>{{ $rentalContract->tenant->first_name }} {{ $rentalContract->tenant->middle_name }} {{ $rentalContract->tenant->last_name }}</td>
                <td>{{ $rentalContract->tenant->phone_number }}</td>
                <td>{{ $rentalContract->tenant->whatsapp_number }}</td>
                <td>{{ $rentalContract->tenant->region  }}</td>
            </tr>
            <tr>
                <th>نوع السيارة (Type of Car)</th>
                <th>رقم اللوحة (Plate No.)</th>
                <th>الكيلومترات عند الاستلام (Kilometers In)</th>
                <th>الكيلومترات عند التسليم (Kilometers Out)</th>
            </tr>
            <tr>
                {{-- <td>{{ $contract->car_type }}</td>
                <td>{{ $contract->plate_number }}</td>
                <td>{{ $contract->kilometers_in }}</td>
                <td>{{ $contract->kilometers_out }}</td> --}}
            </tr>
        </table>

        <table>
            <tr>
                <th>تاريخ الاستئجار (Date of Rent)</th>
                <th>وقت الاستئجار (Time of Rent)</th>
                <th>تاريخ التسليم (Date of Return)</th>
                <th>وقت التسليم (Time of Return)</th>
            </tr>
            <tr>
                {{-- <td>{{ $contract->rent_date }}</td>
                <td>{{ $contract->rent_time }}</td>
                <td>{{ $contract->return_date }}</td>
                <td>{{ $contract->return_time }}</td> --}}
            </tr>
        </table>

        <div class="fuel-section">
            <h2 class="fuel-gauge-title">قراءة الوقود (Fuel Reading)</h2>

            <div class="fuel-labels">
                <span class="fuel-label">0% <span class="fuel-checkbox" id="fuel-0">⬜</span></span>
                <span class="fuel-label">25% <span class="fuel-checkbox" id="fuel-25">⬜</span></span>
                <span class="fuel-label">50% <span class="fuel-checkbox" id="fuel-50">⬜</span></span>
                <span class="fuel-label">75% <span class="fuel-checkbox" id="fuel-75">⬜</span></span>
                <span class="fuel-label">100% <span class="fuel-checkbox" id="fuel-100">⬜</span></span>
            </div>
        </div>

        <div class="checklist-section">
            <h2 style="color: #D6B046;">قائمة الفحص (Checklist)</h2>
            <table>
                <tr>
                    <th>البند (Item)</th>
                    <th>الحالة (Status)</th>
                    <th>البند (Item)</th>
                    <th>الحالة (Status)</th>
                </tr>
                <tr>
                    <td>الرخصة (License)</td>
                    <td>[✓] موجود (Present)</td>
                    <td>المكيف (A/C)</td>
                    <td>[✓] موجود (Present)</td>
                </tr>
                <tr>
                    <td>الراديو (Radio)</td>
                    <td>[✓] موجود (Present)</td>
                    <td>CD / كاسيت (CD / Cassette)</td>
                    <td>[✗] مفقود (Missing)</td>
                </tr>
                <tr>
                    <td>المطفأة الأمامية (Front Ashtray)</td>
                    <td>[✓] موجود (Present)</td>
                    <td>المطفأة الخلفية (Back Ashtray)</td>
                    <td>[✓] موجود (Present)</td>
                </tr>
                <tr>
                    <td>المرآة اليمنى (Right Mirror)</td>
                    <td>[✓] موجودة (Present)</td>
                    <td>المرآة اليسرى (Left Mirror)</td>
                    <td>[✓] موجودة (Present)</td>
                </tr>
                <tr>
                    <td>الأضواء الأمامية (Front Lights)</td>
                    <td>[✓] موجودة (Present)</td>
                    <td>الأضواء الخلفية (Rear Lights)</td>
                    <td>[✓] موجودة (Present)</td>
                </tr>
                <tr>
                    <td>فرش الأرضية (Floor Mats)</td>
                    <td>[✓] موجود (Present)</td>
                    <td>غطاء العجلات (Wheel Caps)</td>
                    <td>[✓] موجود (Present)</td>
                </tr>
                <tr>
                    <td>الإطار الاحتياطي (Spare Tire)</td>
                    <td>[✓] موجود (Present)</td>
                    <td>الرافعة (Jack)</td>

<td>[✓] موجودة (Present)</td>
                </tr>
                <tr>
                    <td>الأدوات (Tools)</td>
                    <td>[✓] موجودة (Present)</td>
                </tr>
            </table>
        </div>

        <!-- Remarks Section -->
        <table>
            <tr>
                <th colspan="4">ملاحظات (Remarks)</th>
            </tr>
            <tr>
                <td colspan="4">
                    <br /><br /><br /><br /><br /><br /><br /><br /><br />
                </td>
            </tr>
        </table>

        <!-- Signatures -->
        <div class="sign-section">
            <p><strong>توقيع المستأجر: __ (Renter's Signature)</strong></p>
            <p><strong>توقيع الموظف: __ (Employee's Signature)</strong></p>
            <p><strong>التاريخ: __ (Date)</strong></p>
        </div>

        <!-- Note about exceeding 200 km per day -->
        <div class="note">
            <p>ملاحظة: لا تتجاوز 200 كم في اليوم، أو ستُفرض رسوم إضافية. (Note: Do not exceed 200 km per day, or extra
                charges will apply.)</p>
        </div>

        <!-- Bill of Exchange Section (Image) -->
        <div class="bill-of-exchange">
            <img src="https://tsd.fra1.cdn.digitaloceanspaces.com/Assets/WhatsApp%20Image%202024-10-09%20at%202.23.35%20AM.jpeg"
                alt="صورة الكمبيالة (Bill of Exchange Image)">
        </div>
    </div>

    <script>
        // Set the fuel level (can be 0, 25, 50, 75, or 100)
        var fuelLevel = 25;

        // Function to check the appropriate fuel level checkbox
        function checkFuelLevel(level) {
            document.getElementById('fuel-' + level).textContent = '✔️';
        }

        // Call the function with the fuel level
        checkFuelLevel(fuelLevel);
    </script>

</body>

</html>