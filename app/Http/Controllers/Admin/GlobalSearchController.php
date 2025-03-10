<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\CrmCustomer;
use Session;

class GlobalSearchController extends Controller
{
    private $models = [
        'CrmCustomer' => 'cruds.crmCustomer.title',
        //'Clinic'      => 'cruds.clinic.title',
    ];

    public function search(Request $request)
    {
        $search = $request->input('search');

        if ($search === null || !isset($search['term'])) {
            abort(400);
        }

        $userid = Auth()->user()->id;
        $isadmin =  Auth()->user()->getIsAdminAttribute();

        $term           = $search['term'];
        $searchableData = [];
        foreach ($this->models as $model => $translation) {
            $modelClass = 'App\Models\\' . $model;
            $query      = $modelClass::query();

            $fields = $modelClass::$searchable;

            if(!$isadmin){
                
                $query = CrmCustomer::with(['clinic', 'source', 'status', 'assignees'])->whereHas('clinic.managers', function ($query ) use($userid) { 
                         $query->where('user_id', '=', $userid );
                    })->where(function($query2)use ($term){
                     $query2
                    ->orWhere('first_name','LIKE','%' . $term . '%')->orWhere('last_name','LIKE','%' . $term . '%')->orWhere('phone','LIKE','%' . $term . '%')->orWhere('email','LIKE','%' . $term . '%');
                });

                
                

            }else{
                /*foreach ($fields as $field) {
                    $query->orWhere($field, 'LIKE', '%' . $term . '%');
                }*/
                $query = CrmCustomer::with(['clinic', 'source', 'status', 'assignees'])->where(function($query2)use ($term){
                     $query2
                    ->orWhere('first_name','LIKE','%' . $term . '%')->orWhere('last_name','LIKE','%' . $term . '%')->orWhere('phone','LIKE','%' . $term . '%')->orWhere('email','LIKE','%' . $term . '%');
                });
            }  

            $selectedclinic = array();
            if(session('selectedclinic')){
                $selectedclinic = session('selectedclinic');
                $query = $query->whereIn('clinic_id',$selectedclinic);
            }

        //    $sql = $query->toSql();
//$bindings = $query->getBindings(); print_r($sql);exit;

            $results = $query->take(25)
                ->get();

            foreach ($results as $result) {
                $parsedData           = $result->only($fields);
                $parsedData['model']  = trans($translation);
                $parsedData['fields'] = $fields;
                $formattedFields      = [];
                foreach ($fields as $field) {
                    $formattedFields[$field] = Str::title(str_replace('_', ' ', $field));
                }
                $parsedData['fields_formated'] = $formattedFields;

                $parsedData['url'] = url('/admin/' . Str::plural(Str::snake($model, '-')) . '/' . $result->id . '/edit');

                $searchableData[] = $parsedData;
            }
        }

        return response()->json(['results' => $searchableData]);
    }
}
