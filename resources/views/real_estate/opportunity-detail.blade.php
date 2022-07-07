@extends('layouts.reports')
@section('content')
<style>

    .virtual-tr{
        background-color: #ffd700;
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


<p><strong>Opportunity Detail</strong></p>
<p>This report provides you with a detailed view of the potential savings available if activities are
moved from their current (High) location to an available alternative.</p>

<p>When you first enter this report, you will see the RSF Rates for each location. Please see the
section titled ‘Location RSF Rates’ if you need clarification with what you are viewing. The
default view is unfiltered, however, you may change this by using the set of filters at the top of
the page. You may sort by Position, Department, Group, Location, or Proximity Factor.</p>

<p>Below the RSF rates for each location, you will see an expansive dataset that is colorized. Please
note that activities with a <strong>high proximity value are colored red</strong>, activities with a<strong> medium
proximity value are colored orange</strong>, and activities with a <strong>low proximity value are colored
blue</strong>. You will see that the first set of uncolored columns shows classifications by both Legal
Services, as well as Support Activities (below legal services). Next to those, you will see the
colored chart.</p>

<p>The first three rows of this chart tell you the proximity factor of each activity (whether it is high,
medium, or low), the amount of hours that employees have spent in each activity, and the yearly
RSF Cost of each activity.</p>
<img src="{{asset('imgs/user-guide/image-047.png')}}" alt="img">
<p>The second set of three rows shows you the potential savings that are available if a specific
activity is moved to a lower cost location. If the activity you are viewing has potential savings,
they will be shown in this section of the report. Please note that not all activities will have
numbers listed.</p>
<img src="{{asset('imgs/user-guide/image-048.png')}}" alt="img">
<p>You will also notice that in this section of the report, you can filter the potential savings based on
which location you will move an activity to. The default is set to ‘Adjacent”, which is a
medium-cost proximity factor; however you can also view the report with a ‘Regional’ or
‘Other’ proximity factor view. To change this setting, simply click on the tab listed ‘Adjacent’ as
shown above, and you will be able to select another option.</p>

      </div> 



<div class="container-fluid px-3 hideinhelppdf">
    <div class="flex justify-between items-center cont-mtitle  mb-4">
        <h1 class="text-survey ">Opportunity Detail Report / {{ $survey->survey_name }}</h1>
        <div class="d-flex py-0 py-md-2">
            @if (\Auth::check() && \Auth::user()->hasPermission('surveyExport', $survey))                
                <button class="revnor-btn mr-1" id="pdfBtn">Download PDF</button>                 
            @endif
            <button type="button" class="helpguidebtn mx-1" data-toggle="modal" data-target="#helpdetasurvey"></button>
        </div> 
    </div>
    <div id="individualContent">
        <div class="first_part"> 
            @include('real_estate.partials.opportunity-detail-filter')
        </div>
        <link rel="stylesheet" href="{{ asset('css/report-additional-style.css') }}">
        <div class="row second_part flex items-center justify-center table-txtmid table-responsive" style="padding:20px 0;border-top:1px solid #dfdfdf;">
            <table id="locationRatesTable" class="table table-sm table-striped "> 
                <thead>
                    <tr>
                        <th class="text-right"></th>
                        <th class="text-right">Current</th>
                        <th class="text-right">Adjacent</th>
                        <th class="text-right">Regional</th>
                        <th class="text-right">Other</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data['locationRates'] as $location)
                        <tr>
                            <td class="text-right"><b>{{ $location->location }}</b></td>
                            <td class="text-right">${{ number_format($location->location_Current, 2) }}</td>
                            <td class="text-right">${{ number_format($location->location_Adjacent, 2) }}</td>
                            <td class="text-right">${{ number_format($location->location_Regional, 2) }}</td>
                            <td class="text-right">${{ number_format($location->location_OTHER, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="third_part" style="padding-top: 0;border-top: 3px solid #bfbfbf;">
            <div class="tableContainer table-txtmid table-responsive">
                <table id="opportunityDetailTable" class="table table-bordered " style="margin:30px 0;">
                    <thead>
                        <tr>
                            <th style="border-bottom:none;"></th>
                            <th style="border-bottom:none;"></th>
                            <th class="text-center border-solid" colspan="3">Current Cost</th>
                            <th class="text-center" colspan="3">Potential Savings</th>
                        </tr>
                        <tr>
                            <th class="jump-th">
                                <div class="flex justify-center jump-btn">
                                    
                                </div>
                            </th>
                            <th class="jump-th">                                
                                <div class="flex justify-center jump-btn">
                                   
                                </div>
                            </th>
                            <th class="text-center" scope="col">Proximity Factor</th>
                            <th class="text-center" scope="col">Hours</th>
                            <th class="text-center" scope="col">RSF Cost(Current)</th>
                            <th class="text-center" scope="col">
                                <div class="">
                                    <span class="">RSF Cost</span>
                                    <select class="custom-select custom-select-sm" name="rsf_cost_sort" id="rsf_cost_sort" style="width:auto;">
                                        <option value="Adjacent">Adjacent</option>
                                        <option value="Regional">Regional</option>
                                        <option value="OTHER">Other</option>
                                    </select>
                                </div>
                            </th>
                            <th class="text-center" scope="col">Variance</th>
                            <th class="text-center" scope="col">Percentage</th>
                        </tr>
                        <tr>
                            <th class="text-right" colspan="2">Grand Total</th>
                            <th class="text-right"></th>
                            <th class="text-center">{{ number_format($data['total_hours']) }}</th>
                            <th class="text-center">{{ number_format($data['total_rsf_cost']['current']) }}</th>
                            <th class="text-center">
                                <span class="text-Adjacent">${{ number_format($data['total_rsf_cost']['adjacent']) }}</span>
                                <span class="text-Regional">${{ number_format($data['total_rsf_cost']['regional']) }}</span>
                                <span class="text-OTHER">${{ number_format($data['total_rsf_cost']['other']) }}</span>
                            </th>
                            <th class="text-center">
                                <span class="text-Adjacent">${{ number_format($data['total_rsf_cost']['variance_adjacent']) }}</span>
                                <span class="text-Regional">${{ number_format($data['total_rsf_cost']['variance_regional']) }}</span>
                                <span class="text-OTHER">${{ number_format($data['total_rsf_cost']['variance_other']) }}</span>
                            </th>
                            <th class="text-center">
                                <span class="text-Adjacent">{{ $data['total_rsf_cost']['current'] > 0 ?number_format(100 * ($data['total_rsf_cost']['variance_adjacent']) / $data['total_rsf_cost']['current']) : 0 }}%</span>
                                <span class="text-Regional">{{ $data['total_rsf_cost']['current'] > 0 ?number_format(100 * ($data['total_rsf_cost']['variance_regional']) / $data['total_rsf_cost']['current']) : 0 }}%</span>
                                <span class="text-OTHER">{{ $data['total_rsf_cost']['current'] > 0 ?number_format(100 * ($data['total_rsf_cost']['variance_other']) / $data['total_rsf_cost']['current']) : 0 }}%</span>
                            </th>
                        </tr>

                    </thead>
                    <tbody>
                        @php  $firstQr = ''; @endphp
                        @foreach ($data['rows'] as $row)
                            @php
                                $questionDescAry = explode("..", $row['question_desc']);
                                $prox_desc = 'High';
                                $tr_color = 'high-tr';
                                switch ($row['proximity_factor']) {
                                    case 1:
                                        $prox_desc = 'Low';
                                        $tr_color = 'low-tr';
                                        break;
                                    
                                    case 2:
                                        $prox_desc = 'Med';
                                        $tr_color = 'med-tr';
                                        break;
                                    
                                    case 3:
                                        $prox_desc = 'High';
                                        $tr_color = 'high-tr';
                                        break;

                                    case 4:
                                        $prox_desc = 'Virtual';
                                        $tr_color = 'virtual-tr';
                                        break;
                                    
                                    default:
                                        # code...
                                        break;
                                }
                            @endphp
                            @if($firstQr != $questionDescAry[0])
                                @php
                                    $firstQr =  $questionDescAry[0] ;
                                @endphp
                                <tr>
                                    <td  title="{{ $questionDescAry[0] }}"><strong>{{ $questionDescAry[0] }}</strong></td>
                                </tr>
                            @endif
                            <tr>
                                @foreach ($questionDescAry as $i => $question_desc)
                                    @if($i != 0)
                                        <td class="questionDescTD{{ $i }}" title="{{ $question_desc }}">{{ $question_desc }}</td>
                                    @endif
                                @endforeach
                                <td class="{{ $tr_color }} text-center content-td">{{ $prox_desc }}</td>
                                <td class="{{ $tr_color }} text-center content-td">{{ number_format($row['hours']) }}</td>
                                <td class="{{ $tr_color }} text-center content-td">{{ number_format($row['rsf_cost_current']) }}</td>
                                <td class="{{ $tr_color }} text-center content-td">
                                    @if ($row['rsf_cost_adjacent'] > 0)
                                    <span class="text-Adjacent">
                                        ${{ number_format($row['rsf_cost_adjacent']) }}
                                    </span>
                                    @endif
                                    @if ($row['rsf_cost_regional'] > 0)
                                    <span class="text-Regional">
                                        ${{ number_format($row['rsf_cost_regional']) }}                                        
                                    </span>
                                    @endif
                                    @if ($row['rsf_cost_other'] > 0)
                                    <span class="text-OTHER">
                                        ${{ number_format($row['rsf_cost_other']) }}                                        
                                    </span>
                                    @endif
                                </td>
                                <td class="{{ $tr_color }} text-center content-td">
                                    @if ($row['rsf_cost_adjacent'] > 0)
                                    <span class="text-Adjacent">${{ number_format($row['variance_adjacent']) }}</span>                                        
                                    @endif
                                    @if ($row['rsf_cost_regional'] > 0)
                                    <span class="text-Regional">${{ number_format($row['variance_regional']) }}</span>                                        
                                    @endif
                                    @if ($row['rsf_cost_other'] > 0)
                                    <span class="text-OTHER">${{ number_format($row['variance_other']) }}</span>                                        
                                    @endif
                                </td>
                                <td class="{{ $tr_color }} text-center content-td">
                                    @if ($row['rsf_cost_adjacent'] > 0)
                                    <span class="text-Adjacent">{{ number_format(100 * ($row['variance_adjacent']) / $row['rsf_cost_current']) }}%</span>                                        
                                    @endif
                                    @if ($row['rsf_cost_regional'] > 0)                                        
                                    <span class="text-Regional">{{ number_format(100 * ($row['variance_regional']) / $row['rsf_cost_current']) }}%</span>
                                    @endif
                                    @if ($row['rsf_cost_other'] > 0)                                        
                                    <span class="text-OTHER">{{ number_format(100 * ($row['variance_other']) / $row['rsf_cost_current']) }}%</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
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
            <p class="text-phead">Opportunity Detail Report / {{ $survey->survey_name }}</p> 
            <p class="redtext-phead">Confidential</p>
            {{-- <img  src="{{asset('imgs/logo-pdfhead.png')}}">   --}}
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


<p><strong>Opportunity Detail</strong></p>
<p>This report provides you with a detailed view of the potential savings available if activities are
moved from their current (High) location to an available alternative.</p>

<p>When you first enter this report, you will see the RSF Rates for each location. Please see the
section titled ‘Location RSF Rates’ if you need clarification with what you are viewing. The
default view is unfiltered, however, you may change this by using the set of filters at the top of
the page. You may sort by Position, Department, Group, Location, or Proximity Factor.</p>

<p>Below the RSF rates for each location, you will see an expansive dataset that is colorized. Please
note that activities with a <strong>high proximity value are colored red</strong>, activities with a<strong> medium
proximity value are colored orange</strong>, and activities with a <strong>low proximity value are colored
blue</strong>. You will see that the first set of uncolored columns shows classifications by both Legal
Services, as well as Support Activities (below legal services). Next to those, you will see the
colored chart.</p>

<p>The first three rows of this chart tell you the proximity factor of each activity (whether it is high,
medium, or low), the amount of hours that employees have spent in each activity, and the yearly
RSF Cost of each activity.</p>
<img src="{{asset('imgs/user-guide/image-047.png')}}" alt="img">
<p>The second set of three rows shows you the potential savings that are available if a specific
activity is moved to a lower cost location. If the activity you are viewing has potential savings,
they will be shown in this section of the report. Please note that not all activities will have
numbers listed.</p>
<img src="{{asset('imgs/user-guide/image-048.png')}}" alt="img">
<p>You will also notice that in this section of the report, you can filter the potential savings based on
which location you will move an activity to. The default is set to ‘Adjacent”, which is a
medium-cost proximity factor; however you can also view the report with a ‘Regional’ or
‘Other’ proximity factor view. To change this setting, simply click on the tab listed ‘Adjacent’ as
shown above, and you will be able to select another option.</p> 
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




        var survey_id = @php echo $data['survey'] -> survey_id; @endphp;
        var respData  = @php echo $data['resps']; @endphp;

        var imgData_1, imgData_2, imgData_3, imgData_4, copyrightData, headerData;

        let formatter = new Intl.NumberFormat('en-US', {
                        style: 'currency',
                        currency: 'USD',
                        minimumFractionDigits: 0, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
                        maximumFractionDigits: 0, // (causes 2500.99 to be printed as $2,501)
                    });

        let numberFormatter = new Intl.NumberFormat('en-US');

        // Handle the pdf button click, generate image data from the body
        $('#pdfBtn').click(function () {
            $('#generatePDFModal').modal('show');
            $('#headerDiv').show();
            $('#copyright_div').addClass('d-flex');
			$('#copyright_div').show();	  			
            source = $('#individualContent .first_part');
            html2canvas(source, {
                    scale:3
                    }).then(function(canvas) {
                        
                    imgData_1 = canvas.toDataURL("image/jpeg", 1.0);
                        
                });
            source = $('#individualContent .second_part');
            html2canvas(source, {
                    scale:3
                    }).then(function(canvas) {
                        
                    imgData_2 = canvas.toDataURL("image/jpeg", 1.0);
                        
                });
            // Copyright
            source = $('#copyright_div');
            html2canvas(source, {
                    scale:3
                    }).then(function(canvas) {
                        
                    copyrightData = canvas.toDataURL("image/jpeg", 1.0);
                        
                });
            source = $('#headerDiv');
            html2canvas(source, {
                    scale:3
                    }).then(function(canvas) {
                        
                    headerData = canvas.toDataURL("image/jpeg", 1.0);
                        
                });
            source = $('#individualContent .third_part');
            html2canvas(source, {
                onrendered: function (canvas) {
                    imgData_3 = canvas.toDataURL('image/jpeg', 1.0);
                }
            }).then(function () {
                $('#generatePDFModal .modal-body').html('Generated a PDF');
                $('#headerDiv').hide();
					$('#copyright_div').removeClass('d-flex');
			        $('#copyright_div').hide();	 				
                $('#generatePDFModal .btn').attr('disabled', false);
            });
        });

        $('.filter-secondary').change(function () {
            let depthQuestion = $('#depthQuestion').val();
            let minPercent    = $('#minPercent').val();
            let filterResp    = $('#filterResp').val();

            $.ajax({
                url: '{{ route("getAtAGlanceTableData") }}',
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "survey_id": survey_id,
                    "position": JSON.stringify(options['position']),
                    "department": JSON.stringify(options['department']),
                    "group": JSON.stringify(options['group']),
                    "category": JSON.stringify(options['category']),
                    "location": JSON.stringify(options['location']),
                    "depthQuestion": depthQuestion,
                    "minPercent": minPercent,
                    "filterResp": filterResp
                },
                dataType: 'json',
                beforeSend: function () {
                    mask_height = $('body').height();
                    $('.loading-mask').css('height', mask_height);
                    $('.loading-mask').show();
                },
                success: function (res) {
                    rows = res.ataglance_data;
                    $tableContainer = $('.tableContainer');
                    strHtml = `<table class="table table-bordered table-responsive" style="margin:30px 0;">
                                    <thead>
                                        <tr>
                                            <th></th>`;
                    for (i = 0; i <= depthQuestion; i++) {
                        strHtml += `<th></th>`;
                    }
                    strHtml +=  `           <th style="text-align: right;">Hours</th>
                                            <th style="text-align: right;">% Hours</th>
                                            <th style="text-align: right;">Cost</th>
                                        </tr>
                                        <tr>
                                            <th>Grand Total</th>`;
                    for (i = 0; i <= depthQuestion; i++) {
                        strHtml += `<th></th>`;
                    }
                    strHtml +=  `           <th style="text-align: right;">${ numberFormatter.format(res.grand_total_hours) }</th>
                                            <th></th>
                                            <th style="text-align: right;">${ formatter.format(res.grand_total_cost) }</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;
                    

                    rows.forEach(row => {
                        questionDescAry = row.question_desc.split("..");

                        strHtml += `<tr>`;
                        if (row.rowspan != 0) {
                            strHtml += `<td rowspan="${row.rowspan}" style="font-weight:bold;">${ row.option }</td>`;
                        }
                        
                        for (i = 0; i < questionDescAry.length; i++) {
                            strHtml += `<td class="questionDescTD${i}" data-option="${row.option}">${questionDescAry[i]}</td>`;
                        }
                        strHtml += `    <td style="text-align: right;">${ numberFormatter.format(row.hours) }</td>
                                        <td style="text-align: right;">${ row.percent }%</td>
                                        <td style="text-align: right;">${ formatter.format(row.cost) }</td>
                                    </tr>`;
                    });

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
                    
                    $('.loading-mask').hide();
                    setTimeout(() => {
                        $('body .questionDescTD0').parent('tr').addClass("yooooo");
                        //$('body .questionDescTD0').parent('tr').css('border-top','40px solid white');
                    }, 500);
                },
                error: function(request, error) {
                    alert("Request: " + JSON.stringify(request));
                }
            });
        });

        $('#rsf_cost_sort').change(function () {
            val = $(this).val();
            $('.text-Adjacent').hide();
            $('.text-Regional').hide();
            $('.text-OTHER').hide();
            $(`.text-${val}`).show();
        });

        $(document).ready(function () {

            setTimeout(() => {
                $('body .questionDescTD0').parent('tr').addClass("yooooo");
               // $('body .questionDescTD0').parent('tr').css('border-top','40px solid white'); 
            }, 500);
            

           

            for (let i = 0; i < 5; i++) {
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
        });

        /**
        * Export excel data
        *
        *@param {number} resp_id
        *@return {void}
        */
        function exportExcelData (resp_id) {
            let excelData;
            respData.forEach(row => {
                if (resp_id == row.resp_id) {
                    excelData = row;
                }
            });
            let compOpt = $('#compareCompensation').val();
            let sum_hours = numberFormatter.format(excelData.legal_hours + excelData.support_hours);
            let sum_comp;
            if (compOpt == '0') {
                sum_comp = formatter.format(excelData.resp_compensation);
                compOpt = 'Compensation';
            } else {
                sum_comp = formatter.format(excelData.resp_compensation + excelData.resp_compensation * excelData.resp_benefit_pct);
                compOpt = 'Compensation + Benefits';
            }
            $.ajax({
                url: "{{ route('exportParticipantAnalysisExcel') }}",
                type: 'POST',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'data': excelData,
                    'compOption': compOpt,
                    'sum_hours': sum_hours,
                    'sum_comp': sum_comp
                },
                dataType: 'json',
                beforeSend: function () {

                },
                success: function (response) {
                    location.href = response.url;
                },
                error: function (request, error) {
                    alert("Request: " + JSON.stringify(request));
                }
            });
        }

        /**
        * Generate pdf document of report
        *
        * @return {void}
        */
        function generatePDF () {
            let imgWidth = $('#individualContent .first_part').outerWidth();
            pdfdoc = new jsPDF('p', 'mm', 'a4');
            imgHeight1 = Math.round($('#individualContent .first_part').outerHeight() * 190 / imgWidth);
            y = 14;
            position = y; 
            doc_page = 1;

            /* pdfdoc.addImage(imgData_1, 'JPEG', 10, y, 190, imgHeight1);
            y += imgHeight1; */

            imgHeight2 = Math.round($('#individualContent .second_part').outerHeight() * 190 / imgWidth);
            pdfdoc.addImage(imgData_2, 'JPEG', 10, y, 190, imgHeight2);
            y += imgHeight2;

            imgHeight3 = Math.round($('#individualContent .third_part').outerHeight() * 210 / imgWidth);
            pdfdoc.addImage(imgData_3, 'JPEG', 10, y, 190, imgHeight3);
            y += imgHeight3;

            pageHeight = pdfdoc.internal.pageSize.height;
            heightLeft = y - pageHeight;

            while (heightLeft >= -10) {
                position += heightLeft - imgHeight3 + 10;
                pdfdoc.addPage();
                doc_page++;
                pdfdoc.addImage(imgData_3, 'JPEG', 10, position, 190, imgHeight3);
                heightLeft -= pageHeight;
            }

            for (i = 1; i <= doc_page; i++) {
                pdfdoc.setPage(i);
                pdfdoc.addImage(headerData, 'JPEG', 10, 0, 190, 13); 
                pdfdoc.addImage(copyrightData, 'JPEG', 10, 284.5, 190, 13); 
                pdfdoc.setTextColor(111,107,107); 
                pdfdoc.setFontSize(8); 
                pdfdoc.text('Page ' + i + ' of ' + doc_page , 169, 290, 0, 45); 
            }

            pdfdoc.save(`Opportunity Detail Report({{$data['survey']->survey_name}})`);
            $('#pdfBtn').html('Download PDF');
            $('#pdfBtn').prop('disabled', false);
            $('#generatePDFModal').modal('hide');
            $('#generatePDFModal .btn').attr('disabled', true);
        }

        /**
        * Zoom in or out the report with the depth of taxonomy
        *
        * @param {number} depth
        * @return {void}
        */
        function JumpToQuestionsByDepth (depth) {
            alert('This ois a test');
            depthQuestion = depth;

            $.ajax({
                url: "{{ route('realestate.filter-opportunity-detail') }}",
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "survey_id": survey_id,
                    "position": JSON.stringify(options['position']),
                    "department": JSON.stringify(options['department']),
                    "group": JSON.stringify(options['group']),
                    "location": JSON.stringify(options['location']),
                    "proximity": options['proximity'],
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
                    console.log(res);
                    alert('Hi /...');
                    var firstQr = '';
                    if (res == 404) {
                        Toast.fire({
                            icon: 'error',
                            title: 'No more record.'
                        });
                    } else {
                        rows = res.rows;
                        total_hours = res.total_hours;
                        total_rsf_cost = res.total_rsf_cost;

                        $tableContainer = $('.tableContainer');

                        strHtml = `<table id="opportunityDetailTable" class="table table-responsive table-bordered" style="margin:30px 0;">
                                        <thead>
                                            <tr>`;        

                        for (i = 0; i <= depthQuestion; i++) {
                            strHtml += `<th style="border-bottom:none;"></th>`;
                        }

                        strHtml += `<th class="text-center border-solid border-right" colspan="3">Current Cost</th>`;
                        strHtml += `<th class="text-center" colspan="3">Potential Savings</th>`;
                        strHtml += `<tr>`;

                        for (i = 0; i <= depthQuestion; i++) { 
                            if (i == depthQuestion) {
                                strHtml += `<th class="jump-th">
                                                <div class="flex justify-center jump-btn">
                                                    <svg onclick="JumpToQuestionsByDepth(${depthQuestion + 1});" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;cursor: pointer;" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 1024 1024"><path d="M328 544h152v152c0 4.4 3.6 8 8 8h48c4.4 0 8-3.6 8-8V544h152c4.4 0 8-3.6 8-8v-48c0-4.4-3.6-8-8-8H544V328c0-4.4-3.6-8-8-8h-48c-4.4 0-8 3.6-8 8v152H328c-4.4 0-8 3.6-8 8v48c0 4.4 3.6 8 8 8z" fill="currentColor"/><path d="M880 112H144c-17.7 0-32 14.3-32 32v736c0 17.7 14.3 32 32 32h736c17.7 0 32-14.3 32-32V144c0-17.7-14.3-32-32-32zm-40 728H184V184h656v656z" fill="currentColor"/></svg>
                                                </div>
                                            </th>`;
                            } else {
                                strHtml += `<th class="jump-th">
                                                <div class="flex justify-center jump-btn">
                                                    <svg onclick="JumpToQuestionsByDepth(${i});" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;cursor: pointer;" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 1024 1024"><path d="M328 544h368c4.4 0 8-3.6 8-8v-48c0-4.4-3.6-8-8-8H328c-4.4 0-8 3.6-8 8v48c0 4.4 3.6 8 8 8z" fill="currentColor"/><path d="M880 112H144c-17.7 0-32 14.3-32 32v736c0 17.7 14.3 32 32 32h736c17.7 0 32-14.3 32-32V144c0-17.7-14.3-32-32-32zm-40 728H184V184h656v656z" fill="currentColor"/></svg>
                                                </div>
                                            </th>`;
                            }
                        }

                        strHtml += `<th class="text-right">Proximity Factor</th>`;
                        strHtml += `<th class="text-right">Hours</th>`;
                        strHtml += `<th class="text-right border-right">RSF Cost(Current)</th>`;
                        strHtml += `<th class="text-right">
                                        <div class=" ">
                                            <span class="">RSF Cost</span> 
                                            <select class="custom-select custom-select-sm" name="rsf_cost_sort" id="rsf_cost_sort" style="width:auto;">
                                                <option value="Adjacent">Adjacent</option>
                                                <option value="Regional">Regional</option>
                                                <option value="OTHER">Other</option>
                                            </select>
                                        </div>
                                    </th>`;
                        strHtml += `<th class="text-right" scope="col">Variance</th>
                                    <th class="text-right" scope="col">Percentage</th>
                                </tr>`;

                        strHtml += 
                            `<tr>
                                <th class="text-right" colspan="${depthQuestion + 1}">Grand Total</th>
                                <th class="text-right"></th>
                                <th class="text-right">${numberFormatter.format(Math.round(total_hours))}</th>
                                <th class="text-right border-right">${numberFormatter.format(Math.round(total_rsf_cost.current))}</th>
                                <th class="text-right">
                                    <span class="text-Adjacent">${formatter.format(Math.round(total_rsf_cost.adjacent))}</span>
                                    <span class="text-Regional">${formatter.format(Math.round(total_rsf_cost.regional))}</span>
                                    <span class="text-OTHER">${formatter.format(Math.round(total_rsf_cost.other))}</span>
                                </th>
                                <th class="text-right">
                                    <span class="text-Adjacent">${formatter.format(Math.round(total_rsf_cost.variance_adjacent))}</span>
                                    <span class="text-Regional">${formatter.format(Math.round(total_rsf_cost.variance_regional))}</span>
                                    <span class="text-OTHER">${formatter.format(Math.round(total_rsf_cost.variance_other))}</span>
                                </th>
                                <th class="text-right">
                                    <span class="text-Adjacent">${numberFormatter.format(Math.round(100 * total_rsf_cost.variance_adjacent / total_rsf_cost.current))}%</span>
                                    <span class="text-Regional">${numberFormatter.format(Math.round(100 * total_rsf_cost.variance_regional / total_rsf_cost.current))}%</span>
                                    <span class="text-OTHER">${numberFormatter.format(Math.round(100 * total_rsf_cost.variance_other / total_rsf_cost.current))}%</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>`;

                        for (const i in rows) {
                            questionDescAry  = rows[i].question_desc.split("..");
                            if(firstQr != questionDescAry[0]){
                                var firstQr = questionDescAry[0] ;
                                strHtml += `<tr><td title="${questionDescAry[0]}">${questionDescAry[0]}</td></tr>`;
                            }
                            strHtml += `<tr>`;
                            for (j = 0; j < questionDescAry.length; j++) {
                                if(j != 0){
                                 strHtml += `<td class="questionDescTD${j}" title="${questionDescAry[j]}">${questionDescAry[j]}</td>`;
                                }
                            }
                            prox_desc = 'High';
                            tr_color = 'high-tr';
                            switch (rows[i].proximity_factor) {
                                case 1:
                                    prox_desc = 'Low';
                                    tr_color = 'low-tr';
                                    break;
                            
                                case 2:
                                    prox_desc = 'Med';
                                    tr_color = 'med-tr';
                                    break;
                            
                                case 3:
                                    prox_desc = 'High';
                                    tr_color = 'high-tr';
                                    break;
                            
                                default:
                                    break;
                            }
                            strHtml += `<td class="${tr_color} text-right content-td">${prox_desc}</td>
                                <td class="${tr_color} text-right content-td">${numberFormatter.format(Math.round(rows[i].hours))}</td>
                                <td class="${tr_color} border-right text-right content-td">${numberFormatter.format(Math.round(rows[i].rsf_cost_current))}</td>
                                <td class="${tr_color} text-right content-td">
                                    <span class="text-Adjacent">${rows[i].rsf_cost_adjacent > 0 ? formatter.format(Math.round(rows[i].rsf_cost_adjacent)) : ''}</span>
                                    <span class="text-Regional">${rows[i].rsf_cost_regional > 0 ? formatter.format(Math.round(rows[i].rsf_cost_regional)) : ''}</span>
                                    <span class="text-OTHER">${rows[i].rsf_cost_other > 0 ? formatter.format(Math.round(rows[i].rsf_cost_other)) : ''}</span>
                                </td>
                                <td class="${tr_color} text-right content-td">
                                    <span class="text-Adjacent">${rows[i].rsf_cost_adjacent > 0 ? formatter.format(Math.round(rows[i].variance_adjacent)) : ''}</span>
                                    <span class="text-Regional">${rows[i].rsf_cost_regional > 0 ? formatter.format(Math.round(rows[i].variance_regional)) : ''}</span>
                                    <span class="text-OTHER">${rows[i].rsf_cost_other ? formatter.format(Math.round(rows[i].variance_other)) : ''}</span>
                                </td>
                                <td class="${tr_color} text-right content-td">
                                    <span class="text-Adjacent">${rows[i].rsf_cost_adjacent > 0 ? numberFormatter.format(Math.round(100 * rows[i].variance_adjacent / rows[i].rsf_cost_current)) + '%' : ''}</span>
                                    <span class="text-Regional">${rows[i].rsf_cost_regional > 0 ? numberFormatter.format(Math.round(100 * rows[i].variance_regional / rows[i].rsf_cost_current)) + '%' : ''}</span>
                                    <span class="text-OTHER">${rows[i].rsf_cost_other > 0 ? numberFormatter.format(Math.round(100 * rows[i].variance_other / rows[i].rsf_cost_current)) + '%' : ''}</span>
                                </td>`;
                            strHtml += `</tr>`;
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
                        
                        $('#rsf_cost_sort').click(function () {
                            val = $(this).val();
                            $('.text-Adjacent').hide();
                            $('.text-Regional').hide();
                            $('.text-OTHER').hide();
                            $(`.text-${val}`).show();
                        });
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
