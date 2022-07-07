<link rel="stylesheet" href="{{ asset('css/filter.css') }}">  
<div class="flex justify-between items-center " id="reportsupfilters">
    <div id="filter-position" class="flex-1">
        <div class="text-ns">Position</div>
        <div class="button-group">
            <button type="button" class="dropdown-toggle filter-btn" data-toggle="dropdown">
                <span class="filter-caption">All</span> <span class="caret"></span>
            </button>
            <ul class="dropdown-menu scrollable-menu" role="menu">
                <li><a href="#" class="small" data-value="all" data-field="position" tabIndex="-1"><input type="checkbox" checked/>&nbsp;<span>Deselect All</span></a></li>
                @foreach ($data['position'] as $position)
                    <li><a href="#" class="small" data-value="{{ $position }}" data-field="position" tabIndex="-1"><input type="checkbox" checked/>&nbsp;<span>{{ $position }}</span></a></li>
                @endforeach
            </ul>
        </div>
    </div>
    <div id="filter-department" class="flex-1">
        <div class="text-ns">Department</div>
        <div class="button-group">
            <button type="button" class="dropdown-toggle filter-btn" data-toggle="dropdown">
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
            <button type="button" class="dropdown-toggle filter-btn" data-toggle="dropdown">
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
            <button type="button" class="dropdown-toggle filter-btn" data-toggle="dropdown">
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
    <div id="filter-proximity" class="flex-1">
        <div class="text-ns">Proximity Factor</div>
        <div class="button-group">
            <button type="button" class="dropdown-toggle filter-btn" data-toggle="dropdown">
                <span class="filter-caption">All</span> <span class="caret"></span>
            </button>
            <ul class="dropdown-menu scrollable-menu" role="menu">
                <li><a href="#" class="small" data-value="all" data-field="proximity" tabIndex="-1"><span>(All)</span></a></li>
                <li><a href="#" class="small" data-value="1" data-field="proximity" tabIndex="-1"><span>1. Low</span></a></li>
                <li><a href="#" class="small" data-value="2" data-field="proximity" tabIndex="-1"><span>2. Medium</span></a></li>
                <li><a href="#" class="small" data-value="3" data-field="proximity" tabIndex="-1"><span>3. High</span></a></li>
                <li><a href="#" class="small" data-value="4" data-field="proximity" tabIndex="-1"><span>4. Virtual</span></a></li>
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
    origin_options['survey_status'] = [];
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
    options['survey_status'] = [];
    options['proximity'] = 'all';

    var depthQuestion = {{ $data['depth'] }};

    const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            },
        });

    // Handle the event of click filter dropdown
    $( '.dropdown-menu a' ).on( 'click', function( event ) {
        let survey_id = @php echo $data['survey'] -> survey_id; @endphp;
        var $target = $( event.currentTarget ),
            val = $target.attr( 'data-value' ),
            field = $target.attr('data-field'),
            $inp = $target.find( 'input' ),
            idx;

        if (field == 'proximity') {
            if (val == 'all') {
                $('#filter-' + field).find('.filter-caption').html('All');
                options['proximity'] = val;
            } else {
                $('#filter-' + field).find('.filter-caption').html($target.find('span').text());
                options['proximity'] = val;
            }
        } else {
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
        }
        
        $( event.target ).blur();
        // Update the data of page by filter update
        $.ajax({
            url: "{{ route('realestate.filter-activity-cost-by-location') }}",
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
                if (res.costData == 404) {
                    Toast.fire({
                        icon: 'error',
                        title: 'No more record.'
                    });
                } else {
                    costData = res.costData;
                    $tableContainer = $('.tableContainer');

                    strHtml = `<table id="costbyLocationTable" 
                                    class="table" 
                                    style="width:96%;margin:30px 2%;display:none;">
                                    <thead>
                                        <tr>
                                            <th style="border-bottom: none;padding-top:20px;width:120px;">Location</th>`;

                    for (let i = 0; i < depthQuestion; i++) {
                        strHtml += `<th style="border-bottom: none;">${res.thAry[i]}</th>`;
                    }

                    strHtml += `<th class="text-right" style="border-bottom: none;">Employee Cost</th>
                                <th class="text-right" style="border-bottom: none;">RSF</th>
                                <th class="text-right" style="border-bottom: none;">Hours</th>
                                <th class="text-right" style="border-bottom: none;">RSF Cost(Current)</th>
                            </tr>
                            <tr style="height:20px">                            
                                <th style="border-top: none;"></th>`;

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

                    strHtml += `<th class="text-right" style="border-top: none;"></th>
                                <th class="text-right" style="border-top: none;"></th>
                                <th class="text-right" style="border-top: none;"></th>
                                <th class="text-right" style="border-top: none;"></th>
                            </tr>
                        </thead>
                        <tbody>`;                        
                    
                    for (const location in costData) {
                        rows = costData[location].rows;
                        
                        for (let i in rows) {
                            strHtml += `<tr>`;
                            strHtml += `<td class="questionDescTD0"><b>${rows[i].location}</b></td>`;
                            questionDescAry = rows[i].question_desc.split("..");
                            for (j = 0; j < depthQuestion ; j++) {
                                strHtml += `<td class="questionDescTD${j + 1}" data-option="${rows[i].location}" title="${questionDescAry[j]}">${questionDescAry[j]}</td>`;
                            }                                
                            strHtml += `<td class="text-right">${numberFormatter.format(Math.round(rows[i].employee_cost))}</td>
                                        <td class="text-right">${numberFormatter.format(Math.round(rows[i].rsf))}</td>
                                        <td class="text-right">${numberFormatter.format(Math.round(rows[i].hours))}</td>
                                        <td class="text-right">${numberFormatter.format(Math.round(rows[i].rsf_cost_current))}</td>
                                    </tr>`;
                        }

                        strHtml += `<tr>
                                        <td style="border: none;"></td>
                                        <td colspan="${depthQuestion}">Total</td>
                                        <td class="text-right"><b>${numberFormatter.format(Math.round(costData[location].total_employee_cost))}</b></td>
                                        <td class="text-right"><b>${numberFormatter.format(Math.round(costData[location].total_rsf))}</b></td>
                                        <td class="text-right"><b>${numberFormatter.format(Math.round(costData[location].total_hours))}</b></td>
                                        <td class="text-right"><b>${numberFormatter.format(Math.round(costData[location].total_cost_current))}</b></td>
                                    </tr>`;
                    }

                    strHtml += `<tr>
                                    <td><b>Grand Total</b></td>
                                    <td colspan="${depthQuestion}"></td>
                                    <td class="text-right"><b>${numberFormatter.format(Math.round(res.total_employee_cost))}</b></td>
                                    <td class="text-right"><b>${numberFormatter.format(Math.round(res.total_rsf))}</b></td>
                                    <td class="text-right"><b>${numberFormatter.format(Math.round(res.total_hours))}</b></td>
                                    <td class="text-right"><b>${numberFormatter.format(Math.round(res.total_rsf_cost_current))}</b></td>
                                </tr>`;

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

                    $('#costbyLocationTable').css('display', 'table');
                }

                $('.loading-mask').hide();
            },
            error: function(request, error) {
                alert("Request: " + JSON.stringify(request));
            }
        });
    });
</script>
