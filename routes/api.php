<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\CodeController;
use App\Http\Controllers\api\CollegeController;
use App\Http\Controllers\api\ExamController;
use App\Http\Controllers\api\FavoriteController;
use App\Http\Controllers\api\NotificationController;
use App\Http\Controllers\api\QuestionController;
use App\Http\Controllers\api\SliderController;
use App\Http\Controllers\api\SpecialtyController;
use App\Http\Controllers\api\SubjectController;
use App\Http\Controllers\api\SuggestionController;
use App\Http\Controllers\api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/colleges', [CollegeController::class, 'index']);

Route::group(['prefix' => 'user'], function () {
    Route::post('/register', [AuthController::class, 'registerUser']);
    Route::post('/login', [AuthController::class, 'logInUser']);
});


Route::middleware(['code', 'auth-token'])->prefix('user')->group(function () {
    Route::get('/exams/book', [ExamController::class, 'chooseExamByBook']);
    Route::get('/logout', [AuthController::class, 'logoutUser']);
    Route::get('/exams/exam', [ExamController::class, 'chooseExamByExam']);
    Route::get('/exams/show', [ExamController::class, 'showExamByDegree']);
    Route::get('/exams/subject', [ExamController::class, 'chooseExamBySubject']);
    Route::post('/exams/calculate', [ExamController::class, 'calculateExamMark']);
    Route::post('/device/store', [NotificationController::class, 'storeDeviceToken']);
    Route::get('/profile', [UserController::class, 'showProfile']);
    Route::post('/profile/update', [UserController::class, 'updateProfile']);
    Route::post('/suggestion', [SuggestionController::class, 'addSuggestion']);
    Route::post('/upload-image', [UserController::class, 'updateProfileImage']);
    Route::get('/add-favorite', [FavoriteController::class, 'addFavorite']);
    Route::get('/favorite', [FavoriteController::class, 'showFavoriteQuestions']);
    Route::get('/show-favorite', [FavoriteController::class, 'displayFavoriteQuestion']);
    Route::get('/delete-favorite', [FavoriteController::class, 'deleteFavoriteQuestion']);
    Route::get('/notifications', [NotificationController::class, 'showNotifications']);
});

Route::middleware(['code', 'auth-token'])->prefix('college')->group(function () {
    Route::get('/subject', [SubjectController::class, 'showSubjects']);
    Route::get('/specialty', [CollegeController::class, 'showSpecialties']);
});


Route::get('/sliders', [SliderController::class, 'showSlider']);

Route::post('/admin/log-in', [AuthController::class, 'loginAdmin']);

Route::middleware(['admin'])->prefix('admin')->group(function () {
    Route::get('/log-out', [AuthController::class, 'logoutAdmin']);
    Route::post('/send-notify', [NotificationController::class, 'sendNotification']);
    Route::post('/add-slider', [SliderController::class, 'addSlider']);
    Route::get('/show-suggestions', [SuggestionController::class, 'index']);
    Route::get('/delete-suggestions', [SuggestionController::class, 'deleteSuggestion']);
    Route::get('/delete-slider', [SliderController::class, 'deleteSlider']);
    Route::get('/exams', [ExamController::class, 'showExamByCollege']);
    Route::post('/add-exam', [ExamController::class, 'addExam']);
    Route::get('/show-questions', [QuestionController::class, 'showQuestionsByExam']);
    Route::post('/add-question', [QuestionController::class, 'addQuestionAndAnswers']);
    Route::post('/update-question', [QuestionController::class, 'update']);
    Route::post('/make-account', [AuthController::class, 'registerAdmin']);
    Route::post('/generate-code', [CodeController::class, 'generateCode']);
    Route::get('/show-users', [UserController::class, 'showUsersForGeneratingCode']);
    Route::post('/add-college', [CollegeController::class, 'addCollege']);
    Route::get('/colleges', [CollegeController::class, 'indexForAdmin']);
    Route::get('/subjects', [SubjectController::class, 'index']);
    Route::post('/add-subject', [SubjectController::class, 'addSubject']);
    Route::post('/update-subject', [SubjectController::class, 'updateSubject']);
    Route::post('/add-specialty', [SpecialtyController::class, 'addSpecialty']);
    Route::post('/update-specialty', [SpecialtyController::class, 'updateSpecialty']);
    Route::get('/specialties', [SpecialtyController::class, 'showSpecialties']);
    Route::get('/sliders', [SliderController::class, 'index']);
});

Route::get('/run-migrations', function () {
    \Illuminate\Support\Facades\Artisan::call('migrate');
    return 'Migrations executed successfully!';
});


Route::get('/run-seeders', function () {
    Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);

    return 'Admin Seeder executed successfully.';
});
Route::get('/link-storage', function () {
    Artisan::call('storage:link');

    return 'linked successfully!';
});
