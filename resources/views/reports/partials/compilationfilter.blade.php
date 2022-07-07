<link rel="stylesheet" href="{{ asset('css/filter.css') }}">
<div class="text-ns my-2">
    Show utilization for the following personnel:
</div>
<div class="flex justify-between items-center" id="reportsupfilters"> 
    <div id="filter-position" class="flex-1">
        <div class="text-ns">Position</div> 
        <div class="button-group">
            <button type="button" class=" dropdown-toggle filter-btn" data-toggle="dropdown">
                <span class="filter-caption">All</span> <span class="caret"></span>
            </button>
            <ul class="dropdown-menu scrollable-menu" role="menu">
                <li><a href="#" class="small" data-value="all" data-field="position" tabIndex="-1"><input type="checkbox" checked/>&nbsp;<span>Select All</span></a></li>
                @foreach ($data['position'] as $position)
                    <li><a href="#" class="small" data-value="{{ $position }}" data-field="position" tabIndex="-1"><input type="checkbox"/>&nbsp;<span>{{ $position }}</span></a></li>
                @endforeach
            </ul>
        </div>
    </div>
    <div id="filter-department" class="flex-1">
        <div class="text-ns">Department</div>
        <div class="button-group">
            <button type="button" class=" dropdown-toggle filter-btn" data-toggle="dropdown">
                <span class="filter-caption">All</span> <span class="caret"></span>
            </button>
            <ul class="dropdown-menu scrollable-menu" role="menu">
                <li><a href="#" class="small" data-value="all" data-field="department" tabIndex="-1"><input type="checkbox" checked/>&nbsp;<span>Deselect All</span></a></li>
                @foreach ($data['department'] as $department)
                    <li><a href="#" class="small" data-value="{{ $department }}" data-field="department" tabIndex="-1"><input type="checkbox" checked/>&nbsp;<span>{{ $department }}</span></a></li>
                @endforeach
            </ul>
        </div>
    </div>
    <div id="filter-group" class="flex-1">
        <div class="text-ns">Group</div>
        <div class="button-group">
            <button type="button" class=" dropdown-toggle filter-btn" data-toggle="dropdown">
                <span class="filter-caption">All</span> <span class="caret"></span>
            </button>
            <ul class="dropdown-menu scrollable-menu" role="menu">
                <li><a href="#" class="small" data-value="all" data-field="group" tabIndex="-1"><input type="checkbox" checked/>&nbsp;<span>Deselect All</span></a></li>
                @foreach ($data['group'] as $group)
                    <li><a href="#" class="small" data-value="{{ $group }}" data-field="group" tabIndex="-1"><input type="checkbox" checked/>&nbsp;<span>{{ $group }}</span></a></li>
                @endforeach
            </ul>
        </div>
    </div>
    <div id="filter-location" class="flex-1">
        <div class="text-ns">Location</div>
        <div class="button-group">
            <button type="button" class=" dropdown-toggle filter-btn" data-toggle="dropdown">
                <span class="filter-caption">All</span> <span class="caret"></span>
            </button>
            <ul class="dropdown-menu scrollable-menu" role="menu">
                <li><a href="#" class="small" data-value="all" data-field="location" tabIndex="-1"><input type="checkbox" checked/>&nbsp;<span>Deselect All</span></a></li>
                @foreach ($data['location'] as $location)
                    <li><a href="#" class="small" data-value="{{ $location }}" data-field="location" tabIndex="-1"><input type="checkbox" checked/>&nbsp;<span>{{ $location }}</span></a></li>
                @endforeach
            </ul>
        </div>
    </div>
    <div id="filter-category" class="flex-1">
        <div class="text-ns">Category</div>
        <div class="button-group">
            <button type="button" class=" dropdown-toggle filter-btn" data-toggle="dropdown">
                <span class="filter-caption">All</span> <span class="caret"></span>
            </button>
            <ul class="dropdown-menu scrollable-menu" role="menu">
                <li><a href="#" class="small" data-value="all" data-field="category" tabIndex="-1"><input type="checkbox" checked/>&nbsp;<span>Deselect All</span></a></li>
                @foreach ($data['category'] as $category)
                    <li><a href="#" class="small" data-value="{{ $category }}" data-field="category" tabIndex="-1"><input type="checkbox" checked/>&nbsp;<span>{{ $category }}</span></a></li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<script>
    // Define filter variables
    var origin_options = [];
    origin_options['position'] = @json($data['position']);
    origin_options['position'].push('all');
    origin_options['department'] = @json($data['department']);
    origin_options['department'].push('all');
    origin_options['group'] =  @json($data['group']);
    origin_options['group'].push('all');
    origin_options['location'] = @json($data['location']);
    origin_options['location'].push('all');
    origin_options['category'] = @json($data['category']);
    origin_options['category'].push('all');
    var options = [];
    options['position'] = @json($data['position']);
    options['position'].push('all');
    options['department'] = @json($data['department']);
    options['department'].push('all');
    options['group'] =  @json($data['group']);
    options['group'].push('all');
    options['location'] = @json($data['location']);
    options['location'].push('all');
    options['category'] = @json($data['category']);
    options['category'].push('all');


    function reverseString(str) {
        return str.split("").reverse().join("");
    }


    function addComma(num) {
        const emptyStr = '';
        const group_regex = /\d{3}/g;

        // delete extra comma by regex replace.
        const trimComma = str => str.replace(/^[,]+|[,]+$/g, emptyStr)


        const str = num + emptyStr;
        const [integer, decimal] = str.split('.')

        const conversed = reverseString(integer);

        const grouped = trimComma(reverseString(
            conversed.replace(/\d{3}/g, match => `${match},`)
        ));

        return !decimal ? grouped : `${grouped}.${decimal}`;
    }


    // Handle the event of click filter dropdown
    $( '.dropdown-menu a' ).on( 'click', function( event ) {
        let survey_id = @php echo $data['survey']->survey_id; @endphp;
        var $target = $( event.currentTarget ),
            val = $target.attr( 'data-value' ),
            field = $target.attr('data-field'),
            $inp = $target.find( 'input' ),
            idx;

        if (val == 'all') {
            if ( ( idx = options[field].indexOf( val ) ) > -1 ) {
                options[field] = [];
                setTimeout( function() {
                    $('#filter-' + field).find('input').prop('checked', false);
                }, 0);
                $(this).find('span').html('Select All');
            } else {
                options[field] = origin_options[field];
                setTimeout( function() {
                    $('#filter-' + field).find('input').prop('checked', true);
                    $('#filter-' + field).find('.filter-caption').html('All');
                }, 0);
                $(this).find('span').html('Deselect All');
            }
        } else {
            if ( ( idx = options[field].indexOf( val ) ) > -1 ) {
                options[field].splice( idx, 1 );
                setTimeout( function() { $inp.prop( 'checked', false ) }, 0);
            } else {
                options[field].push( val );
                setTimeout( function() { $inp.prop( 'checked', true ) }, 0);
            }

        }

        filtertext = $('#filter-' + field).find(`a[data-value='${val}'] span`).html();

        switch (options[field].length) {
            case 0:
                $('#filter-' + field).find('.filter-caption').html('None');
                $('#compilationDetailContent .detail_' + field + ' .filterTitle').html('none');
                break;

            case 1:
                if (options[field][0] == 'all') {
                    $('#filter-' + field).find('.filter-caption').html("None");
                    options[field] = [];
                    setTimeout( function() {
                        $('#filter-' + field).find('input').prop('checked', false);
                        $('#filter-' + field).find('a[data-value="all"] span').html('Select All');
                    }, 0);
                    $('#compilationDetailContent .detail_' + field + ' .filterTitle').html("none");
                } else {
                    filtertext = $('#filter-' + field).find(`a[data-value='${options[field][0]}'] span`).html();
                    $('#filter-' + field).find('.filter-caption').html(filtertext);
                    $('#compilationDetailContent .detail_' + field + ' .filterTitle').html(filtertext.toLowerCase());
                }
                break;

            case 2:
                if ( ( idx = options[field].indexOf( 'all' ) ) > -1 ) {
                    for (let tmp = 0; tmp < options[field].length; tmp++) {
                        const element = options[field][tmp];
                        filtertext = $('#filter-' + field).find(`a[data-value='${element}'] span`).html();
                        if (element != 'all') {
                            $('#filter-' + field).find('.filter-caption').html(filtertext);
                        }
                    }
                } else {
                    $('#filter-' + field).find('.filter-caption').html('Multiple values');
                }
                break;

            case origin_options[field].length - 1:  
                if ( ( idx = options[field].indexOf( 'all' ) ) < 0 ) {
                    $('#filter-' + field).find('.filter-caption').html('All');
                    $('#filter-' + field).find('[data-value="all"]').find('input').prop('checked', true);
                    $('#compilationDetailContent .detail_' + field + ' .filterTitle').html('<not filtered>');
                } else {
                    $('#filter-' + field).find('.filter-caption').html('Multiple values');
                    $('#compilationDetailContent .detail_' + field + ' .filterTitle').html('<multiple values>');
                }
                break;

            default:
                $('#filter-' + field).find('.filter-caption').html('Multiple values');
                $('#compilationDetailContent .detail_' + field + ' .filterTitle').html('multiple values');
                break;
        }

        $( event.target ).blur();

        let parent_id = 0;
        let parent_hours = {{ $data['total_hours'] }};
        let parent_cost = {{ $data['total_cost'] }};

        mask_height = $('body').height();
        $('.loading-mask').css('height', mask_height);
        $('.loading-mask').fadeIn();
        for (let i = 1; i < 8; i++) {
            $('#question' + i + '-layer').empty();
        }
        // Update the data of page by filter update
        let urlStr = String(location.href);
        if (urlStr.includes('nc')) {
            $.ajax({
                url: "{{ route('getNCCompilationChildServiceData') }}",
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "survey_id": survey_id,
                    "parent_id": parent_id,
                    "parent_hours": parent_hours,
                    "total_hours": total_hours,
                    "report": "nccompilation",
                    "position": JSON.stringify(options['position']),
                    "department": JSON.stringify(options['department']),
                    "group": JSON.stringify(options['group']),
                    "location": JSON.stringify(options['location']),
                    "category": JSON.stringify(options['category']),
                },
                dataType: 'json',
                beforeSend: function () {
                    $('.dropdown-menu').removeClass('show');                    
                },
                success: function (response) {
                    if (response.status && response.status == 400) {
                        $('#question1-layer').empty();
                        $('#notimeModal').modal();
                    } else {
                        let strHtml = "";
                        
                        var TotalHours = 0;
                       
                        response.forEach(item => {
                            // console.log(item.cost);
                           
                            TotalHours += item.hours;
                        })
                        
                        console.log(TotalHours);                       
                       
                       
                        if(options['position'].length == 0 && options['department'].length == 0 && options['group'].length == 0 && options['location'].length == 0 && options['category'].length == 0){
                            
                           
                            $('body #varyingTotalHours').text(0);
                            
                        
                        }else{
                           
                            $('body #varyingTotalHours').text(addComma(TotalHours));
                        }
                        response.forEach(item => {
                            if (strHtml == "") {
                                strHtml = `<div>
                                            <a class="btn btn-revelation-primary btn-block text-left service_bar" data-toggle="collapse" href="#Root1" role="button" aria-expanded="false" aria-controls="Root1">
                                                <span class="service_bar_title">Legal and Support</span> | ${item.resp_num} respondents
                                            </a>
                                            <div class="collapse" id="Root1">
                                                <div class="card card-body">
                                                    <table class="service_table table-responsive">
                                                        <thead>
                                                            <tr>
                                                                <th></th>
                                                                <th style="text-align: right;">Hours</th>
                                                                <th style="text-align: center;color:#82BD5E;">% of Total Hours</th>
                                                                <th style="text-align: center;color:#367BC1;">% of Hours within selection</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>`;
    
                            }
                            strHtml += `<tr>
                                            <td onclick="getChildServiceData(${item.question_id}, ${item.hours}, ${item.cost}, 1, '${item.question_desc}', '');" style="width:30%;text-align:right;">${item.question_desc}</td>
                                            <td onclick="getChildServiceData(${item.question_id}, ${item.hours}, ${item.cost}, 1, '${item.question_desc}', '');" style="text-align: right;">${numberFormatter.format(item.hours)}</td>
                                            <td onclick="getChildServiceData(${item.question_id}, ${item.hours}, ${item.cost}, 1, '${item.question_desc}', '');" style="width:20%;">
                                                <div class="bar-graph flex items-center justify-start" style="width: 100%;">
                                                    <div class="bg-support" style="width:calc(80% * ${item.total_hours_pct} / 100);height:24px;"></div>
                                                    <span class="px-1">${item.total_hours_pct}%</span>
                                                </div>
                                            </td>
                                            <td onclick="getChildServiceData(${item.question_id}, ${item.hours}, ${item.cost}, 1, '${item.question_desc}', '');" style="width:20%;">
                                            </td>
                                            <td class="btn-detailList">
                                                <button class="btn btn-revelation-primary btn-detail-list" data-questionId="${item.question_id}" data-questionDesc="${item.question_desc}" data-hours="${item.hours}" title="View Participants for ${item.question_desc}">
                                                    <svg class="respDetailBtn" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1.25em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 640 512"><path d="M96 224c35.3 0 64-28.7 64-64s-28.7-64-64-64s-64 28.7-64 64s28.7 64 64 64zm448 0c35.3 0 64-28.7 64-64s-28.7-64-64-64s-64 28.7-64 64s28.7 64 64 64zm32 32h-64c-17.6 0-33.5 7.1-45.1 18.6c40.3 22.1 68.9 62 75.1 109.4h66c17.7 0 32-14.3 32-32v-32c0-35.3-28.7-64-64-64zm-256 0c61.9 0 112-50.1 112-112S381.9 32 320 32S208 82.1 208 144s50.1 112 112 112zm76.8 32h-8.3c-20.8 10-43.9 16-68.5 16s-47.6-6-68.5-16h-8.3C179.6 288 128 339.6 128 403.2V432c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48v-28.8c0-63.6-51.6-115.2-115.2-115.2zm-223.7-13.4C161.5 263.1 145.6 256 128 256H64c-35.3 0-64 28.7-64 64v32c0 17.7 14.3 32 32 32h65.9c6.3-47.4 34.9-87.3 75.2-109.4z" fill="white"/></svg>
                                                </button>
                                            </td>
                                        </tr>`;
                        });
    
                        strHtml += `    </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>`;
    
                        $('#question1-layer').html(strHtml);
                        $('#question1-layer .btn-revelation-primary').click();
    
                        $('.loading-mask').fadeOut();
    
                        $('.service_table tbody tr').click(function() {
                            $(this).parent().find('tr').css('background-color', 'white');
                            $(this).css('background-color', 'rgba(54, 123, 193, 0.3)');
                        });
    
                        $('.btn-detail-list').click(function() {
                            mask_height = $('body').height();
                            $('.loading-mask').css('height', mask_height);
                            $('.loading-mask').fadeIn();
                            let question_id = $(this).attr('data-questionId');
                            let question_desc = $(this).attr('data-questionDesc');
                            let total_hours = $(this).attr('data-hours');
                            $detailContainer = $('#compilationDetailContent .third_part');
                            $detailContainer.empty();
                            $detailContainer.append(`<table class="table table-sm border-0" border="0" id="detailRespTable">
                                <thead>
                                    <tr>
                                        <th>Full Name</th>
                                        <th>Employee ID</th>
                                        <th>Employee Category</th>
                                        <th>Department</th>
                                        <th>Position</th>
                                        <th>Location</th>
                                        <th>Hours</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>`);
                            $.ajax({
                                url: '{{ route('getDetailCompilationRespsList') }}',
                                type: 'POST',
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    "survey_id": survey_id,
                                    "question_id": question_id,
                                    "position": JSON.stringify(options['position']),
                                    "department": JSON.stringify(options['department']),
                                    "group": JSON.stringify(options['group']),
                                    "location": JSON.stringify(options['location']),
                                    "category": JSON.stringify(options['category']),
                                },
                                dataType: 'json',
                                success: function (response) {
                                    $('.loading-mask').fadeOut();
                                    $('#compilationReportContent').css('display', 'none');
                                    $('#compilationDetailContent').css('display', 'block');
                                    $('#backBtn').fadeIn();
                                    $('#excelBtn').fadeIn();
                                    $('.questionName').html(question_desc);

                                    excelData = response;
                                    let size = Object.size(response)
                                    $('.respNum').html(size);
                                    $detailTable = $('#detailRespTable tbody');
                                    $detailTable.empty();
                                    $headerTable = $('#detailRespTable thead');
                                    $('#detailRespTable thead .table-primary').remove();
                                    $headerTable.append(`<tr class="table-primary" style="font-weight:bold;">
                                                            <th colspan="7"><div style="float:left;">Grand Total</div><div style="float:right;">${hoursFormatter.format(total_hours)}</div></th>
                                                        </tr>`);
                                    response.forEach(item => {
                                        let strHtml = `<tr>
                                                    <td>${item.name}</td>
                                                    <td>${item.employee_id}</td>
                                                    <td>${item.employee_category}</td>
                                                    <td>${item.department}</td>
                                                    <td>${item.position}</td>
                                                    <td>${item.location}</td>
                                                    <td style="text-align:right;">${hoursFormatter.format(item.hours)}</td>
                                                </tr>`;
                                        $detailTable.append(strHtml);
                                    });
                                    $('#detailRespTable').DataTable({
                                        paging: false,
                                        searching: false,
                                        columnsDefs: [
                                            { orderable: false, targets: -1}
                                        ]
                                    });
                                }
                            });
                        });
                    }
                },
                error: function(request, error) {
                    alert("Request: " + JSON.stringify(request));
                }
            });
        } else {
            $.ajax({
                url: '{{ route('getCompilationChildServiceData') }}',
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "survey_id": survey_id,
                    "parent_id": parent_id,
                    "parent_hours": parent_hours,
                    "parent_cost": parent_cost,
                    "total_cost": total_cost,
                    "position": JSON.stringify(options['position']),
                    "department": JSON.stringify(options['department']),
                    "group": JSON.stringify(options['group']),
                    "location": JSON.stringify(options['location']),
                    "category": JSON.stringify(options['category']),
                },
                dataType: 'json',
                beforeSend: function () {
                    $('.dropdown-menu').removeClass('show');
                },
                success: function (response) {
                    if (response.status && response.status == 400) {
                        $('#question1-layer').empty();
                        $('#notimeModal').modal();
                    } else {
                        var TotalCost = 0;
                        var TotalHours = 0;
                        var AvgHourlyCost = 0;
                        response.forEach(item => {
                            // console.log(item.cost);
                            TotalCost += item.cost;
                            TotalHours += item.hours;
                        })
                        
                        if(options['position'].length == 0 && options['department'].length == 0 && options['group'].length == 0 && options['location'].length == 0 && options['category'].length == 0){
                            
                            $('body #varyingTotalCost').text(0);
                            $('body #varyingTotalHours').text(0);
                            $('body #varyingAvgHourlyCost').text(0);
                        
                        }else{
                           
                            AvgHourlyCost = Math.round(TotalCost/TotalHours);
                            
                            $('body #varyingTotalCost').text(addComma(TotalCost));
                            $('body #varyingTotalHours').text(addComma(TotalHours));
                            $('body #varyingAvgHourlyCost').text(addComma(AvgHourlyCost));
                            
                        }
                       
                       
                        // return;
                        let strHtml = "";
    
                        response.forEach(item => {
                            if (strHtml == "") {
                                strHtml = `<div>
                                            <a class="btn btn-revelation-primary btn-block text-left service_bar" data-toggle="collapse" href="#Root1" role="button" aria-expanded="false" aria-controls="Root1">
                                                <span class="service_bar_title">Legal and Support</span> | ${item.resp_num} respondents
                                            </a>
                                            <div class="collapse" id="Root1">
                                                <div class="card card-body">
                                                    <table class=" table-responsive">
                                                        <thead>
                                                            <tr>
                                                                <th></th>
                                                                <th style="text-align: right;">Hours</th>
                                                                <th style="text-align: right;">Cost</th>
                                                                <th style="text-align: right;">Avg.Hourly Cost</th>
                                                                <th style="text-align: center;color:#82BD5E;">% of Total Cost</th>
                                                                <th style="text-align: center;color:#367BC1;">% of Cost within selection</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>`;
    
                            }
                            strHtml += `<tr>
                                            <td onclick="getChildServiceData(${item.question_id}, ${item.hours}, ${item.cost}, 1, '${item.question_desc}', '');" style="width:30%;text-align:right;">${item.question_desc}</td>
                                            <td onclick="getChildServiceData(${item.question_id}, ${item.hours}, ${item.cost}, 1, '${item.question_desc}', '');" style="text-align: right;">${numberFormatter.format(item.hours)}</td>
                                            <td onclick="getChildServiceData(${item.question_id}, ${item.hours}, ${item.cost}, 1, '${item.question_desc}', '');" style="text-align: right;">${currencyFormatter.format(item.cost)}</td>
                                            <td onclick="getChildServiceData(${item.question_id}, ${item.hours}, ${item.cost}, 1, '${item.question_desc}', '');" style="text-align: right;">${currencyFormatter.format(item.avg_hourly_cost)}</td>
                                            <td onclick="getChildServiceData(${item.question_id}, ${item.hours}, ${item.cost}, 1, '${item.question_desc}', '');" style="width:20%;">
                                                <div class="bar-graph flex items-center justify-start" style="width: 100%;">
                                                    <div class="bg-support" style="width:calc(80% * ${item.total_cost_pct} / 100);height:24px;"></div>
                                                    <span class="px-1">${item.total_cost_pct}%</span>
                                                </div>
                                            </td>
                                            <td onclick="getChildServiceData(${item.question_id}, ${item.hours}, ${item.cost}, 1, '${item.question_desc}', '');" style="width:20%;">
    
                                            </td>
                                            <td>
                                                <button class="btn btn-revelation-primary btn-detail-list" data-questionId="${item.question_id}" data-questionDesc="${item.question_desc}" data-hours="${item.hours}" title="View Participants for ${item.question_desc}">
                                                    <svg class="respDetailBtn" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1.25em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 640 512"><path d="M96 224c35.3 0 64-28.7 64-64s-28.7-64-64-64s-64 28.7-64 64s28.7 64 64 64zm448 0c35.3 0 64-28.7 64-64s-28.7-64-64-64s-64 28.7-64 64s28.7 64 64 64zm32 32h-64c-17.6 0-33.5 7.1-45.1 18.6c40.3 22.1 68.9 62 75.1 109.4h66c17.7 0 32-14.3 32-32v-32c0-35.3-28.7-64-64-64zm-256 0c61.9 0 112-50.1 112-112S381.9 32 320 32S208 82.1 208 144s50.1 112 112 112zm76.8 32h-8.3c-20.8 10-43.9 16-68.5 16s-47.6-6-68.5-16h-8.3C179.6 288 128 339.6 128 403.2V432c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48v-28.8c0-63.6-51.6-115.2-115.2-115.2zm-223.7-13.4C161.5 263.1 145.6 256 128 256H64c-35.3 0-64 28.7-64 64v32c0 17.7 14.3 32 32 32h65.9c6.3-47.4 34.9-87.3 75.2-109.4z" fill="white"/></svg>
                                                </button>
                                            </td>
                                        </tr>`;
                        });
    
                        strHtml += `    </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>`;
    
                        $('#question1-layer').html(strHtml);
                        $('#question1-layer .btn-revelation-primary').click();
    
                        $('.loading-mask').fadeOut();
    
                        $('.service_table tbody tr').click(function() {
                            $(this).parent().find('tr').css('background-color', 'white');
                            $(this).css('background-color', 'rgba(54, 123, 193, 0.3)');
                        });
    
                        $('.btn-detail-list').click(function() {
                            mask_height = $('body').height();
                            $('.loading-mask').css('height', mask_height);
                            $('.loading-mask').fadeIn();
                            let question_id = $(this).attr('data-questionId');
                            let question_desc = $(this).attr('data-questionDesc');
                            let total_hours = $(this).attr('data-hours');
                            $detailContainer = $('#compilationDetailContent .third_part');
                            $detailContainer.empty();
                            $detailContainer.append(`<table class="table table-sm" id="detailRespTable">
                                <thead>
                                    <tr>
                                        <th>Full Name</th>
                                        <th>Employee ID</th>
                                        <th>Employee Category</th>
                                        <th>Department</th>
                                        <th>Position</th>
                                        <th>Location</th>
                                        <th>Hours</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>`);
                            $.ajax({
                                url: '{{ route('getDetailCompilationRespsList') }}',
                                type: 'POST',
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    "survey_id": survey_id,
                                    "question_id": question_id,
                                    "position": JSON.stringify(options['position']),
                                    "department": JSON.stringify(options['department']),
                                    "group": JSON.stringify(options['group']),
                                    "location": JSON.stringify(options['location']),
                                    "category": JSON.stringify(options['category']),
                                },
                                dataType: 'json',
                                success: function (response) {
                                    $('.loading-mask').fadeOut();
                                    $('#compilationReportContent').css('display', 'none');
                                    $('#compilationDetailContent').css('display', 'block');
                                    $('#backBtn').fadeIn();
                                    $('#excelBtn').fadeIn();
                                    $('.questionName').html(question_desc);

                                    excelData = response;
                                    let size = Object.size(response)
                                    $('.respNum').html(size);
                                    $detailTable = $('#detailRespTable tbody');
                                    $detailTable.empty();
                                    $headerTable = $('#detailRespTable thead');
                                    $('#detailRespTable thead .table-primary').remove();
                                    $headerTable.append(`<tr class="table-primary" style="font-weight:bold;">
                                                            <th colspan="7"><div style="float:left;">Grand Total</div><div style="float:right;">${hoursFormatter.format(total_hours)}</div></th>
                                                        </tr>`);
                                    response.forEach(item => {
                                        let strHtml = `<tr>
                                                    <td>${item.name}</td>
                                                    <td>${item.employee_id}</td>
                                                    <td>${item.employee_category}</td>
                                                    <td>${item.department}</td>
                                                    <td>${item.position}</td>
                                                    <td>${item.location}</td>
                                                    <td style="text-align:right;">${hoursFormatter.format(item.hours)}</td>
                                                </tr>`;
                                        $detailTable.append(strHtml);
                                    });
                                    $('#detailRespTable').DataTable({
                                        paging: false,
                                        searching: false,
                                        columnsDefs: [
                                            { orderable: false, targets: -1}
                                        ]
                                    });
                                }
                            });
                        });
                    }
                },
                error: function(request, error) {
                    alert("Request: " + JSON.stringify(request));
                }
            });
        }

    });
</script>
