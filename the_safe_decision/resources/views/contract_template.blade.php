<!DOCTYPE html>
<html lang="en" dir="rtl"> <!-- Set the direction to RTL -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rental Contract</title>
    <style>
        body {
            font-family: sans-serif;
            color: #000;
            text-align: right; /* Align text to the right */
        }

        .container {
            width: 100%;
            padding: 0px 20px;
            margin: auto;
            box-sizing: border-box;
        }

        .flex-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start; /* Align content at the start */
        }

        .image-container {
            width: 100%; /* Full width for the image container */
            text-align: center;
            margin-top: 10px; /* Add some margin to separate from the header */
            border: 2px solid black; /* Add a black border around the image */
            padding: 5px; /* Space between the image and the border */
        }

        img {
            max-width: 100%; /* Ensures the image does not exceed the container's width */
            height: 250px; /* Increased height for better visibility */
            object-fit: contain; /* Ensures the image fits nicely within its container */
        }

        .fuel-section {
            width: 100%; /* Full width for the fuel section */
            margin-top: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse; /* Collapse borders for better appearance */
        }

        .head {
            width: 100%;
            border: 2px solid black;
            background-color: #d6b045;
            color: white;
            font-size: 16px;
            text-align: center;
        }

        .tbody {
            color: black;
            font-size: 14px; /* Slim font size for better fitting */
            text-align: right; /* Align tbody text to the right */
        }

        td {
            font-size: 14px; /* Adjusted font size to fit A3 page */
            padding: 10px; /* Increased padding for taller cells */
            text-align: center; /* Center text within the table cells */
            height: 40px; /* Increased height for better visibility */
        }

        .fuel-table td {
            width: 20%; /* Narrower column width for the fuel table */
        }

        h4 {
            margin-top: 10px;
            font-size: 16px;
        }

        .header {
            text-align: center; /* Center the contract name and company details */
            margin-bottom: 20px;
        }

        .notes {
            margin-top: 20px;
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset($rentalContract->institution->logo_image) }}" alt="Company Logo" style="width: 120px; height: auto; margin-bottom: 4px;">
            <h3>(Rent A Car Contract) عقد تاجير سيارة</h3>
            <h3>
                <span id="name_company">{{ $rentalContract->institution->name }}</span>
            </h3>
            <h5>
                <span id="branch">{{ $rentalContract->institution->emergency_number }} </span>, <span id="Details">{{ $rentalContract->institution->address_en }}</span>
            </h5>
            <h4>رقم العقد Contract No</h4>
            <h4> #{{ $rentalContract->id }} </h4>
        </div>

        <!-- Combined Renter and Car Info Table -->
        <table class="t0" border="1">
            <tr class="head">
                <td>(Renter's Name) اسم المستاجر</td>
                <td>(Phone Number) رقم الهاتف</td>
                <td>(Whatsapp) واتساب</td>
                <td>(Address) العنوان</td>
                <td>(Type of Car ) نوع السيارة</td>
                <td>(Plate Number) رقم اللوحة</td>
                <td>(Date & Time of Rent) تاريخ ووقت الاستئجار</td>
                <td>(Date & Time of Return) تاريخ ووقت التسليم</td>
            </tr>
            <tr class="tbody">
                <td>{{ $rentalContract->tenant->first_name }} {{ $rentalContract->tenant->middle_name }} {{ $rentalContract->tenant->last_name }}</td>
                <td>{{ $rentalContract->tenant->phone_number }}</td>
                <td>{{ $rentalContract->tenant->whatsapp_number }}</td>
                <td>
                    {{ $rentalContract->tenant->city->name_ar }},{{ $rentalContract->tenant->city->name_en }}
                    <br>
                    {{ $rentalContract->tenant->region }}
                    <br>
                    {{ $rentalContract->tenant->street }}
                    <br>
                    {{ $rentalContract->tenant->building_number }}
                </td>
                <td>
                    {{ $rentalContract->car->model->manufacture->name_ar}} {{ $rentalContract->car->model->manufacture->name_en}}
                    <br>
                    {{ $rentalContract->car->model->name_en}}
                    <br>
                    {{ $rentalContract->car->manu_year}}
                </td>
                <td>{{ $rentalContract->car->tagNumber}}</td>
                <td>{{ $rentalContract->rent_date}}</td>
                <td>{{ $rentalContract->return_date}}</td>
            </tr>
        </table>

        <!-- Car Scratches Section -->
        <h4>Car Scratches (الخدوش على السيارة)</h4>
        <div class="image-container">
            <img src="{{ asset($rentalContract->scratches_image) }}" alt="Car Scratches">
        </div>

        <!-- Fuel Section -->
        <div class="fuel-section">
            <h4>الوقود (Fuel)</h4>
            <table class="t4 fuel-table" border="1">
                <tr class="head">
                    <td>0%</td>
                    <td>25%</td>
                    <td>50%</td>
                    <td>75%</td>
                    <td>100%</td>
                </tr>
                <tr class="tbody">
                    <td style="background-color: {{ $rentalContract->fuel_before_reading >= 0 ? 'red' : 'white' }};"></td> <!-- Red for 0% -->
                    <td style="background-color: {{ $rentalContract->fuel_before_reading >= 0.25 ? 'brown' : 'white' }};"></td> <!-- Brown for 25% -->
                    <td style="background-color: {{ $rentalContract->fuel_before_reading >= 0.5 ? 'orange' : 'white' }};"></td> <!-- Orange for 50% -->
                    <td style="background-color: {{ $rentalContract->fuel_before_reading >= 0.75 ? 'limegreen' : 'white' }};"></td> <!-- Lime Green for 75% -->
                    <td style="background-color: {{ $rentalContract->fuel_before_reading == 1.0 ? 'green' : 'white' }};"></td> <!-- Green for 100% -->
                </tr>
            </table>
        </div>

        <!-- Checklist Section -->
        <h4>الفحص (Checklist)</h4>
        <table border="1">
            <tr class="head">
                <td>Feature (المواصفات)</td>
                <td>Status (الحالة)</td>
                <td>Feature (المواصفات)</td>
                <td>Status (الحالة)</td>
                <td>Feature (المواصفات)</td>
                <td>Status (الحالة)</td>
            </tr>
            @foreach(array_chunk($allFeatures->toArray(), 3) as $featureSet)
                <tr class="tbody">
                    @foreach($featureSet as $feature)
                        <td>{{ $feature['name_ar'] }} ({{ $feature['name_en'] }})</td>
                        <td>{{ in_array($feature['id'], $selectedFeatures) ? 'X' : '' }}</td>
                    @endforeach
                    @for ($i = count($featureSet); $i < 3; $i++)
                        <td></td>
                        <td></td>
                    @endfor
                </tr>
            @endforeach
        </table>

        <!-- Remarks Section -->
        <h4 class="notes">ملاحظات (Remarks)</h4>
        <table border="1">
            <tr class="head">
                <td>ملاحظات</td>
            </tr>
            <tr class="tbody">
                <td>{{ $tenantReview->description ?? '' }}</td>
            </tr>
        </table>

        <div class="text">
            <h4>توقيع المتساجر:</h4>
            <h4>توقيع الموظف:</h4>
            <h4>التاريخ:</h4>
        </div>
    </div>
</body>
</html>