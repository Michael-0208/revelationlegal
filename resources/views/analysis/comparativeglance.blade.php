@extends('layouts.reports')
@section('content')

<div id="HelpContent" class="modal-body" style="display:none;">
<p><strong>Comparative Glance (Beta)</strong></p>
<p>When you open Comparative Glance (Beta), you will see a dataset that looks similar to the one
you saw in ‘At A Glance’. The default view is unfiltered; however, you may change this by using
the set of filters up at the top of your screen. You may sort by Position, Department, Group,
Location, and Category. Below this first set of filters is a second set of filters, which will allow
for you to view an even more specific analysis, as shown below:</p>
<img src="{{asset('imgs/user-guide/image-043.png')}}" alt="img">
<p>Should you continue to view the analysis with the default view, you will come to a dataset below
these filters that will show you the activities for a location, whether a specific activity is a
Support Activity or a Legal Activity, and what type of support or legal activity it is. Further on
the right side of the dataset towards the top, you will see the hours that are spent in each activity,
the cost of those hours and the percentage that those hours equate to, and the percentage of hours
in total. If you would like to view more specific data to see how the grand totals are made up,
you may scroll downwards on the graph. Numbers and percentages will vary depending on
activity.</p>

</div> 

 
<div class="container-fluid px-3 px-md-4  hideinhelppdf">  
    <div class="flex justify-between items-center cont-mtitle  mb-4"> 
        <h1 class="text-survey">Comparative Function At-A-Glance / {{ $survey->survey_name }}</h1>
        <div class="d-flex py-0 py-md-2"> 
            @if (\Auth::check() && \Auth::user()->hasPermission('surveyExport', $survey))
                {{-- <button class="btn btn-secondary" id="downloadExcel">Export to Excel</button> --}}
            @endif
            @if (\Auth::check() && \Auth::user()->hasPermission('surveyPrint', $survey))                
                <button class="revnor-btn mr-1" id="pdfBtn">Download PDF</button>                
            @endif
            <button type="button" class="helpguidebtn mx-1" data-toggle="modal" data-target="#helpdetasurvey"></button>  
        </div> 
    </div>
    <div id="individualContent">  
        <div class="first_part">
            @include('analysis.partial.comparativeglancefilter')
        </div>
        <link rel="stylesheet" href="{{ asset('css/report-additional-style.css') }}">
        <div class="row second_part" style="padding:20px 0;border-top:1px solid #dfdfdf;">
            <div class="flex items-center justify-start atglace-tinyselect" style="font-weight: bold;">
                <span class="selectspan-txt">Show </span>
                <select class="select-tiny filter-secondary" name="filterRespPrimary" id="filterRespPrimary">
                    <option value="0">Position</option>
                    <option value="1">Department</option>
                    <option value="2">Group</option>
                    <option value="3" selected>Location</option>
                    <option value="4">Category</option>
                    <option value="5">Participant</option>
                </select>
                <span class="selectspan-txt">by</span>
                <select class="select-tiny filter-secondary" name="filterRespSecondary" id="filterRespSecondary">
                    <option value="0" selected>Position</option>
                    <option value="1">Department</option>
                    <option value="2">Group</option>
                    <option value="3">Location</option>
                    <option value="4">Category</option>
                    <option value="5">Participant</option>
                </select>
                <span class="selectspan-txt">where the percent of hours is at least </span>
                <div class="input-group suffix" style="width: 100px;">
                    <input class="input-tiny filter-secondary" id="minPercent" type="number" value="15" step="1">
                    <span class="input-group-addon">%</span>
                </div>
                <span class="selectspan-txt">for each </span>
                <select class="select-tiny filter-secondary" name="depthQuestion" id="depthQuestion">
                    <option value="0">Legal | Support</option>
                    <option value="1">Classifications</option>
                    <option value="2">Substantive Areas</option>
                    <option value="3">Processes</option>
                    <option value="4" selected>Activities</option>
                </select>
               <span class="selectspan-txt text-filterResp">by Location</span>
            </div>
        </div>
        <div class="third_part" style="padding-top: 0;border-top: 3px solid #bfbfbf;">
            <div class="tableContainer table-txtmid">
                <table class="table table-responsive"> 
                    <thead>
                        <tr>
                            <th style="min-width: 130px;"></th>
                            <th style="min-width: 140px;"></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th style="min-width: 150px;">Hours</th>
                            <th style="min-width: 115px;">Cost</th>
                            <th style="min-width: 170px;">% Hours (per Activities)</th>
                            <th style="min-width: 120px;">% Hours (Total)</th>
                        </tr>
                        <tr>
                            <th>Grand Total</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th style="">{{ number_format(round($data['grand_total_hours']), 0, '.', ',') }}</th>
                            <th style="">${{ number_format(round($data['grand_total_cost']), 0, '.', ',') }}</th>
                            <th></th>
                            <th style="">100%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['ataglance_data'] as $row)
                            <tr>
                                @if ($row['rowspan'] != 0)
                                    <td rowspan="{{ $row['rowspan'] }}" style="font-weight:bold;">{{ $row['option'] }}</td>
                                @endif
                                @if ($row['rowspan_secondary'] != 0)
                                    <td rowspan="{{ $row['rowspan_secondary'] }}" style="font-weight: bold;">{{ $row['sub_option'] }}</td>
                                @endif
                                @php
                                    $questionDescAry = explode("..", $row['question_desc']);
                                    $total_hoursPercent = 100 * $row['hours'] / $data['grand_total_hours'];
                                    if ($total_hoursPercent < 1 && $total_hoursPercent != 0) {
                                        $total_hoursPercent = round((float) $total_hoursPercent, 2);
                                    } else {
                                        $total_hoursPercent = round($total_hoursPercent, 2);
                                    }
                                @endphp
                                @foreach ($questionDescAry as $i => $question_desc)
                                    <td class="questionDescTD{{ $i }}" data-suboption="{{ $row['sub_option'] }}">{{ $question_desc }}</td>
                                @endforeach
                                <td style="">{{ number_format($row['hours']) }}</td>
                                <td style="">${{ number_format($row['cost']) }}</td>
                                <td style="">{{ $row['percent'] }}%</td>
                                <td style="">{{ $total_hoursPercent }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    

    <div id="pdfPreview">
        <div id="imgReport1"></div>
        <div id="imgReport2" style="background:white;"></div>
        <div id="imgReport3" style="background: white;"></div>
        <div id="copyImg"></div>
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
            <p class="text-phead">Comparative Function At-A-Glance / {{ $survey->survey_name }}</p>
            <p class="redtext-phead">Confidential</p>
            {{-- <img  src="{{asset('imgs/logo-pdfhead.png')}}">   --}}
    </div>  

    <div class="loading-mask"></div> 
    
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
<p>When you open Comparative Glance (Beta), you will see a dataset that looks similar to the one
you saw in ‘At A Glance’. The default view is unfiltered; however, you may change this by using
the set of filters up at the top of your screen. You may sort by Position, Department, Group,
Location, and Category. Below this first set of filters is a second set of filters, which will allow
for you to view an even more specific analysis, as shown below:</p>
<img src="{{asset('imgs/user-guide/image-043.png')}}" alt="img">
<p>Should you continue to view the analysis with the default view, you will come to a dataset below
these filters that will show you the activities for a location, whether a specific activity is a
Support Activity or a Legal Activity, and what type of support or legal activity it is. Further on
the right side of the dataset towards the top, you will see the hours that are spent in each activity,
the cost of those hours and the percentage that those hours equate to, and the percentage of hours
in total. If you would like to view more specific data to see how the grand totals are made up,
you may scroll downwards on the graph. Numbers and percentages will vary depending on
activity.</p> 
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>
</div>  
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
        var respData  = @php echo $data['resps']; @endphp;

        let formatter = new Intl.NumberFormat('en-US', {
                        style: 'currency',
                        currency: 'USD',
                        minimumFractionDigits: 0, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
                        maximumFractionDigits: 0, // (causes 2500.99 to be printed as $2,501)
                    });

        let numberFormatter = new Intl.NumberFormat('en-US');

        // Handle the pdf button click, generate image data from the body
        $('#pdfBtn').click(function () {
            $('#headerDiv').show();
			 $('#copyright_div').addClass('d-flex');
			 $('#copyright_div').show();
            $('#pdfBtn').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Generating PDF...`);
            $('#pdfBtn').prop('disabled', true);
            source = $('#individualContent .first_part');
            html2canvas(source, {
                onrendered: function (canvas) {
                    canvas.setAttribute('id', 'imgCanvas1');
                    $('#imgReport1').append(canvas);
                }
            }); 
            source = $('#individualContent .second_part');
            html2canvas(source, {
                onrendered: function (canvas) {
                    canvas.setAttribute('id', 'imgCanvas2')
                    $('#imgReport2').append(canvas);
                }
            });
            // Copyright
            source = $('#copyright_div');
            html2canvas(source, {
                onrendered: function (canvas) {
                    canvas.setAttribute('id', 'copyrightCanvas');
                    $('#copyImg').append(canvas);
                }
            });
            source = $('#headerDiv');
            html2canvas(source, {
                onrendered: function (canvas) {
                    canvas.setAttribute('id', 'headerCanvas');
                    $('#copyImg').append(canvas);
                }
            });  
            source = $('#individualContent .third_part');
            html2canvas(source, {
                onrendered: function (canvas) {
                    canvas.setAttribute('id', 'imgCanvas3')
                    $('#imgReport3').append(canvas);

                    setTimeout(() => {
                        let imgWidth = $('#imgReport3').outerWidth();
                        //imgData_1 = document.getElementById('imgCanvas1').toDataURL('image/jpeg', 1.0);
                        imgData_2 = document.getElementById('imgCanvas2').toDataURL('image/jpeg', 1.0);
                        imgData_3 = document.getElementById('imgCanvas3').toDataURL('image/jpeg', 1.0);
                        copyrightData = document.getElementById('copyrightCanvas').toDataURL('image/jpeg', 1.0);
                        headerData = document.getElementById('headerCanvas').toDataURL('image/jpeg', 1.0);
                        pdfdoc = new jsPDF('p', 'mm', 'a4');
                        headerDataHeight1 = Math.round($('#headerDiv').outerHeight() * 190 / imgWidth);
                        y = 18;
                        position = y; 
                        doc_page = 1;

                        /* pdfdoc.addImage(headerData, 'JPEG', 10, y, 190, headerDataHeight1);
                        y += headerDataHeight1;
                        imgHeight4 = Math.round($('#copyImg').outerHeight() * 190 / imgWidth);
                        pdfdoc.addImage(copyrightData, 'JPEG', 10, y, 190, imgHeight4);
                        y += imgHeight4;
                         */ 
                        
                        imgHeight2 = Math.round($('#imgReport2').outerHeight() * 190 / imgWidth);
                        pdfdoc.addImage(imgData_2, 'JPEG', 10, y, 190, imgHeight2);
                        y += imgHeight2;

                        imgHeight3 = Math.round($('#imgReport3').outerHeight() * 170 / imgWidth);
                        pdfdoc.addImage(imgData_3, 'JPEG', 10, y, 190, imgHeight3);
                        y += imgHeight3;

                        pageHeight = pdfdoc.internal.pageSize.height - 40;
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
                            pdfdoc.addImage(copyrightData, 'JPEG', 10, 282, 190, 14);
                            // pdfdoc.text('Page ' + i + ' of ' + (doc_page-1) , 8, 275, 90.916667, 52.916667, null, null, 45);
                            pdfdoc.setTextColor(111,107,107); 
                            pdfdoc.setFontSize(8); 

                            pdfdoc.text('Page ' + i + ' of ' + (doc_page-1) , 168, 287, 0, 45);  
                        }

                        pdfdoc.save(`Comparative Function At-A-Glance({{$data['survey']->survey_name}})`);
                        $('#imgCanvas1').remove();
                        $('#imgCanvas2').remove();
                        $('#imgCanvas3').remove();
                        $('#copyrightCanvas').remove();
                        $('#headerCanvas').remove();
                        $('#pdfBtn').html('Download PDF');
                        $('#pdfBtn').prop('disabled', false);
                        $('#headerDiv').hide();
  					$('#copyright_div').removeClass('d-flex');
					$('#copyright_div').hide();  						
                    }, 1000);
                }
            });
        });

        $('.filter-secondary').change(function () {
            let depthQuestion = $('#depthQuestion').val();
            let depthText     = $('#depthQuestion option:selected').text();
            let minPercent    = $('#minPercent').val();
            let filterRespPrimary    = $('#filterRespPrimary').val();
            let filterRespSecondary  = $('#filterRespSecondary').val();

            $.ajax({
                url: '{{ route("getComparativeGlanceTableData") }}',
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
                    "filterRespPrimary": filterRespPrimary,
                    "filterRespSecondary": filterRespSecondary
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
                    strHtml = `<table class="table table-responsive"> 
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th></th>`;
                    for (i = 0; i <= depthQuestion; i++) {
                        strHtml += `<th></th>`;
                    }
                    strHtml +=  `           <th style="text-align: right;width: 150px;">Hours</th>
                                            <th style="text-align: right;width: 150px;">Cost</th>       
                                            <th style="text-align: right;width: 150px;">% Hours (per ${ depthText })</th>
                                            <th style="text-align: right;width: 150px;">% Hours (Total)</th>
                                        </tr>
                                        <tr>
                                            <th>Grand Total</th>
                                            <th></th>`;
                    for (i = 0; i <= depthQuestion; i++) {
                        strHtml += `<th></th>`;
                    }
                    strHtml +=  `           <th style="text-align: right;">${ numberFormatter.format(res.grand_total_hours) }</th>
                                            <th style="text-align: right;">${ formatter.format(res.grand_total_cost) }</th>
                                            <th></th>
                                            <th style="text-align: right;">100%</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;

                    rows.forEach(row => {
                        strHtml += `<tr>`;
                        if (row.rowspan != 0) {
                            strHtml += `<td rowspan="${row.rowspan}" style="font-weight:bold;">${ row.option }</td>`;
                        }
                        if (row.rowspan_secondary != 0) {
                            strHtml += `<td rowspan="${row.rowspan_secondary}" style="font-weight:bold;">${ row.sub_option }</td>`;
                        }
                        questionDescAry = row.question_desc.split("..");
                        for (i = 0; i < questionDescAry.length; i++) {
                            strHtml += `<td class="questionDescTD${i}" data-suboption="${row.sub_option}">${questionDescAry[i]}</td>`;
                        }
                        hoursPercent = parseFloat(row.hours * 100 / res.grand_total_hours);
                        if (hoursPercent < 1 && hoursPercent != 0) {
                            hoursPercent = hoursPercent.toFixed(2);
                        } else {
                            hoursPercent = Math.round(hoursPercent);
                        }
                        strHtml += `    <td style="text-align: right;">${ numberFormatter.format(row.hours) }</td>
                                        <td style="text-align: right;">${ formatter.format(row.cost) }</td>
                                        <td style="text-align: right;">${ row.percent }%</td>
                                        <td style="text-align: right;">${ hoursPercent }%</td>
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
                            if ($this.text() == prevTDVal && $this.attr('data-suboption') == prevTDOption) { // check value of previous td text
                                span++;
                                if (prevTD != "") {
                                    prevTD.attr("rowspan", span); // add attribute to previous td
                                    $this.remove(); // remove current td
                                }
                            } else {
                                prevTD     = $this; // store current td
                                prevTDVal  = $this.text();
                                prevTDOption  = $this.attr('data-suboption');
                                span       = 1;
                            }
                        });
                    }

                    $('.loading-mask').hide();
                },
                error: function(request, error) {
                    alert("Request: " + JSON.stringify(request));
                }
            });
        });

        $('#downloadExcel').click(function () {
            let depthQuestion = $('#depthQuestion').val();
            let minPercent    = $('#minPercent').val();
            let filterRespPrimary    = $('#filterRespPrimary').val();
            let filterRespSecondary  = $('#filterRespSecondary').val();

            $.ajax({
                url: '{{ route("getComparativeGlanceExcelExport") }}',
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
                    "filterRespPrimary": filterRespPrimary,
                    "filterRespSecondary": filterRespSecondary,
                },
                dataType: 'json',
                beforeSend: function () {
                    $('#downloadExcel').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Generating Excel File...`);
                    $('#downloadExcel').prop('disabled', true);
                },
                success: function (res) {
                    location.href = res.url;
                    $('#downloadExcel').html(`Export to Excel`);
                    $('#downloadExcel').prop('disabled', false);
                },
                error: function(request, error) {
                    alert("Request: " + JSON.stringify(request));
                }
            });
        });

        $(document).ready(function () {
            for (let i = 0; i < 5; i++) {
                var span = 1;
                var prevTD = "";
                var prevTDVal = "";
                var prevTDOption = "";
                $(`td.questionDescTD${i}`).each(function() {
                    var $this = $(this);
                    if ($this.text() == prevTDVal && $this.attr('data-suboption') == prevTDOption) { // check value of previous td text
                        span++;
                        if (prevTD != "") {
                            prevTD.attr("rowspan", span); // add attribute to previous td
                            $this.remove(); // remove current td
                        }
                    } else {
                        prevTD     = $this; // store current td
                        prevTDVal  = $this.text();
                        prevTDOption  = $this.attr('data-suboption');
                        span       = 1;
                    }
                });
            }
        });
    </script>

@endsection
