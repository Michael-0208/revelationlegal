@extends('layouts.reports') 
@section('content')

<style>
    .virtual-bar{
        background-color: gold;
    }
</style>

<div id="HelpContent" class="modal-body" style="display:none;">
<p><strong>Getting Started</strong></p>
<p>When you initially click on the Real Estate tab, you will notice that you have a multitude of
reports to look at. The information you will view may seem confusing at first, so it’s important to
take note of several points first.</p>

<p>To start off, you will be seeing the term <strong>‘Proximity Factor</strong>’ quite often. The proximity factor is required for each activity within the taxonomy, and each activity will either have a high,
medium, or low proximity factor. This will determine how costly an activity will be:</p>

<p><strong>High: Must be near customer</strong></p>
<p>Service provider must have regular personal interaction with the customer or the service provider
must have access to physical files etc. that are maintained by the customer. Proximity
requirement demands that they reside within the same floor of the same building.</p>

<p><strong>Medium: Needs to be relatively close to customer</strong></p>
<p>Periodic personal interactions are required for effective delivery of services. Service provider
does not need to be in the same space as customer but needs to be in close proximity so periodic
personal meetings can occur without requiring travel (so in the same building on a different floor,
or same city in a different building).</p>

<p><strong>Low: Does not need to be near customer</strong></p>
<p>Interfacing with customers using technology (phone, email, etc.) is sufficient. Collaboration
tools, web conferencing, and shared access to file systems are adequate to facilitate effective
interactions. Service providers can be located anywhere and can work virtually.</p>

<p>Another term that you will see is RSF Rates. This is the cost of the square footage that an
individual takes up. RSF Rates are required for each location. C stands for Current, which is also
a high proximity value. This is the most expensive RSF Rate. A stands for Adjacent, which
means that there is a medium proximity value, which is slightly less expensive. R and O stand
for Regional and Other, which are low proximity values, meaning that they are the cheapest.</p>
<img src="{{asset('imgs/user-guide/image-044.png')}}" alt="img">
<p>You will also see something called RSF Requirement per Participant. This is simply telling you
how much space an individual takes up. The more important the position, the more space they
will take up:</p>
<img src="{{asset('imgs/user-guide/image-045.png')}}" alt="img">


<p><strong>Participant Proximity</strong></p>
<p>The Proximity Report provides you with an overview of the annual hours reported by each
participant. Each participant’s hours are allocated by the activity’s respective proximity factor.
When you first enter this report, you will see that the default view is unfiltered. However, if you
would like to view a more specific report, you may do so by using the filters located at the top of
your screen. The<strong> filters located on the right</strong> allow for you to sort by <strong>demographic</strong>, while the <strong>filters on the left</strong> allow for you to sort by <strong>task</strong>.</p>
<p class="d-flex flex-wrap-wrap "><img src="{{asset('imgs/user-guide/image-056.png')}}" alt="img">
<img src="{{asset('imgs/user-guide/image-055.png')}}" alt="img"></p>
<p>Below these filters, you will see a row of colors that act as a guide to help you view this report.
The color<strong> Red </strong>means that the hours spent in these activities have a<strong> High proximity cost</strong>, meaning that they are the most expensive. The<strong> Golden-Orange</strong> color means that hours spent in these activities have a <strong>Medium proximity cost</strong>, meaning that they are slightly cheaper than High proximity activities. The <strong>Blue</strong> color means that hours spent in these activities have a <strong>Low proximity cost</strong>, meaning that they are the cheapest activities.</p>

<p>Below this, you will notice a list of individuals. Located just above the list of individuals is a
field to search for a specific individual’s results. This list tells you a summary of each individual
report for each individual. It has multiple columns that will tell you their Name and Employee
ID, their Position, the Total RSF Cost for that specific individual, and the Total Amount of Hours
that they reported for the year. You will notice that the last three columns show you how many
hours an individual spent working in High, Medium, and Low proximity factor activities
throughout the year.</p>
<img src="{{asset('imgs/user-guide/image-057.png')}}" alt="img">

<p><strong>Proximity by Activity (Beta)</strong></p>
<p>This is the last section in the Real Estate section of the database. This report is color coded, and
tells you the Proximity Factor for activities in both the ‘Legal Services’ and ‘Support Activities’
sections. If you would like more in depth information or clarification on what a Proximity Factor
is, please see the section in real estate titled <strong>‘Getting Started’</strong>.</p>

<p>The first thing you will notice when you come to this report is the colored bar going across your
screen. The colors are labeled ‘High’, ‘Medium’, or ‘Low’. This simply means that each color
represents a different proximity factor. <strong>Red, also labeled as ‘High’</strong>, means that the activities
within this proximity factor are the most expensive.<strong> Orange/Gold, also labeled as ‘Medium’</strong>,
means that the activities in this proximity factor are slightly less expensive than the activities in
‘High’, but they are not the cheapest option. Finally, <strong>Blue, also labeled as ‘Low’</strong>, means that the
activities in this proximity factor are the cheapest and least expensive.</p>
<img src="{{asset('imgs/user-guide/image-062.png')}}" alt="img">
<p>You will also notice that this colored bar has numbers listed under each labeled proximity factor.
This is the total amount of money (in a dollar amount)that the activities cost the firm. Since
‘High’ houses the most expensive activities, it is likely that this dollar amount will be the
highest.</p>

<p>Below this bar, you will see the actual Proximity by Activity report. There are sections for both
Legal Services and Support activities. Within this report, you will see various activities that make
up these sections. Next to each activity, you will see a dollar amount, and a color, that will tell
you how much the activity costs the firm and what proximity factor it falls under. Remember that
<strong>Red means a High proximity factor, Gold/Orange means a medium proximity factor</strong>, and
<strong>Blue means a Low proximity factor</strong>. The dollar amounts for each proximity factor/activity will
add up to total the main number that you see in the colored bar at the top of the report.</p>

<p>The default view for this report is unfiltered, but you may change that at any time by using the
set of filters at the very top of the page. These filters allow for a more specific view of the data
that you are seeing. You may sort by Position, Department, Group, Location, and Category.</p>
<img src="{{asset('imgs/user-guide/image-063.png')}}" alt="img">
<p>Should you choose to do so, you may also download this report as a PDF file, by selecting the
‘Download PDF’ button directly above the filters.</p> 

</div>
 
 

<div class="container-fluid px-3 hideinhelppdf">   
    <div class="flex justify-between items-center cont-mtitle  mb-4">
        <h1 class="text-survey">Proximity by Activity(Beta) / {{ $survey->survey_name }}</h1>
        <div class="d-flex py-0 py-md-2"> 
        @if (\Auth::check() && \Auth::user()->hasPermission('surveyPrint', $survey))
            <button class="revnor-btn mr-1" id="pdfBtn">Download PDF</button>            
        @endif
        <button type="button" class="helpguidebtn mx-1" data-toggle="modal" data-target="#helpdetasurvey"></button>   
        </div>
</div>
    </div>
    <div class="hideinhelppdf" id="participantProximityContent "> 
        <div class="first_part" style="background: white;">
            @include('real_estate.partials.proximity-by-activity-filter')
            {{-- dd($data['rows'])--}}
        </div>
        <div class="second_part pt-4" style="background: white;">
            <div id="proximity_bar" class="flex w-full">
                <div class="high-bar text-center" style="width: {{ $data['rsf_percent_data']['high_percent'] }}%;">
                    <div class="title font-bold">High</div>
                    <div class="value">{{ number_format($data['rsf_percent_data']['high_hours']) }}</div>
                </div>
                <div class="med-bar text-center" style="width: {{ $data['rsf_percent_data']['med_percent'] }}%;">                    
                    <div class="title font-bold">Med</div>
                    <div class="value">{{ number_format($data['rsf_percent_data']['med_hours']) }}</div>
                </div>
                <div class="low-bar text-center" style="width: {{ $data['rsf_percent_data']['low_percent'] }}%;">                    
                    <div class="title font-bold">Low</div>
                    <div class="value">{{ number_format($data['rsf_percent_data']['low_hours']) }}</div>
                </div>
       
                <div class="vitual-bar text-center" style="width: 10%;  border: 1px solid black;  background-color: gold;">                    
                    <div class="title font-bold">Virtual</div>
                    <div class="value">{{ number_format($data['rsf_percent_data']['virtual_hours']) }}</div>
                </div>   
            </div>
        </div>
        <div class="third_part py-4" style="background: white;">
            <div class="tableContainer table-txtmid table-responsive">
                <table id="proximityActivityTable" class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th colspan="{{ $data['depth'] }}"></th>
                            <th style="border-right: 1px solid;" colspan="4" class="text-center">Hours</th>
                            <th colspan="4" class="text-center">RSF</th>
                        </tr>
                        <tr>
                            @for ($i = 0; $i < $data['depth']; $i++)
                                <th style="border-bottom: none;padding-top: 20px;">{{ $data['thAry'][$i] }}</th>
                            @endfor
                            <th style="border-bottom: none;">High</th>
                            <th style="border-bottom: none;">Med</th>
                            <th style="border-bottom: none;">Low</th>
                            <th style="border-bottom: none; border-right: 1px solid #000;">Virtual</th>
                            <th style="border-bottom: none;">High</th>
                            <th style="border-bottom: none;">Med</th>
                            <th style="border-bottom: none;">Low</th>
                            <th style="border-bottom: none;">Virtual</th>
                        </tr>
                        <tr style="height:20px;">
                            @for ($i = 0; $i < $data['depth']; $i++)
                                <th class="jump-th" style="border-top: none;">
                                    <div class="flex justify-center jump-btn">
                                        @if ($i == $data['depth'] - 1) 
                                            <svg onclick="JumpToQuestionsByDepth({{ $i + 2 }});" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 1024 1024"><path d="M328 544h152v152c0 4.4 3.6 8 8 8h48c4.4 0 8-3.6 8-8V544h152c4.4 0 8-3.6 8-8v-48c0-4.4-3.6-8-8-8H544V328c0-4.4-3.6-8-8-8h-48c-4.4 0-8 3.6-8 8v152H328c-4.4 0-8 3.6-8 8v48c0 4.4 3.6 8 8 8z" fill="currentColor"/><path d="M880 112H144c-17.7 0-32 14.3-32 32v736c0 17.7 14.3 32 32 32h736c17.7 0 32-14.3 32-32V144c0-17.7-14.3-32-32-32zm-40 728H184V184h656v656z" fill="currentColor"/></svg>
                                        @else
                                            <svg onclick="JumpToQuestionsByDepth({{ $i + 1 }});" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 1024 1024"><path d="M328 544h368c4.4 0 8-3.6 8-8v-48c0-4.4-3.6-8-8-8H328c-4.4 0-8 3.6-8 8v48c0 4.4 3.6 8 8 8z" fill="currentColor"/><path d="M880 112H144c-17.7 0-32 14.3-32 32v736c0 17.7 14.3 32 32 32h736c17.7 0 32-14.3 32-32V144c0-17.7-14.3-32-32-32zm-40 728H184V184h656v656z" fill="currentColor"/></svg>
                                        @endif
                                    </div>
                                </th>
                            @endfor
                            <th colspan="6" style="border-top:none;"></th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach ($data['rows'] as $row)
                            @php
                                $questionDescAry = explode('..', $row['question_desc']);
                            @endphp
                            <tr>
                                @for ($i = 0; $i < $data['depth']; $i++)
                                    <td class="questionDescTD{{ $i }}" data-option="{{ $questionDescAry[0] }}">{{ $questionDescAry[$i] }}</td>
                                @endfor
                                <td>
                                    @if ($row['high_hours'] > 0)
                                    <div class="flex items-center">
                                        <div class="high-bar" style="width: {{ 60 * $row['high_hours'] / $data['max_high_hours'] }}%;height:15px;margin-right:1px;"></div>
                                        <div class="text-high">{{ number_format($row['high_hours']) }}</div>
                                    </div>                                        
                                    @endif
                                </td>
                                <td>
                                    @if ($row['med_hours'] > 0)
                                    <div class="flex items-center">
                                        <div class="med-bar" style="width: {{ 60 * $row['med_hours'] / $data['max_med_hours'] }}%;height:15px;margin-right:1px;"></div>
                                        <div class="text-med">{{ number_format($row['med_hours']) }}</div>
                                    </div>                                        
                                    @endif
                                </td>
                                <td>
                                    @if ($row['low_hours'] > 0)
                                    <div class="flex items-center">
                                        <div class="low-bar" style="width: {{ 60 * $row['med_hours'] / $data['max_med_hours'] }}%;height:15px;margin-right:1px;"></div>
                                        <div class="text-low">{{ number_format($row['low_hours']) }}</div>
                                    </div>                                        
                                    @endif
                                </td>
                                <td style="border-right:1px solid #000;">
                                    @if ($row['virtual_hours'] > 0)
                                    <div class="flex items-center">
                                        <div class="virtual-bar" style="width: {{ 60 * $row['virtual_hours'] / $data['max_virtual_hours'] }}%;height:15px;margin-right:1px;"></div>
                                        <div class="text-low">{{ number_format($row['virtual_hours']) }}</div>
                                    </div>                                        
                                    @endif
                                </td>
                                <td>
                                    @if ($row['high_rsf'] > 0)
                                    <div class="flex items-center">
                                        <div class="high-bar" style="width: {{ 60 * $row['high_rsf'] / $data['max_high_rsf'] }}%;height:15px;margin-right:1px;"></div>
                                        <div class="text-high">${{ number_format($row['high_rsf']) }}</div>
                                    </div>                                        
                                    @endif
                                </td>
                                <td>
                                    @if ($row['med_rsf'] > 0)
                                    <div class="flex items-center">
                                        <div class="med-bar" style="width: {{ 60 * $row['med_rsf'] / $data['max_med_rsf'] }}%;height:15px;margin-right:1px;"></div>
                                        <div class="text-med">${{ number_format($row['med_rsf']) }}</div>
                                    </div>                                        
                                    @endif
                                </td>
                                <td>
                                    @if ($row['low_rsf'] > 0)
                                    <div class="flex items-center">
                                        <div class="low-bar" style="width: {{ 60 * $row['low_rsf'] / $data['max_low_rsf'] }}%;height:15px;margin-right:1px;"></div>
                                        <div class="text-low">${{ number_format($row['low_rsf']) }}</div>
                                    </div>                                        
                                    @endif
                                </td>
                                <td>
                                    @if ($row['virtual_rsf'] > 0)
                                    <div class="flex items-center">
                                        <div class="virtual-bar" style="width: {{ 60 * $row['virtual_rsf'] / $data['max_virtual_rsf'] }}%;height:15px;margin-right:1px;"></div>
                                        <div class="text-low">${{ number_format($row['virtual_rsf']) }}</div>
                                    </div>                                        
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
    <div id="copyright_div" class="flex justify-content-between items-end" style="">
        <div>
            <img  src="{{asset('imgs/logo-pdfhead.png')}}">
        </div>
        <div class="text-center">
            <a href="{{ url('/') }}">{{ url('/') }}</a> <br>
            <span >© ofPartner LLC {{date("Y")}}, All Rights Reserved.</span>
        </div>
        <div>
            <span>Report Generated @php echo date('m/d/Y h:i:s') @endphp</span>
        </div>
    </div>

    <div id="headerDiv" class="pdfheaderdiv">   
            <p class="text-phead">Proximity by Activity(Beta) / {{ $survey->survey_name }}</p> 
            <p class="redtext-phead">Confidential</p>
            {{-- <img  src="{{asset('imgs/logo-pdfhead.png')}}">   --}}
    </div> 
    </div>

    </div>   
    <div class="modal fade" id="helpdetasurvey" tabindex="-1" aria-labelledby="exampleModalCenterTitle"  aria-hidden="true">
<div class="modal-dialog modal-dialog-centered" role="document">
  <div class="modal-content">
    <div class="modal-header align-items-center">
      <h5 class="modal-title" id="exampleModalCenterTitle">User Guide</h5> 
      <button class="revnor-btn ml-auto mr-2 mb-3 mb-md-0 bg-white text-dark" id="printHelp">Print</button> 
      <button type="button" class="close ml-0" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
    </div>
    <div class="modal-body">
    <p><strong>Getting Started</strong></p>
<p>When you initially click on the Real Estate tab, you will notice that you have a multitude of
reports to look at. The information you will view may seem confusing at first, so it’s important to
take note of several points first.</p>

<p>To start off, you will be seeing the term <strong>‘Proximity Factor</strong>’ quite often. The proximity factor is required for each activity within the taxonomy, and each activity will either have a high,
medium, or low proximity factor. This will determine how costly an activity will be:</p>

<p><strong>High: Must be near customer</strong></p>
<p>Service provider must have regular personal interaction with the customer or the service provider
must have access to physical files etc. that are maintained by the customer. Proximity
requirement demands that they reside within the same floor of the same building.</p>

<p><strong>Medium: Needs to be relatively close to customer</strong></p>
<p>Periodic personal interactions are required for effective delivery of services. Service provider
does not need to be in the same space as customer but needs to be in close proximity so periodic
personal meetings can occur without requiring travel (so in the same building on a different floor,
or same city in a different building).</p>

<p><strong>Low: Does not need to be near customer</strong></p>
<p>Interfacing with customers using technology (phone, email, etc.) is sufficient. Collaboration
tools, web conferencing, and shared access to file systems are adequate to facilitate effective
interactions. Service providers can be located anywhere and can work virtually.</p>

<p>Another term that you will see is RSF Rates. This is the cost of the square footage that an
individual takes up. RSF Rates are required for each location. C stands for Current, which is also
a high proximity value. This is the most expensive RSF Rate. A stands for Adjacent, which
means that there is a medium proximity value, which is slightly less expensive. R and O stand
for Regional and Other, which are low proximity values, meaning that they are the cheapest.</p>
<img src="{{asset('imgs/user-guide/image-044.png')}}" alt="img">
<p>You will also see something called RSF Requirement per Participant. This is simply telling you
how much space an individual takes up. The more important the position, the more space they
will take up:</p>
<img src="{{asset('imgs/user-guide/image-045.png')}}" alt="img">


<p><strong>Activity Cost by Location</strong><p>
<p>When you enter the Activity Cost by Location Report, you will first see a datatable with various
numbers in it. Originally, the dataset view is unfiltered; however, you may change this by using
the set of filters located at the top of your screen. These filters allow for you to sort by Position,
Department, Group, Location, and Proximity Factor. These filters will provide a more specific
viewing of the report for you. Should you choose to do so, you may also download the report in
the form of a PDF file. There is a button located above the filters to allow you to do so.</p>
<img src="{{asset('imgs/user-guide/image-060.png')}}" alt="img">
<p>Below these filters is the datatable, which essentially shows you the locations in which activities
are taking place, the type of activities, and the cost for all of them. The specific sections of this
table will be further elaborated below:</p>

<p>Location- The first tab of this table simply tells you what location activities are taking place in.
Some locations may have more activities than others.</p>
<img src="{{asset('imgs/user-guide/image-061.png')}}" alt="img">
<p>Legal/Support- This tab of the report will tell you whether the activities you are viewing fall
under the Legal Services Category, or the Support Activities category. Legal Services refers to
those individuals who are actively practicing law– i.e., a lawyer. Support Activities refers to
individuals who are supporting the practice of law. An example of this would be somebody who
works in the Financial Department, or Administrative Services.</p>

<p>Classification- This part of the report simply tells you where users spend their time. For example,
this could include Litigation, Human Resources, Bankruptcy, Counseling, and more.</p>

<p>Substantive Area- This tells you what area of a department an individual spends their time in. It’s
essentially one step lower in the taxonomy, meaning that what you are viewing is a bit more
specific. For example, if you are viewing the activities under the ‘litigation’ department, the
substantive area would show you activities that take place in that department, such as Appeal,
Case Discovery, Trial Preparation and Trial, and more.</p>

<p>Category- This is the next tab of the report, and it is also the next step below Substantive Area in
the taxonomy. This will tell you a specific type of job in a department that individuals take part
in. For example, Invoicing (Billing) is a part of Credit and Collections, which is a part of
Finance, which is a Support Activity.</p>

<p>Employee Cost– This section of the datatable simply tells you the cost to the firm of all
employees within a certain activity. If a number is higher, that just means that there are more
employees within that activity, thus it is more expensive.</p>

<p>RSF- This tells you the amount of square footage that employees take up within their activities.
RSF stands for ‘Rental Square Footage’. The higher up in a firm that a position is, the higher the
RSF will be.</p>

<p>Hours- This simply tells you the number of hours that employees reported in their survey
responses. It’s showing you the amount of hours that users spend in each activity. If the number
shows a zero, that’s because the amount of hours is a fraction; it’s a very small amount of time.</p>

<p>RSF Cost (Current)- This last section on the report will show you the current RSF cost for each
activity. RSF Cost varies by location. If you would like more clarification on what an RSF Cost
is, please see the section titled <strong>‘Getting Started’</strong> and <strong>‘Location RSF Rates’</strong>.</p><p><strong>Proximity by Activity (Beta)</strong></p>
<p>This is the last section in the Real Estate section of the database. This report is color coded, and
tells you the Proximity Factor for activities in both the ‘Legal Services’ and ‘Support Activities’
sections. If you would like more in depth information or clarification on what a Proximity Factor
is, please see the section in real estate titled <strong>‘Getting Started’</strong>.</p>

<p>The first thing you will notice when you come to this report is the colored bar going across your
screen. The colors are labeled ‘High’, ‘Medium’, or ‘Low’. This simply means that each color
represents a different proximity factor. <strong>Red, also labeled as ‘High’</strong>, means that the activities
within this proximity factor are the most expensive.<strong> Orange/Gold, also labeled as ‘Medium’</strong>,
means that the activities in this proximity factor are slightly less expensive than the activities in
‘High’, but they are not the cheapest option. Finally, <strong>Blue, also labeled as ‘Low’</strong>, means that the
activities in this proximity factor are the cheapest and least expensive.</p>
<img src="{{asset('imgs/user-guide/image-062.png')}}" alt="img">
<p>You will also notice that this colored bar has numbers listed under each labeled proximity factor.
This is the total amount of money (in a dollar amount)that the activities cost the firm. Since
‘High’ houses the most expensive activities, it is likely that this dollar amount will be the
highest.</p>

<p>Below this bar, you will see the actual Proximity by Activity report. There are sections for both
Legal Services and Support activities. Within this report, you will see various activities that make
up these sections. Next to each activity, you will see a dollar amount, and a color, that will tell
you how much the activity costs the firm and what proximity factor it falls under. Remember that
<strong>Red means a High proximity factor, Gold/Orange means a medium proximity factor</strong>, and
<strong>Blue means a Low proximity factor</strong>. The dollar amounts for each proximity factor/activity will
add up to total the main number that you see in the colored bar at the top of the report.</p>

<p>The default view for this report is unfiltered, but you may change that at any time by using the
set of filters at the very top of the page. These filters allow for a more specific view of the data
that you are seeing. You may sort by Position, Department, Group, Location, and Category.</p>
<img src="{{asset('imgs/user-guide/image-063.png')}}" alt="img">
<p>Should you choose to do so, you may also download this report as a PDF file, by selecting the
‘Download PDF’ button directly above the filters.</p> 
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>
</div>    

    <div class="modal fade" tabindex="-1" role="dialog" id="generatePDFModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body flex items-center justify-center" style="height: 150px;">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> &nbsp;&nbsp; Generating PDF...
                </div>
                <div class="modal-footer">
                    <button class="btn btn-revelation-primary" onclick="generatePDF();" disabled>Download</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" role="dialog" id="generateExcelModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body flex items-center justify-center" style="height: 150px;">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> &nbsp;&nbsp; Generating Excel file ...
                </div>
                <div class="modal-footer">
                    <a class="btn btn-revelation-primary disabled" href="javascript:void(0);">Download</a>
                </div>
            </div>
        </div>
    </div>
    <div class="loading-mask"></div>
</div>
    <script>

$('#printHelp').on('click', function(){
          
          // var respondent_name_print = $('#respondent_data').find('h3').text();
          // if(respondent_name_print != ''){

          $('#headerDiv').show();
          $('#hiddenprint').hide();
          $('.modal-backdrop').hide();
          $('#helpdetasurvey').modal('hide');
          $('#pdfhidden').hide();
          $('.hideinhelppdf').hide(); 
          $('#HelpContent').show();
          $('#copyright_div').addClass('fixedbottompdf');
          $('#headerDiv').addClass('fixedtoppdf');
          $(".entrymain-content")[0].style.minHeight = "0"; 

          const hideElements = ['#desktop_sidebar','#hideinpdf', '.site-footer','.site-header','.first_part', '#pdfPrint', 'header > div > ul'];

          $.each(hideElements, function(_, el){ $(el).hide(); });

          window.print();

          $.each(hideElements, function(_, el){ $(el).show(); });
          
          $('#headerDiv').hide();
          $('#HelpContent').hide();
          $('#hiddenprint').show();
          $('#pdfhidden').show();
          $('.hideinhelppdf').show(); 
          $('#copyright_div').removeClass('fixedbottompdf');
          $('#headerDiv').removeClass('fixedtoppdf');
          $(".entrymain-content")[0].style.minHeight = "100vh";  
         /*  }else{ 
              $('#selectRespModal').modal();

          } */

         
      }); 



        var survey_id = @php echo $data['survey']->survey_id; @endphp;

        var highColor = "#e15659";
        var medColor = "#f28d36";
        var lowColor = "#4e7aa5"; 

        var imgData_1, imgData_2, imgData_3, imgData_4, copyrightData, headerData;

        let numberFormatter = new Intl.NumberFormat('en-US');

        $(document).ready(function () {
            mask_height = $('body').height();
            $('.loading-mask').css('height', mask_height);
            $('.loading-mask').show();
            for (let i = 0; i < depthQuestion; i++) {
                var span = 1;
                var prevTD = "";
                var prevTDVal = "";
                var prevTDOption = "";
                $(`td.questionDescTD${i}`).each(function() {
                    var $this = $(this);
                    if ($this.text() == prevTDVal && $this.attr('data-option') == prevTDOption) { // check value of previous td text
                        span++;
                        if (prevTD != "") {
                            prevTD.attr("rowspan", span); // add attribute to previous td
                            $this.remove(); // remove current td
                        }
                    } else {
                        prevTD     = $this; // store current td
                        prevTDVal  = $this.text();
                        prevTDOption  = $this.attr('data-option');
                        span       = 1;
                    }
                });
            }
            $('.loading-mask').hide();
        });

        // Handle the pdf button click, generate image data from the body
        $('#pdfBtn').click(function () {
            $('#headerDiv').show();
            $('#copyright_div').addClass('d-flex');
			$('#copyright_div').show();	 			
            $('#generatePDFModal').modal('show');
            source = $('#participantProximityContent .first_part');
            html2canvas(source, {
                    scale:3
                    }).then(function(canvas) {
                        
                    imgData_1 = canvas.toDataURL("image/png", 1.0);
                        
                });
            source = $('#participantProximityContent .second_part');
            html2canvas(source, {
                    scale:3
                    }).then(function(canvas) {
                        
                    imgData_2 = canvas.toDataURL("image/png", 1.0);
                        
                });
            // Copyright
            source = $('#copyright_div');
            html2canvas(source, {
                   // scale:3
                    }).then(function(canvas) {
                        
                    copyrightData = canvas.toDataURL("image/png", 1.0);
                        
                });
            source = $('#headerDiv');
            html2canvas(source, {
                    scale:3
                    }).then(function(canvas) {
                        
                    headerData = canvas.toDataURL("image/png", 1.0);
                        
                });
            source = $('#participantProximityContent .third_part');
            html2canvas(source, {
                onrendered: function (canvas) {
                    imgData_3 = canvas.toDataURL('image/jpeg', 1.0);
                }
            }).then(function () {
                $('#generatePDFModal .modal-body').html('Generated a PDF');
                $('#generatePDFModal .btn').attr('disabled', false);
                $('#headerDiv').show();
					$('#copyright_div').removeClass('d-flex');
			        $('#copyright_div').hide();	 				
            });
        });

        $('#generatePDFModal').on('hidden.bs.modal', function () {
            $('#generatePDFModal').modal('hide');
            $('#generatePDFModal .modal-body').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> &nbsp;&nbsp; Generating PDF...`);
            $('#generatePDFModal .btn').attr('disabled', true);
        });

        $('#generateExcelModal').on('hidden.bs.modal', function () {
            $('#generateExcelModal').modal('hide');
            $('#generateExcelModal .modal-body').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> &nbsp;&nbsp; Generating Excel file...`);
            $('#generateExcelModal .btn').attr('href', 'javascript:void(0);');
            $('#generateExcelModal .btn').addClass('disabled');
        });

        $('#generateExcelModal .btn').click(function () {
            $('#generateExcelModal').modal('hide');
        });

        /**
        * Generate pdf document of report
        *
        * @return {void}
        */
        function generatePDF () {
            let imgWidth = $('#participantProximityContent .first_part').outerWidth();
            pdfdoc = new jsPDF('p', 'mm', 'a4');
            imgHeight1 = Math.round($('#participantProximityContent .first_part').outerHeight() * 190 / imgWidth);
            y = 14;
            position = y; 
            doc_page = 1;

            /* pdfdoc.addImage(imgData_1, 'JPEG', 10, y, 190, imgHeight1);
            y += imgHeight1; */

            imgHeight2 = Math.round($('#participantProximityContent .second_part').outerHeight() * 190 / imgWidth);
            pdfdoc.addImage(imgData_2, 'JPEG', 10, y, 190, imgHeight2);
            y += imgHeight2;

            imgHeight3 = Math.round($('#participantProximityContent .third_part').outerHeight() * 190 / imgWidth);
            pdfdoc.addImage(imgData_3, 'JPEG', 10, y, 190, imgHeight3);
            y += imgHeight3;

            pageHeight = pdfdoc.internal.pageSize.height - 20;
            heightLeft = y - pageHeight;

            while (heightLeft >= -pageHeight) {
                position = heightLeft - imgHeight3;
                pdfdoc.addPage();
                doc_page++;
                pdfdoc.addImage(imgData_3, 'JPEG', 10, position, 190, imgHeight3);
                heightLeft -= pageHeight;
            }

            pdfdoc.deletePage(doc_page);

            for (i = 1; i < doc_page; i++) {
                pdfdoc.setPage(i);
                pdfdoc.addImage(headerData, 'JPEG', 10, 0, 190, 14); 
                pdfdoc.addImage(copyrightData, 'JPEG', 10, 282, 190, 14.5);
                // pdfdoc.text('Page ' + i + ' of ' + (doc_page-1) , 8, 275, 90.916667, 52.916667, null, null, 45);
                pdfdoc.setTextColor(111,107,107); 
                pdfdoc.setFontSize(8); 
                pdfdoc.text('Page ' + i + ' of ' + (doc_page-1) , 168, 290, 0, 45);    
            }

            pdfdoc.save(`Proximity by Activity(Beta) - {{$data['survey']->survey_name}}`);
            $('#pdfBtn').html('Download PDF');
            $('#pdfBtn').prop('disabled', false);
            $('#generatePDFModal').modal('hide');
            $('#generatePDFModal .btn').attr('disabled', true);
        }

        function table_numberFormatter (value) {
            return numberFormatter.format(value);
        }

        function table_costFormatter (value) {
            return '$' + numberFormatter.format(value);
        }

        function table_highFormatter (value, row) {
            if (value > 0) {
                let width = Math.round(50 * value / max_hours);
                let show = numberFormatter.format(Math.round(value));
                let html = `<div class="flex items-center">
                        <div class="high-bar" style="width: ${width}%;height:15px;margin-right:1px;"></div>
                        <div>${show}</div>
                    </div>`;
                return html;
            } else {
                return '';
            }
        }

        function table_medFormatter (value, row) {
            if (value > 0) {
                let width = Math.round(50 * value / max_hours);
                let show = numberFormatter.format(Math.round(value));
                let html = `<div class="flex items-center">
                        <div class="med-bar" style="width: ${width}%;height:15px;margin-right:1px;"></div>
                        <div>${show}</div>
                    </div>`;
                return html;
            } else {
                return '';
            }
        }

        function table_lowFormatter (value, row) {
            if (value > 0) {
                let width = Math.round(50 * value / max_hours);
                let show = numberFormatter.format(Math.round(value));
                let html = `<div class="flex items-center">
                        <div class="low-bar" style="width: ${width}%;height:15px;margin-right:1px;"></div>
                        <div>${show}</div>
                    </div>`;
                return html;
            } else {
                return '';
            }
        }

        /**
        * Zoom in or out the report with the depth of taxonomy
        *
        * @param {number} depth
        * @return {void}
        */
        function JumpToQuestionsByDepth (depth) {
            depthQuestion = depth;

            $.ajax({
                url: "{{ route('realestate.filter-proximity-by-activity') }}",
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "survey_id": survey_id,
                    "position": JSON.stringify(options['position']),
                    "department": JSON.stringify(options['department']),
                    "group": JSON.stringify(options['group']),
                    "location": JSON.stringify(options['location']),
                    "category": JSON.stringify(options['category']),
                    "depthQuestion": depthQuestion,
                },
                dataType: 'json',
                beforeSend: function () {
                    mask_height = $('body').height();
                    $('.loading-mask').css('height', mask_height);
                    $('.loading-mask').show();
                    $('.dropdown-menu').removeClass('show');
                },
                success: function (res) {
                    if (res.rows == 404) {
                        Toast.fire({
                            icon: 'error',
                            title: 'No more record.'
                        });
                    } else {
                        rows = res.rows;
                        $tableContainer = $('.tableContainer');

                        strHtml = ` <table id="proximityActivityTable" class="table table-bordered table-sm">
                                        <thead>
                                            <tr>
                                                <th colspan="${depthQuestion}"></th>
                                                <th style="border-right: 1px solid;" colspan="3" class="text-center">Hours</th>
                                                <th colspan="3" class="text-center">RSF</th>
                                            </tr>
                                            <tr>`;

                        for (let i = 0; i < depthQuestion; i++) {
                            strHtml += `<th style="border-bottom: none;padding-top: 20px;">${res.thAry[i]}</th>`;
                        }

                        strHtml += `<th style="border-bottom: none;">High</th>
                                    <th style="border-bottom: none;">Med</th>
                                    <th style="border-bottom: none;border-right:1px solid #000;">Low</th>
                                    <th style="border-bottom: none;">High</th>
                                    <th style="border-bottom: none;">Med</th>
                                    <th style="border-bottom: none;">Low</th>
                                </tr>
                                <tr style="height:20px;">`;

                        for (let i = 0; i < depthQuestion; i++) {
                            strHtml += `<th class="jump-th" style="border-top: none;">
                                            <div class="flex justify-center jump-btn">`;
                            if (i == depthQuestion - 1) {
                                strHtml += `<svg onclick="JumpToQuestionsByDepth(${i + 2});" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 1024 1024"><path d="M328 544h152v152c0 4.4 3.6 8 8 8h48c4.4 0 8-3.6 8-8V544h152c4.4 0 8-3.6 8-8v-48c0-4.4-3.6-8-8-8H544V328c0-4.4-3.6-8-8-8h-48c-4.4 0-8 3.6-8 8v152H328c-4.4 0-8 3.6-8 8v48c0 4.4 3.6 8 8 8z" fill="currentColor"/><path d="M880 112H144c-17.7 0-32 14.3-32 32v736c0 17.7 14.3 32 32 32h736c17.7 0 32-14.3 32-32V144c0-17.7-14.3-32-32-32zm-40 728H184V184h656v656z" fill="currentColor"/></svg>`;
                            } else {
                                strHtml += `<svg onclick="JumpToQuestionsByDepth(${i + 1});" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 1024 1024"><path d="M328 544h368c4.4 0 8-3.6 8-8v-48c0-4.4-3.6-8-8-8H328c-4.4 0-8 3.6-8 8v48c0 4.4 3.6 8 8 8z" fill="currentColor"/><path d="M880 112H144c-17.7 0-32 14.3-32 32v736c0 17.7 14.3 32 32 32h736c17.7 0 32-14.3 32-32V144c0-17.7-14.3-32-32-32zm-40 728H184V184h656v656z" fill="currentColor"/></svg>`;
                            }
                            strHtml += `</div>
                                    </th>`;
                        }

                        strHtml += `<th colspan="6" style="border-top:none;"></th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">`;                        
                        
                        for (let i in rows) {
                            strHtml += `<tr>`;
                            questionDescAry = rows[i].question_desc.split("..");
                            for (j = 0; j < depthQuestion ; j++) {
                                strHtml += `<td class="questionDescTD${j}" data-option="${questionDescAry[0]}" title="${questionDescAry[j]}">${questionDescAry[j]}</td>`;
                            } 
                            strHtml += `<td>`;
                            if (rows[i].high_hours > 0) {
                                strHtml += `<div class="flex items-center">
                                                <div class="high-bar" style="width: ${60 * rows[i].high_hours / res.max_high_hours}%;height:15px;margin-right:1px;"></div>
                                                <div class="text-high">${numberFormatter.format(Math.round(rows[i].high_hours))}</div>
                                            </div>`;
                            }
                            strHtml += `</td>`;
                            strHtml += `<td>`;
                            if (rows[i].med_hours > 0) {
                                strHtml += `<div class="flex items-center">
                                                <div class="med-bar" style="width: ${60 * rows[i].med_hours / res.max_med_hours}%;height:15px;margin-right:1px;"></div>
                                                <div class="text-med">${numberFormatter.format(Math.round(rows[i].med_hours))}</div>
                                            </div>`;
                            }
                            strHtml += `</td>`;
                            strHtml += `<td style="border-right:1px solid #000;">`;
                            if (rows[i].low_hours > 0) {
                                strHtml += `<div class="flex items-center">
                                                <div class="low-bar" style="width: ${60 * rows[i].low_hours / res.max_low_hours}%;height:15px;margin-right:1px;"></div>
                                                <div class="text-low">${numberFormatter.format(Math.round(rows[i].low_hours))}</div>
                                            </div>`;
                            }
                            strHtml += `</td>`;
                            strHtml += `<td>`;
                            if (rows[i].high_rsf > 0) {
                                strHtml += `<div class="flex items-center">
                                                <div class="high-bar" style="width: ${60 * rows[i].high_rsf / res.max_high_rsf}%;height:15px;margin-right:1px;"></div>
                                                <div class="text-high">$${numberFormatter.format(Math.round(rows[i].high_rsf))}</div>
                                            </div>`;
                            }
                            strHtml += `</td>`;
                            strHtml += `<td>`;
                            if (rows[i].med_rsf > 0) {
                                strHtml += `<div class="flex items-center">
                                                <div class="med-bar" style="width: ${60 * rows[i].med_rsf / res.max_med_rsf}%;height:15px;margin-right:1px;"></div>
                                                <div class="text-med">$${numberFormatter.format(Math.round(rows[i].med_rsf))}</div>
                                            </div>`;
                            }
                            strHtml += `</td>`;
                            strHtml += `<td>`;
                            if (rows[i].low_rsf > 0) {
                                strHtml += `<div class="flex items-center">
                                                <div class="low-bar" style="width: ${60 * rows[i].low_rsf / res.max_low_rsf}%;height:15px;margin-right:1px;"></div>
                                                <div class="text-low">$${numberFormatter.format(Math.round(rows[i].low_rsf))}</div>
                                            </div>`;
                            }
                            strHtml += `</td>`;
                        }

                        strHtml += `</tbody>
                                </table>`;

                        $tableContainer.html(strHtml);

                        for (let i = 0; i < depthQuestion; i++) {
                            var span = 1;
                            var prevTD = "";
                            var prevTDVal = "";
                            var prevTDOption = "";
                            $(`td.questionDescTD${i}`).each(function() { 
                                var $this = $(this);
                                if ($this.text() == prevTDVal && $this.attr('data-option') == prevTDOption) { // check value of previous td text
                                    span++;
                                    if (prevTD != "") {
                                        prevTD.attr("rowspan", span); // add attribute to previous td
                                        $this.remove(); // remove current td
                                    }
                                } else {
                                    prevTD     = $this; // store current td 
                                    prevTDVal  = $this.text();
                                    prevTDOption  = $this.attr('data-option');
                                    span       = 1;
                                }
                            });
                        }

                    }

                    $('.loading-mask').hide();
                },
                error: function(request, error) {
                    alert("Request: " + JSON.stringify(request));
                }
            });
        }
    </script>

@endsection
