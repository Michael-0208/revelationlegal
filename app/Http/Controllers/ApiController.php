<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Respondent;
use Illuminate\Support\Facades\Mail;

class ApiController extends Controller
{
    public function saveReports(){
        header('Access-Control-Allow-Headers','*');
        header('Access-Control-Allow-Origin: http://44.202.96.233/');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 604800');
        //header("Content-type: application/json");
        // dd($_GET);
        unset($_GET['paymentMethodNonce']);
       
        $respondent = new Respondent();
      
        //dd($data['resp_benefit_pct']);

        $respondent->resp_access_code = rand(0,9999999);
        $respondent->resp_first =isset( $_GET['firstName']) ?$_GET['firstName'] :'';
        $respondent->resp_last =isset( $_GET['lastName']) ?$_GET['lastName'] :'';
        $respondent->resp_email =isset( $_GET['email']) ?$_GET['email'] :'';
        $respondent->cust_1 = rand(0,9999999);
        $respondent->cust_2 =isset( $_GET['groups']) ?$_GET['groups'] :'';
        $respondent->cust_3 =isset( $_GET['title']) ?$_GET['title'] :'';
        $respondent->cust_4 =isset( $_GET['department']) ?$_GET['department'] :'';
        $respondent->cust_5 =isset( $_GET['category']) ?$_GET['category'] :'';
        $respondent->rentable_square_feet = 0.00;
        $respondent->resp_compensation =isset( $_GET['compensation']) ?$_GET['compensation'] :'';
        $respondent->resp_benefit_pct = isset( $_GET['bonus']) ?$_GET['bonus'] :'';
        $respondent->survey_id = 54;
       

       // $respondent->alternate_text = $data['alternate_text'];
       if($_GET['isFullTime'] < 40 && $_GET['isFullTime'] > 0){
            $respondent->fte = ($_GET['isFullTime']/40);
       }else{
            $respondent->fte = 1;
       }
       $saved = $respondent->save();

       // $saved = $respondent->save();
        if($saved){

            $respondent->resp_access_code = base64_encode($respondent->resp_access_code);
            $respondent->survey_id = base64_encode($respondent->survey_id);
            $respondent->surveyLink = route('survey.start').'?sv='.$respondent->survey_id.'&ac='.$respondent->resp_access_code;

            // dd($respondent);
            Mail::send([], [], function ($message) use ($respondent) {
                $message->from('support@revelationlegal.com', 'support@revelationlegal.com');
                $message->to($respondent->resp_email); //$respondent->resp_email caisonlee28@gmail.com (for dynamic respondent)
                $message->subject('Thank You For registration');
                
                $body ='<html> 
                        <body>
                        <table align="center" width="600" style="font-family: sans-serif;">
                        <tr>
                        <td style="text-align: center;"><img width="180" src="http://127.0.0.1:8000/public/imgs/revel-i-logo.png" style="max-width:180px;"></td></tr> 
                        <tr><td>
                        <p style="margin-top: 20px;">
                        Hi '.$respondent->resp_first.', <br>
                        <p style="font-family: sans-serif;font-size: 15px;margin: 10px 0;">
                        Congratulations on your decision to work smarter, not harder! With RevelationLegal-i, you can quickly and easily identify and analyze the activities you perform. Youll receive a comprehensive report that allows you to pinpoint gaps in productivity, identify strategic opportunities and refocus your time on higher-value activities—all with an initial investment of about 30 minutes. It’s a small commitment with a big return!
                        </p>
                        <p style="font-family: sans-serif;font-size: 15px;margin: 0 0 10px;">
                        Follow the link below to complete our online questionnaire—and take the next step toward improved performance and productivity.  The attached Participant Guide may answer many questions and speed your completion of the questionnaire.

                        </p><p style="font-family: sans-serif;font-size: 15px;margin: 0 0 10px;font-weight: bold;">
                        Survey URL:	<a href="'.$respondent->surveyLink.'" style="font-weight: normal;">Your Questionnaire</a> 
                        </p>
                        <p style="font-family: sans-serif;font-size: 15px;margin: 0 0 10px;">
                        If you have questions or need assistance, contact us at support@revelationlegal.com.
                        </p>
                        <p style="font-family: sans-serif;font-size: 15px;margin:0 0 10px;">
                        Thank you, in advance, for your participation.
                        </p>
                        <p style="font-family: sans-serif;font-size: 12px;margin: 25px 0 0 0;">Notice: This communication may contain privileged or other confidential information. If you have received it in error, please advise the sender by reply email and immediately delete the message and any attachments without copying or disclosing the contents.
                        </p> 
                        </td></tr>
                        </table>
                        </body>
                        </html>';
                   
                $message->setBody($body, 'text/html');
                /* $message->attach( url('/') . '/public/respondentPDF/'.$resp['filename']);
                $message->attach( url('/') . '/public/REV_LEGAL-i_GUIDE.pdf'); *
    
               //return 1;
               // $message->attach('https://www.orimi.com/pdf-test.pdf');*/
            });


            echo "<script>window.close();</script>";
            //return Redirect::to($url);
           // dd('data Saved!');
        }
        
    }
}
