<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clinic;

class UnsubscribeController extends Controller
{
    

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request, $key = null)
    {
        $uemail = $request['uemail']; 
        $clinic      = Clinic::select('id','reportrecipients')->where('callrail_company','=',$key)->first();
        
        $list = explode(",", $clinic['reportrecipients']);
        
        if (($key = array_search($uemail, $list)) !== false) {
            unset($list[$key]);
        }
        
        $newlist = implode(",", $list);
        echo Clinic::where('id', "=", $clinic['id'])->update(['reportrecipients' => $newlist]);
        exit;
    }
}
