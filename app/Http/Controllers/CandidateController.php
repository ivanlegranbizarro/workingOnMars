<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Company;
use App\Models\Candidate;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CandidateController extends Controller
{

  /**
   * Register a new Candidate.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function store(Request $request)
  {
    try {
      $validated_data = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:candidates',
        'password' => 'required|string|min:8|confirmed',
        'phone' => 'required|string|max:12',
        'planet' => 'required|in:earth,mars',
        'street' => 'required|string|max:255',
        'city' => 'required|string|max:255',
        'state' => 'required|string|max:255',
        'zip' => 'required|string|max:255',
        'subscribed' => 'required|boolean',
      ]);


      $validated_data['password'] = Hash::make($validated_data['password']);

      $candidate = Candidate::create($validated_data);


      return response()->json([
        'message' => 'Candidate created successfully',
        'candidate' => $candidate
      ], 201);
    }
    catch (\Throwable $e) {
      return response()->json([
        'message' => 'Something went wrong',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Display the specified resource.
   *
   * @authenticated
   * @return \Illuminate\Http\JsonResponse
   */
  public function show()
  {
    try {
      $candidate = Auth::user();

      return response()->json([
        'message' => 'Candidate retrieved successfully',
        'candidate' => $candidate
      ], 200);
    }
    catch (\Throwable $e) {
      return response()->json([
        'message' => 'Something went wrong',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Update the specified resource in storage.
   *
   * @authenticated
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function update(Request $request)
  {
    try {
      $candidate = Auth::user();

      $validated_data = $request->validate([
        'name' => 'string|max:255',
        'email' => 'email|unique:candidates',
        'password' => 'string|min:8|confirmed',
        'phone' => 'string|max:12',
        'planet' => 'in:earth,mars',
        'street' => 'string|max:255',
        'city' => 'string|max:255',
        'state' => 'string|max:255',
        'zip' => 'string|max:255',
        'subscribed' => 'boolean',
      ]);

      if (isset($validated_data['password'])) {
        $validated_data['password'] = Hash::make($validated_data['password']);
      }

      foreach ($validated_data as $key => $value) {
        $candidate->$key = $value;
      }

      $candidate->save();

      return response()->json([
        'message' => 'Candidate updated successfully',
        'candidate' => $candidate
      ], 200);
    }
    catch (\Throwable $e) {
      return response()->json([
        'message' => 'Something went wrong',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @authenticated
   * @return \Illuminate\Http\JsonResponse
   */
  public function destroy()
  {
    try {
      $candidate = Auth::user();

      if ($candidate->id !== Auth::user()->id) {
        return response()->json([
          'message' => 'You are not authorized to delete this candidate',
        ], 403);
      }

      $candidate->delete();

      return response()->json([
        'message' => 'Candidate deleted successfully',
      ], 204);
    }
    catch (\Throwable $e) {
      return response()->json([
        'message' => 'Something went wrong',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Login a candidate
   *
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function login(Request $request)
  {
    try {
      $validated_data = $request->validate([
        'email' => 'required|email',
        'password' => 'required|string|min:8',
      ]);

      $candidate = Candidate::where('email', $validated_data['email'])->first();

      if (!$candidate || !Hash::check($validated_data['password'], $candidate->password)) {
        return response()->json([
          'message' => 'Invalid credentials',
        ], 401);
      }

      $token = $candidate->createToken('auth_token')->plainTextToken;

      return response()->json([
        'message' => 'Candidate logged in successfully',
        'token' => $token,
        'candidate' => $candidate,
      ], 200);
    }
    catch (\Throwable $e) {
      return response()->json([
        'message' => 'Something went wrong',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Logout a candidate
   *
   * @authenticated
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function logout(Request $request)
  {
    try {
      $request->user()->currentAccessToken()->delete();

      return response()->json([
        'message' => 'Candidate logged out successfully',
      ], 200);
    }
    catch (\Throwable $e) {
      return response()->json([
        'message' => 'Something went wrong',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Search job by title, description, search_for, city or planet
   * through body request
   *
   * @authenticated
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function searchJobs(Request $request)
  {
    try {
      $search = $request->validate([
        'search' => 'required|string|max:255',
      ]);
      $search = $search['search'];

      $jobs = Job::where('title', 'like', '%' . $search . '%')
        ->orWhere('description', 'like', '%' . $search . '%')
        ->orWhere('searching_for', 'like', '%' . $search . '%')
        ->orWhere('planet', 'like', '%' . $search . '%')
        ->orWhere('city', 'like', '%' . $search . '%')
        ->get();

      foreach ($jobs as $job) {
        $job->company_name = Company::find($job->company_id)->name;
        unset($job->company_id);
        unset($job->created_at);
        unset($job->updated_at);
      }

      $jobs = $jobs->paginate(10);

      return response()->json([
        'message' => 'Jobs retrieved successfully',
        'jobs' => $jobs
      ], 200);
    }
    catch (\Throwable $e) {
      return response()->json([
        'message' => $e->getMessage(),
        'status' => 'error',
      ], 500);
    }
  }

  /**
   * Apply for a job
   *
   * @authenticated
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function applyJob(Request $request)
  {
    try {
      $validated_data = $request->validate([
        'job_id' => 'required|integer',
      ]);

      $candidate = Auth::user();

      $job = Job::find($validated_data['job_id']);

      $application = Application::create([
        'candidate_id' => $candidate->id,
        'job_id' => $job->id,
      ]);

      return response()->json([
        'message' => 'Job applied successfully',
        'application' => $application,
      ], 200);
    }
    catch (\Throwable $e) {
      return response()->json([
        'message' => $e->getMessage(),
        'status' => 'error',
      ], 500);
    }
  }
}
