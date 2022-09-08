<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Company;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function index()
  {
    try {
      $companies = Company::all();

      foreach ($companies as $company) {
        unset($company->created_at);
        unset($company->updated_at);
        unset($company->role);
        unset($company->email_verified_at);
      }

      $companies = $companies->paginate(10);

      return response()->json([
        'status' => 'success',
        'data' => $companies
      ], 200);
    }
    catch (\Throwable $th) {
      return response()->json([
        'status' => 'error',
        'message' => $th->getMessage()
      ], 500);
    }
  }

  /**
   * Register a new Company.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function store(Request $request)
  {
    try {
      $validated_data = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:companies',
        'password' => 'required|string|min:8|confirmed',
        'planet' => 'required|in:earth,mars',
        'street' => 'required|string|max:255',
        'city' => 'required|string|max:255',
        'zip' => 'required|string|max:255',
        'state' => 'required|string|max:255',
      ]);

      $validated_data['password'] = Hash::make($validated_data['password']);

      $company = Company::create($validated_data);

      return response()->json([
        'message' => 'Company created successfully',
        'company' => $company
      ], 201);
    }
    catch (\Throwable $e) {
      return response()->json([
        'message' => $e->getMessage(),
        'status' => 'error',
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
      $company = Auth::user();

      return response()->json([
        'message' => 'Company retrieved successfully',
        'company' => $company
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
   * Update Company Information.
   *
   * @authenticated
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function update(Request $request)
  {
    try {
      $company = Auth::user();

      $validated_data = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:companies,email,' . $company->id,
        'password' => 'required|string|min:8|confirmed',
        'planet' => 'required|in:earth,mars',
        'street' => 'required|string|max:255',
        'city' => 'required|string|max:255',
        'zip' => 'required|string|max:255',
        'state' => 'required|string|max:255',
      ]);
      if (isset($validated_data['password'])) {
        $validated_data['password'] = Hash::make($validated_data['password']);
      }
      foreach ($validated_data as $key => $value) {
        $company->$key = $value;
      }

      $company->save();

      return response()->json([
        'message' => 'Company updated successfully',
        'company' => $company
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
   * Company Updated Information from a Job
   *
   * @authenticated
   * @param Request $request
   * @param [type] $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function updateJob(Request $request, $id)
  {
    try {
      $company = Auth::user();

      $job = Job::find($id);

      if ($job->company_id != $company->id) {
        return response()->json([
          'message' => 'Unauthorized',
          'status' => 'error',
        ], 401);
      }

      $validated_data = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string|max:255',
        'searching_for' => 'required|numeric',
        'type_of_job' => 'required|in:full_time,part_time,internship',
        'modality' => 'required|in:remote,office,both',
        'planet' => 'required|in:earth,mars',
        'city' => 'required|string|max:255',
      ]);

      foreach ($validated_data as $key => $value) {
        $job->$key = $value;
      }

      $job->save();

      return response()->json([
        'message' => 'Job updated successfully',
        'job' => $job
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
   * Remove the company from storage.
   *
   * @authenticated
   * @return \Illuminate\Http\JsonResponse
   */
  public function destroy()
  {
    try {
      $company = Auth::user();

      $company->delete();

      return response()->json([
        'message' => 'Company deleted successfully',
      ], 204);
    }
    catch (\Throwable $e) {
      return response()->json([
        'message' => $e->getMessage(),
        'status' => 'error',
      ], 500);
    }
  }


  /**
   * Login a company
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */

  public function login(Request $request)
  {
    try {
      $validated_data = $request->validate([
        'email' => 'required|string|email|max:255',
        'password' => 'required|string|min:8',
      ]);

      $company = Company::where('email', $validated_data['email'])->first();

      if (!$company || !Hash::check($validated_data['password'], $company->password)) {
        return response()->json([
          'message' => 'Invalid credentials',
          'status' => 'error',
        ], 401);
      }

      $token = $company->createToken('auth_token')->plainTextToken;

      return response()->json([
        'message' => 'Company logged in successfully',
        'token' => $token,
        'company' => $company
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
   * Logout a company
   *
   * @authenticated
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */

  public function logout(Request $request)
  {
    try {
      $request->user()->currentAccessToken()->delete();

      return response()->json([
        'message' => 'Company logged out successfully',
        'status' => 'success',
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
   * Post a job
   * only companies can post jobs
   *
   * @authenticated
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */

  public function postJob(Request $request)
  {
    try {

      $user = $request->user();
      if ($user->role != 'company') {
        return response()->json([
          'message' => 'Only companies can post jobs',
          'status' => 'error',
        ], 401);
      }


      $validated_data = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string|max:255',
        'searching_for' => 'required|string|max:255',
        'type_of_job' => 'required|in:full_time,part_time,internship',
        'planet' => 'required|in:earth,mars',
        'city' => 'required|string|max:255',
        'modality' => 'required|in:remote,office,both',
        'company_id' => 'required|exists:companies,id',
      ]);

      $job = Job::create($validated_data);

      return response()->json([
        'message' => 'Job posted successfully',
        'job' => $job
      ], 201);
    }
    catch (\Throwable $e) {
      return response()->json([
        'message' => $e->getMessage(),
        'status' => 'error',
      ], 500);
    }
  }

  /**
   * Display a listing of all jobs posted by the company.
   * and unset the company_id, created_at and updated_at
   *
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function getJobs()
  {
    try {
      $jobs = Job::all();

      foreach ($jobs as $job) {
        $job->company_name = Company::find($job->company_id)->name;
        unset($job->company_id);
        unset($job->created_at);
        unset($job->updated_at);
      }

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
   * Show a job posted by the company.
   *
   * @param [type] $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function getJob($id)
  {
    try {
      $job = Job::find($id);

      if (!$job) {
        return response()->json([
          'message' => 'Job not found',
          'status' => 'error',
        ], 404);
      }

      $job->company_name = Company::find($job->company_id)->name;
      unset($job->company_id);
      unset($job->created_at);
      unset($job->updated_at);

      return response()->json([
        'message' => 'Job retrieved successfully',
        'job' => $job
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
   * Delete a job
   *
   * @authenticated
   * @param Request $request
   * @param [type] $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function deleteJob(Request $request, $id)
  {
    try {
      $job = Job::find($id);
      if ($job->company_id != $request->user()->id) {
        return response()->json([
          'message' => 'You can only delete jobs you posted',
          'status' => 'error',
        ], 401);
      }

      $job->delete();

      return response()->json([
        'message' => 'Job deleted successfully',
        'status' => 'success',
      ], 204);
    }
    catch (\Throwable $e) {
      return response()->json([
        'message' => $e->getMessage(),
        'status' => 'error',
      ], 500);
    }
  }

  /**
   * Company updates status of an application
   *
   * @authenticated
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function updateStatusJob(Request $request)
  {
    try {
      $validated_data = $request->validate([
        'application_id' => 'required|exists:applications,id',
        'job_id' => 'required|exists:jobs,id',
        'status' => 'required|in:pending,accepted,rejected'
      ]);

      $job = Job::find($validated_data['job_id']);
      if ($job->company_id != $request->user()->id) {
        return response()->json([
          'message' => 'You can only update the status of applications to jobs you posted',
          'status' => 'error',
        ], 401);
      }

      Application::where('id', $validated_data['application_id'])->update(['status' => $validated_data['status']]);

      return response()->json([
        'message' => 'Status updated successfully',
        'status' => 'success',
        'new_job_status' => $validated_data['status']
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