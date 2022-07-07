@extends('layouts.reports')
@section('content')


<div id="HelpContent" class="modal-body" style="display:none;">
<p><strong>At A Glance</strong></p>
<p>When you select ‘<strong>At A Glance</strong> ’, you are brought to a screen that is essentially a summarization
of the total Analysis. The default view is unfiltered; however, you may change this by using the
set of filters up at the top of your screen. You may sort by Position, Department, Group,
Location, and Category. To have a more specific view, you may also sort by the type of
subcategory (Activities, Classifications, Legal/Support Activities, etc.), Percentage of Hours,
and the Location that correlates to those hours.</p>
<img src="{{asset('imgs/user-guide/image-042.png')}}" alt="img">
<p>Below these filters, you will see the grand total number of hours, the percentage that those hours
equate to, and the cost of the hours to the firm. The numbers that you will see are based on
location and are unique to whichever filter you have used. For example, if you stick with the
default view (which sorts the analysis by location), you will not see any specifics, just the grand
total. If you were to sort by department, you would see specifics for various departments within
the firm. You will notice that all of the hours and cost of these activities add up to the grand total
number that you saw originally. You may choose whichever view you’d prefer to sort the
analysis by.</p> 

</div> 

 
<div class="container-fluid px-3 hideinhelppdf">  
    <div class="flex justify-between items-center cont-mtitle  mb-4"> 
        <h1 class="text-survey">Function At-A-Glance / {{ $survey->survey_name }}</h1>
        <div class="d-flex py-0 py-md-2"> 
            @if (\Auth::check() && \Auth::user()->hasPermission('surveyExport', $survey))
                {{-- <button class="btn btn-secondary" id="downloadExcel">Export to Excel</button> --}}
            @endif
            @if (\Auth::check() && \Auth::user()->hasPermission('surveyPrint', $survey))                
                <button class="revnor-btn  mr-1" id="pdfBtn">Download PDF</button>                
            @endif
            <button type="button" class="helpguidebtn mx-1" data-toggle="modal" data-target="#helpdetasurvey">         
</button>  
        </div>
    </div>
    
    <div id="individualContent">
        <div class="first_part">
            @include('analysis.partial.ataglancefilter')
        </div>
        <link rel="stylesheet" href="{{ asset('css/report-additional-style.css') }}">
        <div class="row second_part" style="padding:20px 0;border-top:1px solid #dfdfdf;">
            <div class="flex items-center justify-start atglace-tinyselect" style="font-weight: bold;">
                <span class="selectspan-txt">Show</span>
                <select class="select-tiny filter-secondary" name="depthQuestion" id="depthQuestion">
                    <option value="0">Legal | Support</option>
                    <option value="1">Classifications</option>
                    <option value="2">Substantive Areas</option>
                    <option value="3">Processes</option>
                    <option value="4" selected>Activities</option>
                </select>
                <span class="selectspan-txt">where the percent of hours is at least </span>
                <div class="input-group suffix" style="width: 100px;">
                    <input class="input-tiny filter-secondary" id="minPercent" type="number" value="15" step="1">
                    <span class="input-group-addon">%</span>
                </div>
                <span class="selectspan-txt">for each</span> 
                <select class="select-tiny filter-secondary" name="filterResp" id="filterResp">
                    <option value="0">Position</option>
                    <option value="1">Department</option>
                    <option value="2">Group</option>
                    <option value="3" selected>Location</option>
                    <option value="4">Category</option>
                    <option value="5">Participant</option> 
                </select>
            </div>
        </div>
        <div class="third_part" style="padding-top: 0;border-top: 3px solid #bfbfbf;"> 
            <div class="tableContainer at-glacetable table-txtmid">
                <table class="table table-responsive">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th style="text-align: right;">Hours</th>
                            <th style="text-align: right;">Hours %</th>
                            <th style="text-align: right;">Cost</th>
                        </tr>
                        <tr>
                            <th>Grand Total</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th style="text-align: right;">{{ number_format(round($data['grand_total_hours']), 0, '.', ',') }}</th>
                            <th></th>
                            <th style="text-align: right;">${{ number_format(round($data['grand_total_cost']), 0, '.', ',') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['ataglance_data'] as $row)
                            <tr>
                                @if ($row['rowspan'] != 0)
                                    <td rowspan="{{ $row['rowspan'] }}" style="font-weight:bold;">{{ $row['option'] }}</td>
                                @endif
                                @php
                                    $questionDescAry = explode("..", $row['question_desc']);
                                @endphp
                                @foreach ($questionDescAry as $i => $question_desc)
                                    <td class="questionDescTD{{ $i }}" data-option="{{ $row['option'] }}">{{ $question_desc }}</td>
                                @endforeach
                                <td style="text-align: right;">{{ number_format($row['hours']) }}</td>
                                <td style="text-align: right;">{{ $row['percent'] }}%</td>
                                <td style="text-align: right;">${{ number_format($row['cost']) }}</td>
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
            <p class="text-phead">Function At-A-Glance /{{ $survey->survey_name }}</p>
            <p class="redtext-phead">Confidential</p>
            {{-- <img  src="{{asset('imgs/logo-pdfhead.png')}}">   --}}
    </div>   

    <div id="pdfPreview">
        <div id="imgReport1"></div>
        <div id="imgReport2" style="background:white;"></div>
        <div id="imgReport3" style="background: white;"></div>
        <div id="copyImg"></div>
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

<p>When you select ‘<strong>At A Glance</strong> ’, you are brought to a screen that is essentially a summarization
of the total Analysis. The default view is unfiltered; however, you may change this by using the
set of filters up at the top of your screen. You may sort by Position, Department, Group,
Location, and Category. To have a more specific view, you may also sort by the type of
subcategory (Activities, Classifications, Legal/Support Activities, etc.), Percentage of Hours,
and the Location that correlates to those hours.</p>
<img src="{{asset('imgs/user-guide/image-042.png')}}" alt="img">
<p>Below these filters, you will see the grand total number of hours, the percentage that those hours
equate to, and the cost of the hours to the firm. The numbers that you will see are based on
location and are unique to whichever filter you have used. For example, if you stick with the
default view (which sorts the analysis by location), you will not see any specifics, just the grand
total. If you were to sort by department, you would see specifics for various departments within
the firm. You will notice that all of the hours and cost of these activities add up to the grand total
number that you saw originally. You may choose whichever view you’d prefer to sort the
analysis by.</p>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
            $('#pdfBtn').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Generating PDF...`);
            $('#pdfBtn').prop('disabled', true);
            $('#headerDiv').show();
			 $('#copyright_div').addClass('d-flex');
			 $('#copyright_div').show();			
           /*  source = $('#individualContent .first_part');
            html2canvas(source, {
                onrendered: function (canvas) {
                    canvas.setAttribute('id', 'imgCanvas1');
                    $('#imgReport1').append(canvas);
                }
            }); */
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
                        let imgWidth = $('#imgReport1').outerWidth();
                        //imgData_1 = document.getElementById('imgCanvas1').toDataURL('image/jpeg', 1.0);
                      //  imgData_2 = document.getElementById('imgCanvas2').toDataURL('image/jpeg', 1.0);
                        imgData_3 = document.getElementById('imgCanvas3').toDataURL('image/jpeg', 1.0);
                        copyrightData = document.getElementById('copyrightCanvas').toDataURL('image/jpeg', 1.0);
                        headerData = document.getElementById('headerCanvas').toDataURL('image/jpeg', 1.0);
                        pdfdoc = new jsPDF('p', 'mm', 'a4');
                        //headerDataHeight1 = Math.round($('#headerDiv').outerHeight() * 190 / imgWidth);
                         imgHeight1 = Math.round($('#imgReport1').outerHeight() * 190 / imgWidth);
                        y = 14;
                        position = y;
                        doc_page = 1;

                        /* pdfdoc.addImage(imgData_1, 'JPEG', 10, y, 190, imgHeight1);
                        y += imgHeight1; */

                       /*  imgHeight2 = Math.round($('#imgReport2').outerHeight() * 190 / imgWidth);
                        pdfdoc.addImage(imgData_2, 'JPEG', 10, y, 190, imgHeight2);
                        y += imgHeight2; */

                        imgHeight3 = Math.round($('#imgReport3').outerHeight() * 190 / imgWidth);
                        pdfdoc.addImage(imgData_3, 'JPEG', 10, y, 190, imgHeight3);
                        y += imgHeight3;

                        //imgHeight4 = Math.round($('#copyImg').outerHeight() * 190 / imgWidth);
                        //pdfdoc.addImage(copyrightData, 'JPEG', 10, y, 190, imgHeight4);
                       // y += imgHeight4;

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
                            pdfdoc.setTextColor(111,107,107); 
                pdfdoc.setFontSize(8); 
                pdfdoc.text('Page ' + i + ' of ' + (doc_page-1) , 168, 290, 0, 45);
                        }

                        pdfdoc.save(`Function At-A-Glance({{$data['survey']->survey_name}})`);
                        $('#imgCanvas1').remove();
                        $('#imgCanvas2').remove();
                        $('#imgCanvas3').remove();
                        $('#copyrightCanvas').remove();
                        $('#headerCanvas').remove();
                        $('#pdfBtn').html('Download PDF');
                        $('#headerDiv').hide();
						$('#copyright_div').removeClass('d-flex');
						$('#copyright_div').hide(); 						
                        $('#pdfBtn').prop('disabled', false);
                    }, 1000);
                }
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
                    strHtml = `<table class="table table-responsive" style="margin: 20px 0;">
                                    <thead>
                                        <tr>
                                            <th></th>`;
                    for (i = 0; i <= depthQuestion; i++) {
                        strHtml += `<th></th>`;
                    }
                    strHtml +=  `           <th style="text-align: right;">Hours</th>
                                            <th style="text-align: right;">Hours %</th>
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
                        strHtml += `<tr>`;
                        if (row.rowspan != 0) {
                            strHtml += `<td rowspan="${row.rowspan}" style="font-weight:bold;">${ row.option }</td>`;
                        }
                        questionDescAry = row.question_desc.split("..");
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
                },
                error: function(request, error) {
                    alert("Request: " + JSON.stringify(request));
                }
            });
        });

        $('#downloadExcel').click(function () {
            let depthQuestion = $('#depthQuestion').val();
            let minPercent    = $('#minPercent').val();
            let filterResp    = $('#filterResp').val();

            $.ajax({
                url: '{{ route("getAtAGlanceExcelExport") }}',
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
    </script>

@endsection
