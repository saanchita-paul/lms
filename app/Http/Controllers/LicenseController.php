<?php 
namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Clinic;
use Illuminate\Http\Request;

class LicenseController extends Controller
{
    public function validateKey(Request $request)
    {
        $licenseKey = $request->query('license_key');
        $clinicId = $request->query('clinic_id');

        $clinic = Clinic::where('license_key', $licenseKey)
                        ->where('id', $clinicId)
                        ->first();

        if ($clinic) {
            return response()->json(['valid' => true, 'message' => 'License key is valid.']);
        } else {
            return response()->json(['valid' => false, 'message' => 'Invalid license key.'], 401);
        }
    }
}

?>