<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Skill;
use Illuminate\Support\Facades\DB;

class SkillController extends Controller
{
    public function skills(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            '*.id' => 'integer|nullable',
            '*.name' => 'required|string',
            '*.type' => 'required|string'
        ]);


        // Get the skills from the request
        $skills = $validated;

        try {

            $records = Skill::upsert($skills, ['id'], ['name', 'type']);

            // Delete the skills if not in the request
            $ids = array_column($skills, 'id');
            Skill::whereNotIn('id', $ids)->delete();


            return response()->json(['message' => 'Skills created, updated, or deleted successfully', 'skills' => $skills], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to process skills', 'error' => $e->getMessage()], 500);
        }
    }

    public function getSkills()
    {
        $skills = Skill::all();
        return response()->json(['skills' => $skills], 200);
    }
}
