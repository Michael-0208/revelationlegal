<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    /**
     * 
     * Index
     * 
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index(){

        $active_surveys   = Survey::where('survey_active', 1)->orderBy('survey_created_dt', 'ASC')->paginate(10);
        $inactive_surveys = Survey::where('survey_active', 0)->orderBy('survey_created_dt', 'ASC')->paginate(10);

        return view('projects.index', compact('active_surveys', 'inactive_surveys'));
    }
    /**
     * 
     * Create Survey
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function create () {
        $this->request->validate([
            'survey_name' => 'required',
            'cust_1_label' => 'required',
            'cust_2_label' => 'required',
            'cust_3_label' => 'required',
            'cust_4_label' => 'required',
            'cust_5_label' => 'required',
            'cust_6_label' => 'required'
        ]);

        $data = $this->request->all();

        $new = new Survey();

        $new->account_id = Auth::user()->id;
        $new->survey_name = $data['survey_name'];
        $new->survey_active = 1;
        $new->survey_created_dt = date('Y-m-d H:i:s');
        $new->cust_1_label = $data['cust_1_label'];
        $new->cust_2_label = $data['cust_2_label'];
        $new->cust_3_label = $data['cust_3_label'];
        $new->cust_4_label = $data['cust_4_label'];
        $new->cust_5_label = $data['cust_5_label'];
        $new->cust_6_label = $data['cust_6_label'];

        if ($new->save()) {
            $data['status'] = 200;
            //$data['creator'] = $new->creator()->first_name . ' ' . $new->creator()->last_name;
            $data['survey_id'] = $new->survey_id;
            $data['created_dt'] = $new->survey_created_dt;
        } else {
            $data['status'] = 404;
        }

        return $data;
    }
    /**
     * 
     * Survey Update
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function update () {
        $this->request->validate([
            'survey_name' => 'required',
            'cust_1_label' => 'required',
            'cust_2_label' => 'required',
            'cust_3_label' => 'required',
            'cust_4_label' => 'required',
            'cust_5_label' => 'required',
            'cust_6_label' => 'required'
        ]);

        $data = $this->request->all();

        $update = Survey::find($data['survey_id']);
        
        $update->survey_name = $data['survey_name'];
        $update->cust_1_label = $data['cust_1_label'];
        $update->cust_2_label = $data['cust_2_label'];
        $update->cust_3_label = $data['cust_3_label'];
        $update->cust_4_label = $data['cust_4_label'];
        $update->cust_5_label = $data['cust_5_label'];
        $update->cust_6_label = $data['cust_6_label'];

        if ($update->save()) {
            $data['status'] = 200;
            $data['creator'] = $update->creator()->first_name . ' ' . $update->creator()->last_name;
            $data['created_dt'] = $update->survey_created_dt;
        } else {
            $data['status'] = 404;
        }

        return $data;
    }
    /**
     * 
     * Survey Activate
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function activate () {
        $data = $this->request->all();
    
        $activate = Survey::find($data['survey_id']);
    
        $activate->survey_active = 1;
    
        if ($activate->save()) {
            $data['status'] = 200;
            $data['survey'] = $activate;
            $data['survey']->creator = $activate->creator()->first_name . " " . $activate->creator()->last_name;
        } else {
            $data['status'] = 404;
        }
    
        return $data;
    }
    /**
     * 
     * Survey Deactivate
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function deactivate () {
        $data = $this->request->all();

        $deactivate = Survey::find($data['survey_id']);

        $deactivate->survey_active = 0;

        if ($deactivate->save()) {
            $data['status'] = 200;
            $data['survey'] = $deactivate;
            $data['survey']->creator = $deactivate->creator()->first_name . " " . $deactivate->creator()->last_name;
        } else {
            $data['status'] = 404;
        }

        return $data;
    }
    /**
     * 
     * Destroy Survey
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function destroy () {
        $survey_id = $this->request->input('survey_id');

        $destroy = Survey::find($survey_id);

        $record_error = 0;
        $error_message = '';

        if ($destroy->accounts()->count() > 0) {
            $record_error = 1;
            $error_message = 'This survey has accounts already.';
        } else if ($destroy->allowedLocations()->count() > 0) {
            $record_error = 1;
            $error_message = 'This survey has allowed locations already.';
        } else if ($destroy->departments()->count() > 0) {
            $record_error = 1;
            $error_message = 'This survey has departments already.';
        } else if ($destroy->permissions()->count() > 0) {
            $record_error = 1;
            $error_message = 'This survey has permissions already.';
        } else if ($destroy->invitations()->count() > 0) {
            $record_error = 1;
            $error_message = 'This survey has invitations already.';
        } else if ($destroy->locations()->count() > 0) {
            $record_error = 1;
            $error_message = 'This survey has locations already.';
        } else if ($destroy->questions()->count() > 0) {
            $record_error = 1;
            $error_message = 'This survey has questions already.';
        } else if ($destroy->respondents()->count() > 0) {
            $record_error = 1;
            $error_message = 'This survey has respondents already.';
        } else if ($destroy->setting()->count() > 0) {
            $record_error = 1;
            $error_message = 'This survey has its setting already.';
        } else if ($destroy->supportLocations()->count() > 0) {
            $record_error = 1;
            $error_message = 'This survey has its supported locations already.';
        }
        
        if ($record_error == 0) {
            if ($destroy->delete()) {
                $data['status'] = 200;
            } else {
                $data['status'] = 404;
            }
        } else {
            $data['status'] = 400;
            $data['message'] = $error_message;
        }

        return $data;
    }
}
