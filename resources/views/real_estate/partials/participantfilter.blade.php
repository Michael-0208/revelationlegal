<link rel="stylesheet" href="{{ asset('css/filter.css') }}">
<div class="flex flex-wrap justify-between border-b-4">
    <div class="flex-auto border-r-2 col-md-6">
        <div class="text-ns py-2">Filter list of employees by...</div>
        <div class="flex justify-between items-center py-2" id="reportsupfilters">
            <div id="filter-category" class="flex-1">
                <div class="text-ns">Category</div>
                <div class="button-group">
                    <button type="button" class=" dropdown-toggle filter-btn" data-toggle="dropdown">
                        <span class="filter-caption">All</span> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu scrollable-menu" role="menu">
                        <li><a href="#" class="small" data-value="all" data-text="all" data-field="category" tabIndex="-1"><input type="checkbox" checked/>&nbsp;<span>Deselect All</span></a></li>
                        @foreach ($data['category'] as $category)
                            <li><a href="#" class="small" data-value="{{ $category }}" data-text="{{ $category }}" data-field="category" tabIndex="-1"><input type="checkbox" checked/>&nbsp;<span>{{ $category }}</span></a></li>
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
                        <li><a href="#" class="small" data-value="all" data-text="all" data-field="department" tabIndex="-1"><input type="checkbox" checked/>&nbsp;<span>Deselect All</span></a></li>
                        @foreach ($data['department'] as $department)
                            <li><a href="#" class="small" data-value="{{ $department }}" data-text="{{ $department }}" data-field="department" tabIndex="-1"><input type="checkbox" checked/>&nbsp;<span>{{ $department }}</span></a></li>
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
                        <li><a href="#" class="small" data-value="all" data-text="all" data-field="group" tabIndex="-1"><input type="checkbox" checked/>&nbsp;<span>Deselect All</span></a></li>
                        @foreach ($data['group'] as $group)
                            <li><a href="#" class="small" data-value="{{ $group }}" data-text="{{ $group }}" data-field="group" tabIndex="-1"><input type="checkbox" checked/>&nbsp;<span>{{ $group }}</span></a></li>
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
                        <li><a href="#" class="small" data-value="all" data-field="location" data-text="all" tabIndex="-1"><input type="checkbox" checked/>&nbsp;<span>Deselect All</span></a></li>
                        @foreach ($data['location'] as $location)
                            <li><a href="#" class="small" data-value="{{ $location }}" data-text="{{ $location }}" data-field="location" tabIndex="-1"><input type="checkbox" checked/>&nbsp;<span>{{ $location }}</span></a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="flex-auto col-md-6"> 
        <div class="text-ns p-2">Filter hours and costs for tasks in...</div>
        <div class="flex justify-between items-center p-2" id="reportsupfilters">
            <div id="filter-classification" class="flex-1">
                <div class="text-ns">Classification</div>
                <div class="button-group">
                    <button type="button" class=" dropdown-toggle filter-btn" data-toggle="dropdown">
                        <span class="filter-caption">All</span> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu scrollable-menu" role="menu">
                        <li><a href="#" class="small" data-value="all" data-text="all" data-field="classification" tabIndex="-1"><input type="checkbox" checked/>&nbsp;<span>Deselect All</span></a></li>
                        @foreach ($data['classification'] as $classification)
                            <li><a href="#" class="small" data-value="{{ $classification }}" data-text="{{ $data['questionAry'][$classification]->question_desc }}" data-field="classification" tabIndex="-1"><input type="checkbox" checked/>&nbsp;<span>{{ $data['questionAry'][$classification]->question_desc }}</span></a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div id="filter-substantive" class="flex-1">
                <div class="text-ns">Substantive Area</div>
                <div class="button-group">
                    <button type="button" class=" dropdown-toggle filter-btn" data-toggle="dropdown">
                        <span class="filter-caption">All</span> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu scrollable-menu" role="menu">
                        <li><a href="#" class="small" data-value="all" data-text="all" data-field="substantive" tabIndex="-1"><input type="checkbox" checked/>&nbsp;<span>Deselect All</span></a></li>
                        @foreach ($data['substantive'] as $substantive)
                            <li><a href="#" class="small" data-value="{{ $substantive }}" data-text="{{ $data['questionAry'][$substantive]->question_desc }}" data-field="substantive" tabIndex="-1"><input type="checkbox" checked/>&nbsp;<span>{{ $data['questionAry'][$substantive]->question_desc }}</span></a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div id="filter-process" class="flex-1">
                <div class="text-ns">Process</div>
                <div class="button-group">
                    <button type="button" class=" dropdown-toggle filter-btn" data-toggle="dropdown">
                        <span class="filter-caption">All</span> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu scrollable-menu" role="menu">
                        <li><a href="#" class="small" data-value="all" data-text="all" data-field="process" tabIndex="-1"><input type="checkbox" checked/>&nbsp;<span>Deselect All</span></a></li>
                        @foreach ($data['process'] as $process)
                            <li><a href="#" class="small" data-value="{{ $process }}" data-text="{{ $data['questionAry'][$process]->question_desc }}" data-field="process" tabIndex="-1"><input type="checkbox" checked/>&nbsp;<span>{{ $data['questionAry'][$process]->question_desc }}</span></a></li>
                        @endforeach
                    </ul>
                </div>
            </div>            
        </div>
    </div>
</div>

<script>
    // Define filter variables
    var origin_options = [];
    origin_options['department'] = @json($data['department']);
    origin_options['department'].push('all');
    origin_options['group'] =  @json($data['group']);
    origin_options['group'].push('all');
    origin_options['location'] = @json($data['location']);
    origin_options['location'].push('all');
    origin_options['category'] = @json($data['category']);
    origin_options['category'].push('all');
    origin_options['classification'] = @json($data['classification']);
    origin_options['classification'].push('all');
    origin_options['substantive'] = @json($data['substantive']);
    origin_options['substantive'].push('all');
    origin_options['process'] = @json($data['process']);
    origin_options['process'].push('all');
    var options = [];
    options['department'] = @json($data['department']);
    options['department'].push('all');
    options['group'] =  @json($data['group']);
    options['group'].push('all');
    options['location'] = @json($data['location']);
    options['location'].push('all');
    options['category'] = @json($data['category']);
    options['category'].push('all');
    options['classification'] = @json($data['classification']);
    options['classification'].push('all');
    options['substantive'] = @json($data['substantive']);
    options['substantive'].push('all');
    options['process'] = @json($data['process']);
    options['process'].push('all');
    var max_hours = {{ $data['max_hours'] }};

    // Handle the event of click filter dropdown
    $( '.dropdown-menu a' ).on( 'click', function( event ) {
        let survey_id = @php echo $data['survey'] -> survey_id; @endphp;
        var $target = $( event.currentTarget ),
            val = $target.attr( 'data-value' ),
            text = $target.attr( 'data-text' ),
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

        switch (options[field].length) {
            case 0:
                $('#filter-' + field).find('.filter-caption').html('None');
                break;

            case 1:
                if (options[field][0] == 'all') {
                    $('#filter-' + field).find('.filter-caption').html("None");
                    options[field] = [];
                    setTimeout( function() {
                        $('#filter-' + field).find('input').prop('checked', false);
                        $('#filter-' + field).find('a[data-value="all"] span').html('Select All');
                    }, 0);
                } else {
                    $('#filter-' + field).find('.filter-caption').html(val);
                }
                break;

            case 2:
                if ( ( idx = options[field].indexOf( 'all' ) ) > -1 ) {
                    for (let tmp = 0; tmp < options[field].length; tmp++) {
                        const element = options[field][tmp];
                        if (element != 'all') {
                            $('#filter-' + field).find('.filter-caption').html(element);
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
                } else {
                    $('#filter-' + field).find('.filter-caption').html('Multiple values');
                }
                break;

            default:
                $('#filter-' + field).find('.filter-caption').html('Multiple values');
                break;
        }

        $( event.target ).blur();
        mask_height = $('body').height();
        $('.loading-mask').css('height', mask_height);
        $('.loading-mask').fadeIn();
        // Update the data of page by filter update
        $.ajax({
            url: '{{ route("realestate.getParticipantProximity") }}',
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "survey_id": survey_id,
                "department": JSON.stringify(options['department']),
                "group": JSON.stringify(options['group']),
                "category": JSON.stringify(options['category']),
                "location": JSON.stringify(options['location']),
                "classification": JSON.stringify(options['classification']),
                "substantive": JSON.stringify(options['substantive']),
                "process": JSON.stringify(options['process']),
            },
            dataType: 'json',
            beforeSend: function () {
                $('#participantProximityContent .third_part').empty();
                $('#proximity_bar').empty();
                $('.dropdown-menu').removeClass('show');
                max_hours = 0;
            },
            success: function (data) {
                let respsData = data.resps;
                let rsfData   = data.rsf_percent_data;

                if (rsfData.high_hours > 0) {
                    $('#proximity_bar').append(`<div class="high-bar text-center">
                            <div class="title font-bold">High</div>
                            <div class="value"></div>
                        </div>`);
                    $('#proximity_bar .high-bar').css('width', `${rsfData.high_percent}%`);
                    $('#proximity_bar .high-bar .value').html(numberFormatter.format(Math.round(rsfData.high_hours)));
                }

                if (rsfData.med_hours > 0) {
                    $('#proximity_bar').append(`<div class="med-bar text-center">                    
                        <div class="title font-bold">Med</div>
                        <div class="value"></div>
                    </div>`);
                    $('#proximity_bar .med-bar').css('width', `${rsfData.med_percent}%`);
                    $('#proximity_bar .med-bar .value').html(numberFormatter.format(Math.round(rsfData.med_hours)));
                }

                if (rsfData.low_hours > 0) {
                    $('#proximity_bar').append(`<div class="low-bar text-center">                    
                        <div class="title font-bold">Low</div>
                        <div class="value"></div>
                    </div>`);
                    $('#proximity_bar .low-bar').css('width', `${rsfData.low_percent}%`);
                    $('#proximity_bar .low-bar .value').html(numberFormatter.format(Math.round(rsfData.low_hours)));
                }

                // <button class="btn btn-revelation-primary" id="excelBtn">Export to Excel</button>
                let html = `<div class="toolbar">
                            </div>
                            <div class="table-responsive">
                                <table 
                                    id="respTable" 
                                    data-toggle="table"
                                    data-search="true"
                                    data-search-align="left"
                                    data-custom-search="searchRespName"
                                    data-button-class="btn-revelation-primary"
                                    data-toolbar=".toolbar"
                                    data-toolbar-align="right"
                                    class="table table-striped table-sm text-xs w-full" 
                                    cellspacing="0" 
                                    width="100%">
                                    <thead>
                                        <tr>
                                            <th class="th-sm" style="width:50px;" data-sortable="true" data-field="employee_id">Employeed ID</th>
                                            <th class="th-sm" data-sortable="true" data-field="name">Full Name</th>
                                            <th class="th-sm" data-sortable="true" data-field="position">Position</th>
                                            <th class="th-sm text-right" data-sortable="true" data-field="total_hours" data-formatter="table_numberFormatter">Total Hours</th>
                                            <th class="th-sm text-right" data-sortable="true" data-field="rsf_cost" data-formatter="table_costFormatter">Total RSF Cost</th>
                                            <th class="th-sm pl-0" data-sortable="true" data-field="high_hours" data-formatter="table_highFormatter">High</th>
                                            <th class="th-sm pl-0" data-sortable="true" data-field="med_hours" data-formatter="table_medFormatter">Med</th>
                                            <th class="th-sm pl-0" data-sortable="true" data-field="low_hours" data-formatter="table_lowFormatter">Low</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;
                
                respsData.forEach(resp => {
                    html += `<tr>
                                <td>${ resp.cust_1 }</td>
                                <td>${ resp.resp_last }, ${ resp.resp_first }</td>
                                <td>${ resp.cust_3 }</td>
                                <td>${ resp.total_hours }</td>
                                <td>${ resp.rsf_cost }</td>
                                <td>${ resp.prox_high_hours }</td>
                                <td>${ resp.prox_medium_hours }</td>
                                <td>${ resp.prox_low_hours }</td>
                            </tr>`;
                    
                    if (resp.prox_high_hours > max_hours)
                        max_hours = resp.prox_high_hours;
                    
                    if (resp.prox_medium_hours > max_hours)
                        max_hours = resp.prox_medium_hours;
                    
                    if (resp.prox_low_hours > max_hours)
                        max_hours = resp.prox_low_hours;
                });

                html += `</tbody>
                    </table>
                </div>`;

                $('#participantProximityContent .third_part').html(html);
                $('#respTable').bootstrapTable({
                    formatSearch: function () {
                        return 'Search Name'
                    }
                });
                
                $('#searchCloseBtn').css('display', 'none');
                $('.loading-mask').fadeOut();

                // Handle the event of excel button click
        $('#excelBtn').click(function () {
                    let tableData = $('#respTable').bootstrapTable('getData');
                    $.ajax({
                        url: '{{ route("realestate.exportParticipantExcel") }}',
                        type: 'POST',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "survey_id": survey_id,
                            "tableData": JSON.stringify(tableData)
                        },
                        dataType: 'json',
                        beforeSend: function () {
                            $('#generateExcelModal').modal('show');
                        },
                        success: function (res) {
                            $('#generateExcelModal .modal-body').html('Generated an Excel file');
                            $('#generateExcelModal .btn').attr('href', res.url);
                            $('#generateExcelModal .btn').attr('download', res.filename);
                            $('#generateExcelModal .btn').removeClass('disabled');
                        },
                        error: function(request, error) {
                            alert("Request: " + JSON.stringify(request));
                        }
                    });
                });
            },
            error: function(request, error) {
                alert("Request: " + JSON.stringify(request));
            }
        });
    });
</script>
