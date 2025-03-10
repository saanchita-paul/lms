<?php

namespace App\Http\Controllers\Api\V1\CRTX;

use App\Http\Controllers\Controller;
use App\Models\CrmNote;
use App\Traits\ExceptionLog;
use App\Models\ErrorLog;
use Illuminate\Http\Request;
use Validator;

class NotesController extends Controller
{
    use ExceptionLog;

    public function addNotes(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'note'      => 'required',
            'user_id'      => 'required|numeric',
            'customer_id'      => 'required|numeric',
        ]);

        if ($validate->fails()) {
            $error = $this->errorMessages($validate);
            return response()->json(['success' => false, 'message' => 'Something went wrong', 'errors' => $error]);
        }

        $addNotes = new CrmNote();
        $addNotes->note  = $request->note;
        $addNotes->user_id = $request->user_id;
        $addNotes->customer_id = $request->customer_id;
        $addNotes->save();

        return response()->json([
            'success' => true,
            'message' => 'Note added successfully!',
            'data' => ['data' => $addNotes]
        ], 200);
    }

    public function updateNotes(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'note'      => 'required',
            'user_id'      => 'required|numeric',
            'customer_id'      => 'required|numeric',
        ]);

        if ($validate->fails()) {
            $error = $this->errorMessages($validate);
            return response()->json(['success' => false, 'message' => 'Something went wrong', 'errors' => $error]);
        }

        $Id = $request->id;
        $updateNotes = CrmNote::find($Id);
        $updateNotes->note  = $request->note;
        $updateNotes->user_id = $request->user_id;
        $updateNotes->customer_id = $request->customer_id;
        $updateNotes->save();

        return response()->json([
            'success' => true,
            'message' => 'Note updated successfully!',
            'data' => ['data' => $updateNotes]
        ], 200);
    }

    public function deleteNotes(Request $request)
    {
        $Id = $request->id;

        $crmNotes = CrmNote::find($Id);

        if (!$crmNotes) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        $crmNotes->delete();

        return response()->json([
            'success' => true,
            'message' => 'Note deleted successfully!',
        ], 200);
    }
}
