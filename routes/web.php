<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\CallsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\SchemaController;
use App\Http\Controllers\UnixController;

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */
Route::group(// Comment out this when running tests
        ['prefix' => LaravelLocalization::setLocale(),
            'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']], function () {

// Registration routes//////////////////////////////////////////////////////////
           // Show the registration form
           Route::name('showRegisterForm')->get('registration', [CustomAuthController::class, 'showRegisterForm'])->middleware('guest');

           // Handle the registration request
           Route::name('handleRegistration')->post('custom-registration', [CustomAuthController::class, 'handleRegistration']);

           //Show the notice which tells the user to open the mail with a link for email verification
           Route::name('verification.notice')->get('/email/showVerifyEmail', [CustomAuthController::class, 'showVerifyEmail']);

           Route::name('verification.send')->post('/email/verification-notification', [CustomAuthController::class, 'sendEmailVerificationNotification'])
                   ->middleware(['auth', 'throttle:5,1']);

           // Handle the request for email verification sent from the email sent to the user. 
           // NOTE! Do not change the name of this route, as it is triggered by the event(new Registered($user))
           Route::name('verification.verify')->get('/email/verify/{id}/{hash}', [CustomAuthController::class, 'handleEmailVerification'])
                   ->middleware(['auth', 'signed']);

// Forgotten Password routes /////////////////////////////////////////////////////
           // Show the view with the password reset link request form:
           Route::name('showForgotPasswordForm')->get('/forgot-password', [CustomAuthController::class, 'showForgotPasswordForm'])
                   ->middleware('guest');

           // Handle the request for sending the forgotten password reset link
           Route::name('password.email')->post('/forgot-password', [CustomAuthController::class, 'sendPasswordResetLink'])
                   ->middleware('guest');

           // Display the reset password form that is displayed when the user clicks the reset password link
           Route::name('password.reset')->get('/reset-password/{token}', [CustomAuthController::class, 'showResetPasswordForm'])
                   ->middleware('guest');

           // Handle the password reset form submission
           Route::name('password.update')->post('/reset-password', [CustomAuthController::class, 'handleThePasswordResetFormSubmission'])
                   ->middleware('guest');

// Login/logout routes //////////////////////////////////////////////////////////
           // Show login form
           Route::name('showLoginForm')->get('login', [CustomAuthController::class, 'showLoginForm'])->middleware('guest');
           // Handle login request
           Route::name('login.custom')->post('custom-login', [CustomAuthController::class, 'customLogin'])->middleware(env('LOGIN_THROTTLE', 'throttle:5,1'));
           // Logout user
           Route::name('signout')->get('signout', [CustomAuthController::class, 'signOut'])->middleware('auth');

// Home and menu routes //////////////////////////////////////////////////////
           Route::name('home')->get('/', [HomeController::class, 'home']);
           Route::name('about')->get('/about', [MenuController::class, 'about']);
           Route::name('contact')->get('/contact', [MenuController::class, 'contact']);
           Route::name('contact.sendMail')->post('/contacts', [MenuController::class, 'sendMail']);
           Route::name('privacy')->get('/privacy', [MenuController::class, 'privacy']);
           Route::name('schedule.info')->get('/schedule/info', [HomeController::class, 'showInfo']);

// Calls routes ///////////////////////////////////////////////////////////////
           Route::name('calls.')->group(function () {
              //Route::name('home')->get('/callsHome', [CallsController::class, 'callsHome']);
              Route::middleware(['verified'])->group(function () {
                 // Show application welcome view  
                 Route::name('home')->get('/sdCalls/home', [CallsController::class, 'index']);
              });
           });
// Schedule routes ///////////////////////////////////////////////////////////////
           Route::name('schedule.')->group(function () {
              // Show application home/welcome view  
              Route::name('home')->get('/schemaHome', [HomeController::class, 'schemaHome']);

              Route::middleware(['verified'])->group(function () {
                 Route::controller(SchemaController::class)->group(function () {
                    // Show one schema
                    Route::name('index')->get('/schedule/show/{scheduleId}/{showHistory?}', 'index');
                    // Show welcome for users with multiple schemas
                    Route::name('welcome')->get('/welcome', 'welcome');
                    //Show edit view for one user for  attendance update
                    Route::name('showEdit')->get('/schedule/edit/{schedule}', 'showViewEdit');
                    // Show user's schedules
                    // Route::name('showMySchedules')->get('/schedule/showSchedules', 'showMySchedules');
                    Route::name('showMySchedules')->get('/admin/schedule/showSchedules', 'showMySchedules');

                    // Register or unregister for schemas
                    Route::name('register')->post('/schedule/register', 'registerForSchemas');

                    Route::name('release_2_2')->get('/schedule/release_2_2', 'showReleaseNotes_2_2');

                    // Update attendance (for one user)
                    Route::name('updateAttendance')->post('/schedule/updateAttendance', 'updateAttendance');

                    // Routes requiring superadmin authority (can create new schedules) /////////////////////////
                    Route::middleware(['isScheduleAdmin'])->group(function () {
                       Route::name('showRegisterSchedule')->get('/admin/registerNewWchedule', 'showRegisterSchedule');
                       Route::name('registerNewSchedule')->post('/admin/registerNewSchedule', 'registerNewSchedule');
                       Route::name('updateSchedule')->post('/admin/updateSchedule', 'updateSchedule');
                    });

                    // Routes requiring admin authority on schedules /////////////////////////
                    Route::middleware(['isScheduleAdmin'])->group(function () {
                       // Show view AdminComments
                       Route::name('showComments')->get('/admin/comments/{scheduleId}', 'showViewAdminComments');

                       // Show update/remove members view
                       Route::name('showMembers')->get('/admin/members/{scheduleId}', 'showViewMembers');
                       // Show update/remove members view
                       Route::name('showNotConnectedMembers')->get('/admin/notConnectedmembers/{scheduleId}', 'showViewNotConnectedMembers');
                       Route::name('showViewAdminRegisterMember')->get('/admin/registerMember/{scheduleId}', 'showViewAdminRegisterMember');

                       // Update "name in schema" or number and add member to a schedule
                       Route::name('connectMember')->post('/admin/connectMember', 'connectMember');

                       // Update admin status or remove member from a schedule
                       Route::name('updateMember')->post('/admin/updateMember', 'updateMember');
                       // Show register new user form
                       Route::name('showRegisterUser')->get('/admin/showRegisterUser/{scheduleId}', 'showRegisterUser');

                       // Update comments
                       Route::name('updateComments')->post('/admin/updateComments/{scheduleId}', 'updateComments');
                       // Show add/remove dates view
                       Route::name('showAddRemoveDates')->get('/admin/AddRemoveDates/{scheduleId}/{showHistory?}', 'showAddRemoveDates');
                       // Add dates
                       Route::name('addDates')->post('/admin/addDates/{scheduleId}', 'addDates');
                       // Remove dates
                       Route::name('removeDates')->post('/admin/removeDates', 'removeDates');
                    });
                 });
              });
           });
        });   // Comment out this when running tests
// Routes not needing localization /////////////////////////////////////////////
Route::get('/switchLocale', [HomeController::class, 'switchLocale'])->name('switchLocale');

// Routes in dev environment only ///////////////////////////////////////////////
Route::domain('schema.dev.sqd.se')->group(function () {
   Route::get('/unix/home', [UnixController::class, 'index'])->middleware('auth');
   Route::post('/unix/createConfigFile', [UnixController::class, 'createConfigFile'])->name('createConfigFile');
   Route::get('/unix/createAllConfigFile', [UnixController::class, 'createAllConfigFiles'])->name('createAllConfigFiles');
});

//  Route::get('/email/verify', function () { return view('auth.verify-email');
//})->middleware('auth')->name('verification.notice');

// Test routes,  no localization ///////////////////////////////////////////////
//Route::name('test.')->group(function () {
//   Route::name('showRegisterForm')->get('registration/{application}', [CustomAuthController::class, 'showRegisterForm'])->middleware('guest');
//});
////////////////////////////////////////////////////////////////////////////////
