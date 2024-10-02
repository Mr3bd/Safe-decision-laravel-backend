<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class VehicleComparisonController extends Controller
{
    public function compare(Request $request)
    {
        // Validate and upload images
        $request->validate([
            'image1' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'image2' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $image1Path = $request->file('image1')->store('images');
        $image2Path = $request->file('image2')->store('images');

        // Get description of differences using GPT-3
        $description = $this->describeDifferences(storage_path('app/' . $image1Path), storage_path('app/' . $image2Path));

        // Return the result to the user
        return response()->json(['description' => $description]);
    }

    private function compareImages($image1, $image2)
    {
        // Load both images
        $img1 = imagecreatefromjpeg($image1);
        $img2 = imagecreatefromjpeg($image2);
        
        // Get image dimensions
        $width = imagesx($img1);
        $height = imagesy($img1);
        
        // Compare pixel by pixel
        $diffPixels = 0;
        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $rgb1 = imagecolorat($img1, $x, $y);
                $rgb2 = imagecolorat($img2, $x, $y);
                
                if ($rgb1 !== $rgb2) {
                    $diffPixels++;
                }
            }
        }

        return $diffPixels;
    }

    private function describeDifferences($image1Path, $image2Path)
    {
        $differences = $this->compareImages($image1Path, $image2Path);

        $prompt = "You are analyzing two images of the same car. The differences detected between the two images are as follows: {$differences} differing pixels. Describe the changes to the car, including any noticeable scratches, changes in paint color, or signs of an accident.";

        $response = OpenAI::completions()->create([
            'model' => 'gpt-4',
            'prompt' => $prompt,
            'max_tokens' => 150,
        ]);

        return $response['choices'][0]['text'];
    }
}