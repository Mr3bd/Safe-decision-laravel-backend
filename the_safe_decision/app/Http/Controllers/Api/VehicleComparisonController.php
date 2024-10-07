<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class VehicleComparisonController extends Controller
{
    public function compareImages(Request $request)
    {
        // Validate the input
        $request->validate([
            'image1' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image2' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Get the language from the header (default to 'ar' if not provided)
        $language = $request->header('Accept-Language', 'ar');

        // Define text based on the language (Arabic or English)
        $compareText = $language === 'ar' 
            ? "قم بتحليل الصورة للسيارة فقط والتغيرات التي طرأت عليها بين الصورة الاولى والثانية بما في ذلك أي خدوش ملحوظة او اي تغييرات بشكل عام وبالتفصيل بالإضافة لتكلفة الأصلاح في الأردن بالدينار الأردني سواء كانت القطعة مستعملة او جديدة."
            : "Analyze the car's images and the changes between the first and second image, including any noticeable scratches or general changes. Also, detail the repair cost in Jordanian Dinars, whether the parts are used or new.";

        // Store images in the CarComparison folder in DigitalOcean Spaces
        $folder = 'CarComparison';
        $image1Path = $request->file('image1')->store($folder, 'spaces');  // Store image1 in 'CarComparison/' folder
        $image2Path = $request->file('image2')->store($folder, 'spaces');  // Store image2 in 'CarComparison/' folder

        // Generate URLs for the images
        $image1Url = Storage::disk('spaces')->url($image1Path);
        $image2Url = Storage::disk('spaces')->url($image2Path);

        // Prepare the OpenAI API request
        $client = new HttpClient();
        $apiKey = config('services.openai.api_key');;
        $url = 'https://api.openai.com/v1/chat/completions'; 
        // return response()->json(['description' => $apiKey], JSON_UNESCAPED_UNICODE);

        // Prepare the JSON request body
        $data = [
            'model' => 'gpt-4-turbo', 
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => $compareText // Use the selected language's text
                        ],
                        [
                            'type' => 'image_url',
                            'image_url' => ['url' => $image1Url]
                        ],
                        [
                            'type' => 'image_url',
                            'image_url' => ['url' => $image2Url]
                        ]
                    ]
                ]
            ],
            'max_tokens' => 1000
        ];

        // Make the API request
        try {
            $response = $client->request('POST', $url, [
                'headers' => [
                    'Authorization' => "Bearer $apiKey",
                    'Content-Type' => 'application/json',
                ],
                'json' => $data,
            ]);

            $body = json_decode($response->getBody(), true);

            // Get the description and ensure it's properly decoded
            $description = $body['choices'][0]['message']['content'] ?? 'No description provided';

            // Convert the description to UTF-8 if necessary
            if (!mb_check_encoding($description, 'UTF-8')) {
                $description = mb_convert_encoding($description, 'UTF-8');
            }

        } catch (RequestException $e) {
            // return response()->json(['error' => $image1Url], 500);

            return response()->json(['error' => 'Failed to communicate with OpenAI API: ' . $e->getMessage()], 500);
        }

        // Return the generated description with proper encoding
        return response()->json(['description' => $description], JSON_UNESCAPED_UNICODE);
    }
}
