<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ProjectController extends Controller
{
    // Create a new project
    public function create(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'title' => 'required|string',
                'description' => 'required|string',
                'image' => 'required|file',
            ]);

            $validated['image_url'] = $validated['image'];

            // Upload the image to Cloudinary


            // Upload the image to Cloudinary
            $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath(), [
                'folder' => 'portfolio/works',

            ])->getSecurePath();



            // Create the project
            $project = Project::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'image_url' => $uploadedFileUrl,
            ]);

            return response()->json(['message' => 'Project created successfully', 'project' => $project], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation exceptions
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json([
                'message' => 'An error occurred while creating the project',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Update a project
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            // Validate the request
            $validated = $request->validate([
                'title' => 'required|string',
                'description' => 'required|string',
                'image' => 'nullable|file',
            ]);

            // Find the project
            $project = Project::findOrFail($id);

            // Update the project details
            $project->title = $validated['title'];
            $project->description = $validated['description'];

            // Update the image to Cloudinary
            if ($request->hasFile('image')) {
                // Delete the old image from Cloudinary
                if ($project->image_url) {
                    Cloudinary::destroy($project->image_url);
                }

                // Upload the new image to Cloudinary
                $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath(), [
                    'folder' => 'portfolio/works',
                    'public_id' => $project->id, // Use the project ID as the public ID
                    'overwrite' => true, // Overwrite the existing image with the same public ID
                ])->getSecurePath();

                $project->image_url = $uploadedFileUrl;
            }

            // Save the project
            $project->save();

            DB::commit();

            return response()->json(['message' => 'Project updated successfully', 'project' => $project], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to update project',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Delete a project
    public function delete($id)
    {
        DB::beginTransaction();

        try {
            // Find the project
            $project = Project::findOrFail($id);

            // Update the image to Cloudinary
            if ($project->image_url) {
                // Delete the old image from Cloudinary
                if ($project->image_url) {
                    // Extract the public ID from the image URL
                    $publicId = pathinfo(parse_url($project->image_url, PHP_URL_PATH), PATHINFO_FILENAME);

                    // Delete the image from Cloudinary
                    Cloudinary::destroy('portfolio/works/' . $publicId);

                }
            }

            // Delete the project
            $project->delete();

            DB::commit();

            return response()->json(['message' => 'Project deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to delete project',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getProjects()
    {
        $projects = Project::all();

        // Change image_url to image
        $projects->transform(function ($project) {
            $project->image = $project->image_url;
            unset($project->image_url);
            return $project;
        });

        return response()->json(['projects' => $projects], 200);
    }

}
