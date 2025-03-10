<?php

namespace App\Http\Controllers\Api\V1\CRTX;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\TagLeadMapping;
use DB;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::where('clinic_id', $clinicId)->get();
        return response()->json(['tags' => $tags]);
    }
    public function store(Request $request)
    {       
        // Validate the request data
        $validatedData = $request->validate([
            'tagName.*' => 'required|string|max:255',
            'clinic_id' => 'required|exists:clinics,id',
            'lead_id' => 'required|exists:crm_customers,id',
        ]);

        // Check if tagName key exists in the validated data
        if (!isset($validatedData['tagName'])) {
            return response()->json(['success' => false, 'message' => 'tagName field is missing.']);
        }

        // Initialize a flag to track if any new tags were saved
        $tagCount = 0;
        $tagsSaved = false; // Initialize the tagsSaved flag

        foreach ($validatedData['tagName'] as $tagName) {
            // Check if tag already exists
            $tag = Tag::where('tagName', $tagName)
                ->where('clinic_id', $validatedData['clinic_id'])
                ->first();

            if (!$tag) {
                // If tag doesn't exist, create it
                $tag = Tag::create([
                    'tagName' => $tagName,
                    'clinic_id' => $validatedData['clinic_id']
                ]);
                $tagsSaved = true; // Set the flag to true as new tags are saved
                $tagCount++; // Increment the tag count
            }
            // Check if tag is already mapped to this lead
            $existingMapping = TagLeadMapping::where('tag_id', $tag->id)
                ->where('lead_id', $validatedData['lead_id'])
                ->exists();

            if (!$existingMapping) {
                // If mapping doesn't exist, create it
                TagLeadMapping::create([
                    'tag_id' => $tag->id,
                    'lead_id' => $validatedData['lead_id']
                ]);
                // If mapping doesn't exist, the tags are assigned to the lead
                $message = 'Tags assigned to lead successfully.';
            }
        }
        // If no new tags were saved and no mapping was created, it means tags already exist and are mapped to the lead
        if (!$tagsSaved && !isset($message)) {
            $message = 'Tags already exist';
        }
        // Optionally, you can also include the count of newly saved tags in the message
        if ($tagsSaved) {
            if ($tagCount === 1) {
                $message .= 'New tag saved successfully.';
            } else {
                $message .= "New tags saved successfully.";
            }
        }
        // Return the response message
        return response()->json(['success' => true, 'message' => $message]);        
    }

    public function update(Request $request, $id)
    {
        $tag = Tag::findOrFail($id);
        $tag->tagName = $request->input('tagName');
        $tag->save();

        return response()->json(['success' => true,'message' => 'Tag updated successfully', 'tag' => $tag]);
    }

    public function softDelete($tagId, $leadId)
    {
        // $tag = Tag::findOrFail($id);
        // $tag->delete();
        // return response()->json(['success' => true,'message' => 'Tag soft deleted successfully']);
       
        $tagLeadsMapping = TagLeadMapping::where('tag_id', $tagId)
                                          ->where('lead_id', $leadId)
                                          ->first();


        // Check if the tag_leads_mapping entry exists
        if ($tagLeadsMapping) {
            // Soft delete the entry
            $tagLeadsMapping->delete();

            // Return a success response
            return response()->json(['success' => true, 'message' => 'Tag Leads Mapping deleted successfully']);
        } else {
            // Return an error response if the entry does not exist
            return response()->json(['success' => false, 'message' => 'Tag Leads Mapping not found'], 404);
        }
    }

    public function getTagsByClinicId(Request $request)
    {
        // // Validate the request
        // $request->validate([
        //     'clinic_id' => 'required|exists:clinics,id',
        // ]);

        // // Fetch tags based on clinic_id
        // $tags = Tag::where('clinic_id', $request->clinic_id)->get();

        // // Return the fetched tags
        // return response()->json(['success' => true, 'tags' => $tags]);

       
        // Validate the request
        $request->validate([
            'clinic_id' => 'required|exists:clinics,id',
           // 'lead_id' => 'required|exists:crm_customers,id', // Assuming lead_id is passed in the request
        ]);

        // Fetch tags from the Tags table based on clinic_id
        
        $tags = Tag::where('clinic_id', $request->clinic_id)
        ->whereNull('tags.deleted_at') // Specify the table alias for deleted_at
        ->get();

        // Fetch tags associated with the specified lead_id from the tag_leads_mapping table
        $leadTags = Tag::whereHas('leads', function ($query) use ($request) {
            $query->where('lead_id', $request->lead_id)
                ->whereNull('tag_leads_mapping.deleted_at'); // Specify the table alias for deleted_at
        })
        ->whereNull('tags.deleted_at') // Specify the table alias for deleted_at
        ->get();
        
        // Return the fetched tags
        return response()->json([
            'success' => true,
            'clinic_tags' => $tags,
            'tags' => $leadTags,
        ]);
    }

    public function fetchAutocompleteSuggestions(Request $request)
    {
        $clinicId = $request->input('clinic_id');
        $searchQuery = $request->input('search_query');
        
        $tags = Tag::where('clinic_id', $clinicId)
            ->where(function ($query) use ($searchQuery) {
                $query->where('tagName', 'LIKE', '%' . $searchQuery . '%');
            })
            ->whereNull('deleted_at')
            ->pluck('tagName');
        
        $tagNames = $tags->toArray();

        if (empty($tagNames)) {
            return response()->json(['success' => true, 'tags' => []]);
        }

        return response()->json(['success' => true,'tags' => $tagNames]);
    }
}
