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

        .tables {
            width: 60%; /* Adjust width for the tables */
        }

        .image-container {
            width: 40%; /* Adjust width for the image container */
            text-align: left; /* Align the image to the left */
            margin-top: 10px; /* Add some margin to separate from the header */
        }

        img {
            width: 500px; /* Specific width for the image */
            height: 300px; /* Specific height for the image */
            object-fit: cover; /* Ensure the image fits within its container */
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
            font-size: 20px;
            text-align: right; /* Align tbody text to the right */
        }

        td {
            font-size: 14px; /* Adjusted font size to fit A4 page */
            padding: 4px; /* Add padding for better spacing */
            text-align: center; /* Center text within the table cells */
        }

        h4 {
            margin-top: 10px;
            font-size: 16px;
        }

        .header {
            text-align: center; /* Center the contract name and company details */
            margin-bottom: 20px;
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

        <div class="flex-container">
            <!-- Tables Section -->
            <div class="tables">
                <table class="t0" border="1">
                    <tr class="head">
                        <td>(Renter's Name) اسم المستاجر</td>
                        <td>(Phone Number) رقم الهاتف</td>
                        <td>(Whatsapp) واتساب</td>
                        <td>(Address) العنوان</td>
                    </tr>
                    <tr class="tbody">
                        <td>{{ $rentalContract->tenant->first_name }} {{ $rentalContract->tenant->middle_name }} {{ $rentalContract->tenant->last_name }}</td>
                        <td>{{ $rentalContract->tenant->phone_number }}</td>
                        <td>{{ $rentalContract->tenant->whatsapp_number }}</td>
                        <td>{{ $rentalContract->tenant->city->name_ar }},{{ $rentalContract->tenant->city->name_en }}
                            <br>
                            {{ $rentalContract->tenant->region }}
                            <br>
                            {{ $rentalContract->tenant->street }}
                            <br>
                            {{ $rentalContract->tenant->building_number }}
                        </td>
                    </tr>
                </table>
                
                <table class="t1" border="1">
                    <tr class="head">
                        <td>(Type of Car ) نوع السيارة</td>
                        <td>(Plate Number) رقم اللوحة</td>
                        <td>(Date & Time of Rent) تاريخ ووقت الاستئجار</td>
                        <td>(Date & Time of Return) تاريخ ووقت التسليم</td>
                    </tr>
                    <tr class="tbody">
                        <td>
                            {{ $rentalContract->car->model->manufacture->name_ar}} {{ $rentalContract->car->model->manufacture->name_en}}
                            <br>
                            {{ $rentalContract->car->model->name_ar}} {{ $rentalContract->car->model->name_en}}
                            <br>
                            {{ $rentalContract->car->manu_year}}
                        </td>
                        <td>{{ $rentalContract->car->tagNumber}}</td>
                        <td>{{ $rentalContract->rent_date}}</td>
                        <td>{{ $rentalContract->return_date}}</td>
                    </tr>
                </table>

                <h4>الوقود (Fuel)</h4>
                <table class="t4" border="1">
                    <tr class="head">
                        <td>0%</td>
                        <td>25%</td>
                        <td>50%</td>
                        <td>75%</td>
                        <td>100%</td>
                    </tr>
                    <tr class="tbody">
                        <td>{{ $rentalContract->fuel_before_reading == 0 ? '✓' : '' }}</td>
                        <td>{{ $rentalContract->fuel_before_reading == 0.25 ? '✓' : '' }}</td>
                        <td>{{ $rentalContract->fuel_before_reading == 0.5 ? '✓' : '' }}</td>
                        <td>{{ $rentalContract->fuel_before_reading == 0.75 ? '✓' : '' }}</td>
                        <td>{{ $rentalContract->fuel_before_reading == 1 ? '✓' : '' }}</td>
                    </tr>
                </table>
            </div>

            <!-- Image Section -->
            <div class="image-container">
                <img src="https://tsd.fra1.digitaloceanspaces.com/Assets/car_scratch.png" alt="Driver or Car Image">
            </div>
        </div>

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

        <h4>ملاحظات (Remarks)</h4>
        <div class="note">
            <div class="n-type"></div>
        </div>

        <div class="text">
            <h4>توقيع المتساجر:</h4>
            <h4>توقيع الموظف:</h4>
            <h4>التاريخ:</h4>
        </div>
    </div>
</body>
</html>