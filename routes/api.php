<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CandidateController;

/* |-------------------------------------------------------------------------- | API Routes |-------------------------------------------------------------------------- | | Here is where you can register API routes for your application. These | routes are loaded by the RouteServiceProvider within a group which | is assigned the "api" middleware group. Enjoy building your API! | */


# Protected routes
Route::group(['middleware' => 'auth:sanctum'], function () {
  # Routes for company
  Route::controller(CompanyController::class)->group(
    function () {
      Route::post('/logout-company', 'logout')->name('logout.company');
      Route::post('/company-post-job', 'postJob')->name('company.post.job');
      Route::get('/company-info', 'show')->name('company.info');
      Route::post('/delete-job/{id}', 'deleteJob')->name('delete.job');
      Route::post('/update-job/{id}', 'updateJob')->name('update.job');
      Route::post('/company-update-status-job', 'updateStatusJob')->name('company.update.status.job');
    }
  );
  # Routes for candidate
  Route::controller(CandidateController::class)->group(
    function () {
      Route::post('/logout-candidate', 'logout')->name('logout.candidate');
      Route::post('/delete-candidate', 'destroy')->name('delete.candidate');
      Route::get('/candidate-info', 'show')->name('candidate.info');
      Route::put('/update-candidate', 'update')->name('update.candidate');
      Route::get('/search-jobs', 'searchJobs')->name('search.jobs');
      Route::post('/apply-job', 'applyJob')->name('apply.job');
    }
  );
});

### Public routes

# Routes for candidates
Route::controller(CandidateController::class)->group(function () {
  Route::post('/register-candidate', 'store')->name('register.candidate');
  Route::post('/login-candidate', 'login')->name('login.candidate');
});

# Routes for company
Route::controller(CompanyController::class)->group(
  function () {
    Route::get('/companies', 'index')->name('companies');
    Route::post('/register-company', 'store')->name('register.company');
    Route::post('/login-company', 'login')->name('login.company');
    # Routes for jobs  
    Route::get('/jobs', 'getJobs')->name('jobs');
    Route::get('/jobs/{id}', 'getJob')->name('job');
  }
);

