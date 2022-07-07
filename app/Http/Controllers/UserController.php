<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\FacadesAuth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\CommonController;
use App\Models\Account;
use App\Models\AllowedLocation;
use App\Models\Department;
use App\Models\Permission;
use App\Gateways\ReportData;
use App\Models\SupportLocation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{

    public function __construct(Request $request, ReportData $reportData)
    {
        $this->middleware(['auth:sanctum', 'verified']);
        $this->reportData = $reportData;
        $this->request = $request;
    }
    /**
     * 
     * Survey users index
     * 
     * @param int $survey_id    survey id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function survey_users($survey_id)
    {
        $data = [];
        $survey_data = Survey::where('survey_id', $survey_id)->get()->toArray();
        $survey = (object)[
            'survey_id' => $survey_id,
            'survey_name' => $survey_data[0]['survey_name']
        ];

        $this->validateUser($survey);

        $resps = $this->reportData->get_resp_data($survey_id);
        $tmpDepartment = array();
        $tmpLocation = array();


        foreach ($resps as $resp) {
            
            if (!array_key_exists($resp->cust_4, $tmpDepartment)) {
                $tmpDepartment[$resp->cust_4] = $resp->cust_4;
            }
            
            if (!array_key_exists($resp->cust_6, $tmpLocation)) {
                $tmpLocation[$resp->cust_6] = $resp->cust_6;
            }
        }

        sort($tmpDepartment);
        sort($tmpLocation);


        $data['survey'] = $survey;
        // $data['users'] = User::where('survey_id', $survey->survey_id)->get();
        $data['users'] = $this->getUsers($survey);
        $data['allUsers'] = User::where('survey_id', '!=', $survey->survey_id)->orWhereNull('survey_id')->get();

        $departments = Department::when(!\Auth::user()->isAdmin(), function ($q) use ($survey) { // when user is not admin, filter by department
            $q->where('survey_id', $survey->survey_id);
        })->get()->unique('name');

        $permissions = Permission::all()->unique('name');

        $locations = collect(); //?

        return view('users.survey_users', ['data' => $data, 'survey' => $survey, 'departments' => $tmpDepartment, 'permissions' => $permissions, 'locations' => $tmpLocation]);
    }
    /**
     * 
     * Export users csv
     * 
     * @param \App\Models\Survey
     * @return \Illuminate\Support\Facades\Response
     */
    public function exportUsers(Survey $survey)
    {
        $users = $this->getUsers($survey);

        $fname = 'survey-' . $survey->survey_id . '-users.csv';

        $f = fopen($fname, 'w');

        // header row
        $row = [
            'First Name',
            'Last Name',
            'Username',
            'Email Address',
            'Last Login'
        ];

        fputcsv($f, $row);

        foreach ($users as $user) {
            $row = [
                $user->first_name,
                $user->last_name,
                $user->username,
                $user->email,
                $user->last_login,
            ];
            fputcsv($f, $row);
        };



        fclose($f);

        $headers = array(
            'Content-Type' => 'text/csv',
            'Cache-Control' => 'no-cache, must-revalidate'
        );

        return Response::download($fname, $fname, $headers);
    }
    /**
     * 
     * Get users
     * 
     * @param \App\Models\Survey
     * @return \App\Models\User
     */
    private function getUsers($survey)
    {
        $users = [];
        $surveyIds = [$survey->survey_id];
        $user = \Auth::user();
        $tmpUsers = User::whereRaw('FIND_IN_SET("'.$survey->survey_id.'", survey_assign)')
           // ->orWhereIn('survey_assign',$surveyIds)
            ->when(!$user->isAdmin(), function ($q) use ($user) {
                $allowed_departments = Department::where('user_id', $user->id)->get(); // get the apartments assigned to this user
                $allowed_departments = Department::where('survey_id', $user->survey_id)->whereIn('name', $allowed_departments->pluck('name'))->get(); // now get all the departments for this survey that are in the user's allowed list
                $q->whereIn('id', $allowed_departments->pluck('user_id')); // find all the users with ids in the allowed departments list
            })
            
            ->get();
        // dd($tmpUsers);
        // $users = [];
        // foreach ($tmpUsers as $tmpUser) {
        //     if(!array_key_exists($tmpUser->user_id, $users))
        //         $users[$tmpUser->user_id]= $tmpUser;
        // }
        return $tmpUsers;
    }
    /**
     * 
     * Validate the user
     * 
     * @param \App\Models\Survey
     * @return void
     */
    protected function validateUser($survey)
    {
        // dd($survey);
        CommonController::validateUser($survey->survey_id, 'surveyUsers');
    }
    /**
     * 
     * Get the user data
     * 
     * @param \App\Models\User
     * @return \App\Models\User
     */
    public function getUser(User $user, Request $request)
    {
        ///$all_departments = Department::all()->unique('name');

        $resps = $this->reportData->get_resp_data($request->survey_id);
        $all_departments = array();
        $all_locations = array();


        foreach ($resps as $resp) {
            
            if (!array_key_exists($resp->cust_4, $all_departments)) {
                $all_departments[$resp->cust_4] = $resp->cust_4;
            }
            
            if (!array_key_exists($resp->cust_6, $all_locations)) {
                $all_locations[$resp->cust_6] = $resp->cust_6;
            }
        }

        sort($all_departments);
        sort($all_locations);




        //$all_departments = 
        $user_departments = Department::where('user_id', $user->id)->where('survey_id',$request->survey_id)->get();

        $departments = [];
        foreach ($all_departments as $index => $department) {
            $departments[] = [
                'id' => $index,
                'text' => $department,
                'selected' => $user_departments->where('name', $department)->count() > 0 ? true : false
            ];
        };
        $keys = array_column($departments, 'text');
        array_multisort($keys, SORT_ASC, $departments);
        $user->departments = $departments;
 
        $all_permissions = Permission::all()->unique('name');
        $user_permissions = Permission::where('user_id', $user->id)->where('value',$request->survey_id)->get();
       // dd($user_permissions);
        $permissions = [];
        foreach ($all_permissions as $index => $permission) {
            $permissions[] = [
                'id' => $index,
                'text' => $permission->name,
                'selected' => $user_permissions->where('name', $permission->name)->count() > 0 ? true : false
            ];
        };
        $keys = array_column($permissions, 'text');
        array_multisort($keys, SORT_DESC, $permissions);
        $user->permissions = $permissions;

        // $all_locations = SupportLocation::all()->unique('support_location_desc');
        $user_locations = AllowedLocation::where('user_id', $user->id)->where('survey_id',$request->survey_id)->get();

        $locations = [];
        foreach ($all_locations as $index => $location) {
            $locations[] = [
                'id' => $index,
                'text' => $location,
                'selected' => $user_locations->where('name', $location)->count() > 0 ? true : false
            ];
        };
        $keys = array_column($locations, 'text');
        array_multisort($keys, SORT_DESC, $locations);
        $user->locations = $locations;

        return $user;
    }


    public function getUserForAdmin(User $user)
    {
        $all_departments = Department::all()->unique('name');
        $user_departments = Department::where('user_id', $user->id)->get();
        // dd()
        $assignedSurvey = explode(',',$user->survey_assign);
        $user_survey = Survey::whereIn('survey_id',$assignedSurvey)->pluck('survey_id')->toArray();

        $departments = [];
        foreach ($all_departments as $index => $department) {
            $departments[] = [
                'id' => $index,
                'text' => $department->name,
                'selected' => $user_departments->where('name', $department->name)->count() > 0 ? true : false
            ];
        };
        $keys = array_column($departments, 'text');
        array_multisort($keys, SORT_ASC, $departments);
        $user->departments = $departments;

        $all_permissions = Permission::all()->unique('name');
        $user_permissions = Permission::where('user_id', $user->id)->get();

        $permissions = [];
        foreach ($all_permissions as $index => $permission) {
            $permissions[] = [
                'id' => $index,
                'text' => $permission->name,
                'selected' => $user_permissions->where('name', $permission->name)->count() > 0 ? true : false
            ];
        };
        $keys = array_column($permissions, 'text');
        array_multisort($keys, SORT_DESC, $permissions);
        $user->permissions = $permissions;

        
        // dd($user_survey);
        $surveys = [];
        if(count($user_survey) > 0){

            foreach ($user_survey as $key => $survey) {
                
                $surveys[] = [
                    'id' => (int)$survey,
                    'text' => Survey::where('survey_id',$survey)->pluck('survey_name')[0],
                    'selected'=>true,
                ];
            };

            
            // dd($surveys);
            // $keys = array_column($surveys, 'text');
            // array_multisort($keys, SORT_DESC, $surveys);
            
        }
        // dd();
        $user->surveys = $surveys;

        $all_locations = SupportLocation::all()->unique('support_location_desc');
        $user_locations = AllowedLocation::where('user_id', $user->id)->get();

        $locations = [];
        foreach ($all_locations as $index => $location) {
            $locations[] = [
                'id' => $index,
                'text' => $location->support_location_desc,
                'selected' => $user_locations->where('name', $location->support_location_desc)->count() > 0 ? true : false
            ];
        };
        $keys = array_column($locations, 'text');
        array_multisort($keys, SORT_DESC, $locations);
        $user->locations = $locations;

        return $user;
    }
    /**
     * 
     * Add user to survey
     * 
     * @param \Illuminate\Http\Request
     * @return mixed
     */
    public function addUser(Request $request)
    {

        $validated = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'username' => 'required|unique:users,username',
            'email' => 'required|unique:users,email',
            'password' => 'required',
        ]);

        $data = $request->all();

        $permissions = isset($data['permissions']) ? $data['permissions'] : [];
        $departments = isset($data['departments']) ? $data['departments'] : [];
        $locations   = isset($data['locations']) ? $data['locations'] : [];
        
        if (isset($data['page']) && $data['page'] == 'all_users') {
            $data['password'] = Hash::make($data['password']);
            $user = User::create($data);
        } else {
            $survey = Survey::find($data['survey_id']);
    
            $this->validateUser($survey);
            $data['password'] = Hash::make($data['password']);
            $user = User::create($data);
            $this->updatePermissions($user, $survey, $permissions);
            $this->updateDepartments($user, $survey, $departments);
            $this->updateLocations($user, $survey, $locations);
    
            // add survey to users allowed surveys
            $user->accountSurveys()->save(new Account(['survey_id' => $survey->survey_id]));
    
            $user->save();
        }

        $request->merge(['user_id' => $user->id]);
        return $this->addToSurvey($request);
    }
    /**
     * 
     * Remove user
     * 
     * @param \App\Models\User
     * @return \App\Models\User
     */
    public function removeUser(User $user, Request $request)
    {    //dd($request->survey_id);
        // $survey = null;
        // if(Auth::check())
        //     $survey = Survey::find(Auth::user()->survey_id);
        // else
        //     return [];

        // $this->validateUser($survey);
        $AssignedSurvey = explode(',',$user->survey_assign);
        $SurveyToRemove = $request->survey_id;

        if (($key = array_search( $SurveyToRemove, $AssignedSurvey)) !== FALSE) {
            unset($AssignedSurvey[$key]);

            Account::where('account_id',$user->id)->where('survey_id', $request->survey_id)->delete();
            Permission::where('user_id',$user->id)->where('value', $request->survey_id)->delete();

            $updatedSurveyIds = [
                'survey_assign' => implode(',',$AssignedSurvey)
            ];
            User::where('id',$user->id)->update($updatedSurveyIds);

        }

        //dd($AssignedSurvey);

        
    

       // $user->delete();
        return $user; //still return the existing user so we can update the view if needed
    }
    /**
     * 
     * Update the user
     * 
     * @param \Illuminate\Http\Request
     * @param \App\Models\User
     * @return \App\Models\User
     */
    public function updateUser(Request $request, User $user)
    {

        $validated = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'username' => 'required',
            'email' => 'required',
        ]);

        $data = $request->all();
        // dd($data);
        $permissions = isset($data['permissions']) ? $data['permissions'] : [];
        $departments = isset($data['departments']) ? $data['departments'] : [];
        $locations = isset($data['locations']) ? $data['locations'] : [];
        if(isset($data['survey_id'])){
            
            $survey = Survey::find($data['survey_id']);
        }else{
            $survey_id = User::where('email',$data['email'])->pluck('survey_id');
            $survey = Survey::find($survey_id[0]);
            // dd($survey);
        }
        
        

       // $this->validateUser($survey);
        if(!$request->has('fromAdmin')){
            $this->updatePermissions($user, $survey, $permissions);
            $this->updateDepartments($user, $survey, $departments);
            $this->updateLocations($user, $survey, $locations);
        }
        

        $data['password'] = $data['password'] ? Hash::make($data['password']) : $user->password;
        $user = $user->update($data);
        return $user;
    }
    /**
     * 
     * Add user to the survey
     * 
     * @param \Illuminate\Http\Request
     * @return \App\Models\User
     */
    public function addToSurvey(Request $request)
    {
        $data = $request->all();
        $projects = isset($data['projects']) ? $data['projects'] : [];

        $user = User::find($data['user_id']);

        if (isset($data['page']) && $data['page'] == 'all_users') {
            foreach ($projects as $survey_id) {
                $survey = Survey::find($survey_id);
                $user->survey_id = $survey_id;
                $user->save();

                $user->accountSurveys()->where('survey_id', $survey_id)->delete();

                $user->accountSurveys()->save(new Account(['survey_id' => $survey_id]));
            }
        } else {
            $survey = Survey::find($data['survey_id']);
            
            $this->validateUser($survey);
            
            //dd($user->permissions);
            
            $user->survey_id = $survey->survey_id;
            $user->save();

            $alreadyAssignedSurvey = $user->survey_assign;
            
            $updatedAssignedSurvey = [
                $survey->survey_id
            ];

            if($alreadyAssignedSurvey !== null){
                array_push($updatedAssignedSurvey,$alreadyAssignedSurvey);
                $updatedAssignedSurvey = implode( ',' ,$updatedAssignedSurvey);
            }else{
                $updatedAssignedSurvey = $survey->survey_id;
            }
            
           
            $updatedAssignedSurveyElq = [
                'survey_assign' => $updatedAssignedSurvey
            ];
            
            //dd($updatedAssignedSurveyElq);
            // if(count($user->permissions) < 1)
            foreach ($user->permissions as $permission) {
                //if($permission->value != $survey->survey_id){
                Permission::create([
                    'name' =>$permission->name,
                    'user_id' =>$user->id,
                    'value'=>$survey->survey_id
                ]);
               /*  }else{
                    $permission->value = $survey->survey_id;
                    $permission->save();
                } */
                
                
            }
            
            $UpdateSurvey = User::where('id',$user->id)->update($updatedAssignedSurveyElq);
    
            // remove the mapping if it exists to prevent duplicates
            $user->accountSurveys()->where('survey_id', $survey->survey_id)->delete();
    
            // add the mapping
            $user->accountSurveys()->save(new Account(['survey_id' => $survey->survey_id]));
        }

        $user->load('permissions');
        $user->allowedProjects = $user->allowedProjects;

        return $user;
    }
    /**
     * 
     * Update the permission
     * 
     * @param \App\Models\User $user
     * @param \App\Models\Survey $survey
     * @param \App\Models\Permission $permissions
     * @return void
     */
    private function updatePermissions($user, $survey, $permissions)
    {
        // $survey->survey_id = explode
        // $permissions = []   
        foreach ($permissions as $perm) {
            // Permission::create(['name' => $perm, 'user_id' => $user->id]);
            $permission = new Permission();
            $permission->name = $perm;
            $permission->user_id = $user->id;
            $permission->value = $survey->survey_id; //FIXME: Not exactly sure where there is a reference to the survey on the users table and the permissions table, what is the difference and what is the purpose
            $permission->save();
        }
        $user->load('permissions');
    }
    /**
     * 
     * Update the departments
     * 
     * @param \App\Models\User $user
     * @param \App\Models\Survey $survey
     * @param \App\Models\Department $departments
     * @return void
     */
    private function updateDepartments($user, $survey, $departments)
    {
        Department::where('user_id', $user->id)->where('survey_id',$survey->survey_id)->delete();
        // dd($departments, $user, $survey);
        $allowed_departments = [];
        foreach ($departments as $department) {
            $allowed_departments[] = new Department([
                'user_id' => $user->id,
                'name' => $department,
                'survey_id' => $survey->survey_id
            ]);
        }

        $user->departments()->saveMany($allowed_departments);

        $user->refresh(); // load the newly save departments onto the User model
    }
    /**
     * 
     * Update the locations
     * 
     * @param \App\Models\User $user
     * @param \App\Models\Survey $survey
     * @param \App\Models\Location $location
     * @return void
     */
    private function updateLocations($user, $survey, $locations)
    {
        AllowedLocation::where('user_id', $user->id)->where('survey_id',$survey->survey_id)->delete();
        $allowed_locations = [];
        foreach ($locations as $location) {
            $allowed_locations[] = new AllowedLocation([
                'user_id' => $user->id,
                'name' => $location,
                'survey_id' => $survey->survey_id
            ]);
        }

        $user->locations()->saveMany($allowed_locations);

        $user->refresh(); // load the newly save departments onto the User model
    }
    /**
     * 
     * Return all users view
     * 
     * @param \App\Models\Survey
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function allUsers(Survey $survey, Request $request)
    {
        $inactive_users = User::onlyTrashed()->paginate(10);
        $active_users = User::paginate(10);
        // dd($active_users);
        return view('users.all_users', compact('inactive_users', 'active_users'));
    }
    /**
     * 
     * Deactivate the user
     * 
     * @param \Illuminate\Http\Request
     * @return \App\Models\User
     */
    public function deactivateUser(Request $request)
    {
        if (!\Auth::check() || !\Auth::user()->is_admin)
            return [
                'error' => 'You do not have the required permissions to performs this action'
            ];


        $user = User::find($request->user_id);
        $user->delete();
        $user->allowedProjects = $user->allowedProjects;
        return $user;
    }
    /**
     * 
     * Activate the user
     * 
     * @param \Illuminate\Http\Request
     * @return \App\Models\User
     */
    public function activateUser(Request $request)
    {
        if (!\Auth::check() || !\Auth::user()->is_admin)
            return [
                'error' => 'You do not have the required permissions to performs this action'
            ];

        $user = User::withTrashed()->find($request->user_id);
        $user->restore();
        $user->allowedProjects = $user->allowedProjects;
        return $user;
    }
    /**
     * 
     * Force delete the user
     * 
     * @param \Illuminate\Http\Request
     * @return \App\Models\User
     */
    public function forceDelete(Request $request)
    {
        if (!\Auth::check() || !\Auth::user()->is_admin)
            return [
                'error' => 'You do not have the required permissions to performs this action'
            ];

        $user = User::withTrashed()->find($request->user_id);
        $user->forceDelete();
        return $user;
    }
}
