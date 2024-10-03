<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use OpenAI\OpenAI;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;

class VehicleComparisonController extends Controller
{
 public function compareImages(Request $request)
{
    // Validate the input
    $request->validate([
        'image1' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'image2' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Store images temporarily
    $image1Path = $request->file('image1')->store('temp_images');
    $image2Path = $request->file('image2')->store('temp_images');

    // Make sure to get a public URL for the images
    $image1Url = Storage::url($image1Path); // Adjust according to your storage configuration
    $image2Url = Storage::url($image2Path); // Adjust according to your storage configuration

    // Prepare the OpenAI API request
    $client = new HttpClient();
    $apiKey = env('OPENAI_API_KEY');
    $url = 'https://api.openai.com/v1/chat/completions'; // Use the correct endpoint

    // Prepare the JSON request body
    $data = [
        'model' => 'gpt-4-turbo', // Use your correct model name
        'messages' => [
            [
                'role' => 'user',
                'content' => [
                    [
                        'type' => 'text',
                        'text' => "قم بتحليل الصورة للسيارة فقط والتغيرات التي طرأت عليها بين الصورة الاولى والثانية بما في ذلك أي خدوش ملحوظة او اي تغييرات بشكل عام وبالتفصيل بالإضافة لتكلفة الأصلاح في الأردن بالدينار الأردني سواء كانت القطعة مستعملة او جديدة."
                    ],
                    [
                        'type' => 'image_url',
                        'image_url' => [
                            'url' => 'https://i.imgur.com/SAhDP6B.jpeg'
                        ]
                    ],
                    [
                        'type' => 'image_url',
                        'image_url' => [
                            'url' => 'https://i.imgur.com/gCLhmzY.jpeg'
                        ]
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
        return response()->json(['error' => 'Failed to communicate with OpenAI API: ' . $e->getMessage()], 500);
    }

    // Clean up temporary files
    Storage::delete($image1Path);
    Storage::delete($image2Path);

    // Return the generated description with proper encoding
    return response()->json(['description' => $description], JSON_UNESCAPED_UNICODE);
}
}