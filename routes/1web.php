<?php

use App\Http\Controllers\CrosstabReportController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RealEstateController;
use App\Http\Controllers\RespondentController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\SurveyController;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

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


Route::get('crosstab/test', [CrosstabReportController::class, 'fetchIndividualReportData']);

Route::get('participants-guide', [SurveyController::class, 'participantGuide'])->name('guides.participants');


Route::get('survey/{survey}/length', [SurveyController::class, 'surveyLength']);

// survey frontend routes
Route::get('/survey/landing', [RespondentController::class, 'surveyLanding'])->name('survey.start');
Route::get('/survey/questionnaire/{vue?}', [RespondentController::class, 'surveyQuestionnaire'])->where('vue', '.*')->name('survey.questionnaire');
Route::get('/survey/error', [RespondentController::class, 'surveyError'])->name('survey.error');

// survey ajax routes
Route::get('questionnaire/branches', [SurveyController::class, 'getSurveyBranches']);
Route::post('questionnaire/save/answers', [SurveyController::class, 'saveQuestions']);
Route::post('questionnaire/save/locations', [SurveyController::class, 'saveLocations']);
Route::post('questionnaire/reset/questions', [SurveyController::class, 'resetQuestion']);
Route::post('questionnaire/reset/locations', [SurveyController::class, 'resetLocations']);
Route::post('questionnaire/reset/legal', [SurveyController::class, 'deleteLegalAnswers']);
Route::get('questionnaire/summary', [SurveyController::class, 'printSurvey']);
Route::get('questionnaire/{question}', [SurveyController::class, 'getQuestionData']);
Route::post('questionnaire/savesurveyprocess', [SurveyController::class, 'setSurveyProgress']);

Route::get('logout', function () {
    auth()->logout();
    Session()->flush();

    return Redirect::to('/');
})->name('logout');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('welcome');
})->name('login');

Route::get('/userguide', function () {
    return view('userguide');
})->name('user-guide');

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
        ? back()->with(['status' => __($status)])
        : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');

Route::get('/reset-password/{token}/{email}', function ($token, $email) {
    return view('auth.reset-password', ['token' => $token, 'email' => $email]);
})->middleware('guest')->name('password.reset');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('status', __($status))
        : back()->withErrors(['email' => [__($status)]]);
})->middleware('guest')->name('password.update');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('projects', [App\Http\Controllers\SurveyController::class, 'dashboard'])->name('projects');
    Route::get('survey/{id}', [App\Http\Controllers\SurveyController::class, 'survey_index'])->name('survey');
    Route::post('survey-toggle-active', [App\Http\Controllers\SurveyController::class, 'toggle_active'])->name('survey.toggle-active');


    // Routes for Reports
    Route::get('reports/general/{survey_id}/individual-report', [\App\Http\Controllers\ReportController::class, 'individual_report'])->name('individual-report');
    Route::get('reports/general/{survey_id}/compilation-report', [\App\Http\Controllers\ReportController::class, 'compilation_report'])->name('compilation-report');
    Route::post('/exportCompilationExcelData', [\App\Http\Controllers\ReportController::class, 'exportCompilationExcelData'])->name('exportCompilationExcelData');
    Route::get('reports/general/{survey}/individual-crosstabreport', [\App\Http\Controllers\CrosstabReportController::class, 'individual_report'])->name('individual-crosstabreport');
    Route::post('reports/crosstab/excel_export', [\App\Http\Controllers\CrosstabReportController::class, 'exportExcel'])->name('individual-crosstabreport.export-excel');
    Route::get('reports/general/{survey_id}/demographic-report', [\App\Http\Controllers\ReportController::class, 'demographic_report'])->name('demographic_report');
    Route::get('reports/{survey_id}/validation-report', [\App\Http\Controllers\ReportController::class, 'validation_report'])->name('validation-report');
    Route::get('reports/general/{survey_id}/comparative-at-a-glance', [App\Http\Controllers\ReportController::class, 'comparative_at_a_glance'])->name('comparative-at-a-glance');

    // Routes for NC Reports
    Route::get('reports/nc/{survey_id}/individual-ncreport', [\App\Http\Controllers\NCReportController::class, 'individual_report'])->name('individual-ncreport');
    Route::get('reports/nc/{survey_id}/compilation-ncreport', [\App\Http\Controllers\NCReportController::class, 'compilation_report'])->name('compilation-ncreport');
    Route::post('/exportNCCompilationExcelData', [\App\Http\Controllers\NCReportController::class, 'exportNCCompilationExcelData'])->name('exportNCCompilationExcelData');
    Route::get('reports/nc/{survey_id}/demographic-report', [\App\Http\Controllers\NCReportController::class, 'demographic_report'])->name('nc_demographic_report');

    // Routes for Taxonomy
    Route::get('taxonomy/{survey_id}', [\App\Http\Controllers\TaxonomyController::class, 'index'])->name('taxonomy.index');
    Route::post('export-taxonomy', [\App\Http\Controllers\TaxonomyController::class, 'export_excel'])->name('export-taxonomy');
    // Route::get('export-taxonomy/{survey_id}/{data_show}', [\App\Http\Controllers\TaxonomyController::class, 'export_excel'])->name('taxonomy.exportExcel');
    Route::post('createTaxonomy', [\App\Http\Controllers\TaxonomyController::class, 'create'])->name('createTaxonomy');
    Route::post('updateTaxonomy', [\App\Http\Controllers\TaxonomyController::class, 'update'])->name('updateTaxonomy');
    Route::post('deleteTaxonomy', [\App\Http\Controllers\TaxonomyController::class, 'delete'])->name('deleteTaxonomy');
    Route::post('checkTaxonomyPage', [\App\Http\Controllers\TaxonomyController::class, 'checkTaxonomyPage'])->name('checkTaxonomyPage');
    Route::post('createPageForTaxonomy', [\App\Http\Controllers\TaxonomyController::class, 'createPageForTaxonomy'])->name('createPageForTaxonomy');

    // Routes for Analysis
    Route::get('analysis/{survey_id}/participant', [\App\Http\Controllers\AnalysisController::class, 'participant_analysis'])->name('participant-analysis');
    Route::post('/exportParticipantAnalysisExcel', [\App\Http\Controllers\AnalysisController::class, 'exportParticipantAnalysisExcel'])->name('exportParticipantAnalysisExcel');
    Route::get('analysis/{survey_id}/ataglance', [\App\Http\Controllers\AnalysisController::class, 'ataglance_analysis'])->name('ataglance-analysis');
    Route::post('getAtAGlanceTableData', [\App\Http\Controllers\AnalysisController::class, 'getAtAGlanceTableData'])->name('getAtAGlanceTableData');
    Route::post('getAtAGlanceExcelExport', [\App\Http\Controllers\AnalysisController::class, 'getAtAGlanceExcelExport'])->name('getAtAGlanceExcelExport');
    Route::get('analysis/{survey_id}/comparative-glance', [\App\Http\Controllers\AnalysisController::class, 'comparative_glance_analysis'])->name('comparative-glance-analysis');
    Route::post('getComparativeGlanceTableData', [\App\Http\Controllers\AnalysisController::class, 'getComparativeGlanceTableData'])->name('getComparativeGlanceTableData');
    Route::post('getComparativeGlanceExcelExport', [\App\Http\Controllers\AnalysisController::class, 'getComparativeGlanceExcelExport'])->name('getComparativeGlanceExcelExport');

    // Routes for Invitations
    Route::get('invitations/{survey_id}/settings', [\App\Http\Controllers\InvitationsController::class, 'invitations_settings'])->name('invitations-settings');
    Route::get('invitations/{survey_id}/send', [\App\Http\Controllers\InvitationsController::class, 'invitations_send'])->name('invitations-send');
    Route::post('invitations/update_settings', [\App\Http\Controllers\InvitationsController::class, 'update_invitationsettings'])->name('invitations/update_settings');
    Route::post('invitations/get_participants_num', [\App\Http\Controllers\InvitationsController::class, 'get_participants_num'])->name('invitations/get_participants_num');
    Route::post('invitations/send_emails', [\App\Http\Controllers\InvitationsController::class, 'send_emails'])->name('invitations/send_emails');

    // Routes for Settings
    Route::get('settings/{survey_id}/settings', [\App\Http\Controllers\SettingsController::class, 'settings_settings'])->name('settings-settings');
    Route::post('settings/update_settings', [\App\Http\Controllers\SettingsController::class, 'update_settings'])->name('settings/update_settings');
    Route::get('settings/{survey_id}/locations', [\App\Http\Controllers\SettingsController::class, 'settings_locations'])->name('settings-locations');
    Route::post('settings/add_location', [\App\Http\Controllers\SettingsController::class, 'add_location'])->name('settings/add_location');
    Route::post('settings/update_location', [\App\Http\Controllers\SettingsController::class, 'update_location'])->name('settings/update_location');
    Route::post('settings/delete_location', [\App\Http\Controllers\SettingsController::class, 'delete_location'])->name('settings/delete_location');

    // Routes for Real Estate
    Route::get('realestate/{survey_id}/individual-proximity', [\App\Http\Controllers\RealEstateController::class, 'individual_proximity'])->name('real-estate.individual-proximity');
    Route::post('/realestate-getRespData', [\App\Http\Controllers\RealEstateController::class, 'getRespondentData'])->name('realestate-getRespData');
    Route::post('/realestate-getRespList', [\App\Http\Controllers\RealEstateController::class, 'getRespondentList'])->name('realestate-getRespList');
    Route::get('realestate/{survey_id}/location-rsf-rates', [\App\Http\Controllers\RealEstateController::class, 'location_rsf_rates'])->name('real-estate.location_rsf_rates');
    Route::post('realestate/create_location', [RealEstateController::class, 'create_location'])->name('real-estate.create-location');
    Route::post('realestate/update_location', [RealEstateController::class, 'update_location'])->name('real-estate.update-location');
    Route::post('realestate/destroy_location', [RealEstateController::class, 'destroy_location'])->name('real-estate.destroy-location');
    Route::get('realestate/{survey_id}/participant-proximity', [\App\Http\Controllers\RealEstateController::class, 'participant_proximity'])->name('real-estate.participant-proximity');
    Route::post('/realestate-getParticipantProximity', [\App\Http\Controllers\RealEstateController::class, 'getParticipantProximity'])->name('realestate.getParticipantProximity');
    Route::post('/realestate-exportParticipantExcel', [RealEstateController::class, 'export_participant_excel'])->name('realestate.exportParticipantExcel');
    Route::get('realestate/{survey_id}/activity-by-location', [\App\Http\Controllers\RealEstateController::class, 'activity_by_location'])->name('real-estate.activity-by-location');
    Route::post('/realestate-getActivityByLocation', [RealEstateController::class, 'getActivityByLocation'])->name('realestate.getActivityByLocation');
    Route::post('/realestate-getActivityByQuestion', [RealEstateController::class, 'getActivityByQuestion'])->name('realestate.getActivityByQuestion');
    Route::post('/realestate-exportLocationExcel', [RealEstateController::class, 'export_location_excel'])->name('realestate.exportLocationExcel');
    Route::post('/realestate-filter-activity-by-location', [RealEstateController::class, 'filter_activity_by_location'])->name('realestate.filter-activity-by-location');
    Route::get('realestate/{survey_id}/opportunity-detail', [\App\Http\Controllers\RealEstateController::class, 'opportunity_detail'])->name('real-estate.opportunity-detail');
    Route::post('/realestate-filter-opportunity-detail', [RealEstateController::class, 'filter_opportunity_detail'])->name('realestate.filter-opportunity-detail');
    Route::get('realestate/{survey_id}/opportunity-summary', [\App\Http\Controllers\RealEstateController::class, 'opportunity_summary'])->name('real-estate.opportunity-summary');
    Route::post('/realestate-filter-opportunity-summary', [RealEstateController::class, 'filter_opportunity_summary'])->name('realestate.filter-opportunity-summary');
    Route::get('realestate/{survey_id}/activity-cost-by-location', [\App\Http\Controllers\RealEstateController::class, 'activity_cost_by_location'])->name('real-estate.activity-cost-by-location');
    Route::post('/realestate-filter-activity-cost-by-location', [RealEstateController::class, 'filter_activity_cost_by_location'])->name('realestate.filter-activity-cost-by-location');
    Route::get('realestate/{survey_id}/proximity-by-activity', [\App\Http\Controllers\RealEstateController::class, 'proximity_by_activity'])->name('real-estate.proximity-by-activity');
    Route::post('/realestate-filter-proximity-by-activity', [RealEstateController::class, 'filter_proximity_by_activity'])->name('realestate.filter-proximity-by-activity');


    // Ajax Route
    // ---------- Individual report
    Route::get('/getRespondentData/{resp_id}/{survey_id}', [\App\Http\Controllers\ReportController::class, 'getRespondentData'])->name('getRespondentData');
    Route::post('/getRespondentList', [\App\Http\Controllers\ReportController::class, 'getRespondentList'])->name('getRespondentList');
    // ---------- Taxonomy
    Route::post('/getBranchUpdate', [\App\Http\Controllers\ReportController::class, 'getBranchUpdate'])->name('getBranchUpdate');
    // ---------- Demographic Report
    Route::post('/getDemographicData', [\App\Http\Controllers\ReportController::class, 'getDemographicData'])->name('getDemographicData');
    // ---------- Compilation Report
    Route::post('/getCompilationChildServiceData', [\App\Http\Controllers\ReportController::class, 'getCompilationChildServiceData'])->name('getCompilationChildServiceData');
    Route::post('/getDetailCompilationRespsList', [\App\Http\Controllers\ReportController::class, 'getDetailCompilationRespsList'])->name('getDetailCompilationRespsList');
    // ---------- Compilation NC Report
    Route::post('/getNCCompilationChildServiceData', [\App\Http\Controllers\NCReportController::class, 'getCompilationChildServiceData'])->name('getNCCompilationChildServiceData');
    // ---------- Validation Report
    Route::post('/getIrregularList', [\App\Http\Controllers\ReportController::class, 'getIrregularList'])->name('getIrregularList');
    Route::post('/getClassificationIrregular', [\App\Http\Controllers\ReportController::class, 'getClassificationIrregular'])->name('getClassificationIrregular');
    Route::post('/getOptionsIrregular', [\App\Http\Controllers\ReportController::class, 'getOptionsIrregular'])->name('getOptionsIrregular');
    Route::post('/getDeviationIrregular', [\App\Http\Controllers\ReportController::class, 'getDeviationIrregular'])->name('getDeviationIrregular');
    Route::post('/getFullDataOfIrregular', [\App\Http\Controllers\ReportController::class, 'getFullDataOfIrregular'])->name('getFullDataOfIrregular');
    Route::post('/exportExcelSummaryData', [\App\Http\Controllers\ReportController::class, 'exportExcelSummaryData'])->name('exportExcelSummaryData');
    Route::post('/exportExcelFullData', [\App\Http\Controllers\ReportController::class, 'exportExcelFullData'])->name('exportExcelFullData');


    Route::get('printreport', [\App\Http\Controllers\ReportController::class, 'print_report'])->name('print');
    Route::post('reports/crosstab/individual', [\App\Http\Controllers\CrosstabReportController::class, 'fetchIndividualReportData'])->name('individualCrosstabData');

    // Routes for Users
    Route::get('/survey/{survey_id}/users', [\App\Http\Controllers\UserController::class, 'survey_users'])->name('survey_users');
    Route::get('/users/fetch/{user}', [\App\Http\Controllers\UserController::class, 'getUser']);
    Route::post('/users/create', [\App\Http\Controllers\UserController::class, 'addUser']);
    Route::post('/users/add-to-survey', [\App\Http\Controllers\UserController::class, 'addToSurvey']);
    Route::post('/users/remove/{user}', [\App\Http\Controllers\UserController::class, 'removeUser']);
    Route::post('/users/update/{user}', [\App\Http\Controllers\UserController::class, 'updateUser']);
    Route::get('/users/export/{survey}', [\App\Http\Controllers\UserController::class, 'exportUsers']);

    Route::get('/all-users', [\App\Http\Controllers\UserController::class, 'allUsers'])->name('users.all');
    Route::post('/all-users/deactivate', [\App\Http\Controllers\UserController::class, 'deactivateUser'])->name('users.deactivate');
    Route::post('/all-users/activate', [\App\Http\Controllers\UserController::class, 'activateUser'])->name('users.activate');
    Route::post('/all-users/delete', [\App\Http\Controllers\UserController::class, 'forceDelete'])->name('users.delete');

    // Routes for Respondents
    Route::get('/survey/{survey}/respondents', [RespondentController::class, 'index'])->name('Respondents');
    Route::get('/survey/{survey}/respondents/fetch/{respondent}', [RespondentController::class, 'getRespondent']);
    Route::get('/survey/{survey}/respondents/downloads/example-csv', [RespondentController::class, 'downloadExampleCsv']);
    Route::get('/survey/{survey}/respondents/downloads/participants', [RespondentController::class, 'downloadRespondents']);
    Route::get('/survey/{survey}/respondents/downloads/all-participants-data', [RespondentController::class, 'downloadParticipantAllData']);
    Route::post('/survey/{survey}/respondents/save', [RespondentController::class, 'saveRespondent']);
    Route::post('/survey/{survey}/respondents/remove/all', [RespondentController::class, 'deleteRespondents']);
    Route::get('/survey/{survey}/respondents/remove/{respondent}', [RespondentController::class, 'removeRespondent']);
    Route::post('/survey/{survey}/respondents/upload', [RespondentController::class, 'uploadRespondents']);
    Route::post('/survey/{survey}/save-field-labels', [SurveyController::class, 'saveFieldLabels']);
    Route::post('/survey/{survey}/respondents/reset', [RespondentController::class, 'resetRespondents']);
    Route::get('/survey/{survey}/access-codes/generate', [RespondentController::class, 'generateAccessCode']);
    Route::post('/survey/{survey}/access-codes/validate', [RespondentController::class, 'validateAccessCode']);

    // Route for Support
    Route::get('/support', [SupportController::class, 'index'])->name('support.index');
    Route::post('/support-contact', [SupportController::class, 'contact'])->name('support.contact');

    // Route for Projects
    Route::get('/survey-projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::post('/project-create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/project-update', [ProjectController::class, 'update'])->name('projects.update');
    Route::post('/project-delete', [ProjectController::class, 'destroy'])->name('projects.delete');
    Route::post('/project-activate', [ProjectController::class, 'activate'])->name('projects.activate');
    Route::post('/project-deactivate', [ProjectController::class, 'deactivate'])->name('projects.deactivate');
});
