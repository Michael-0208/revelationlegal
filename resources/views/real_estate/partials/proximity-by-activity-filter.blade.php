<link rel="stylesheet" href="{{ asset('css/filter.css') }}">
<div class="flex justify-between items-center px-2" style="border-bottom: 3px solid lightgray;" id="reportsupfilters">
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
    <div id="filter-category" class="flex-1">
        <div class="text-ns">Category</div>
        <div class="button-group">
            <button type="button" class="dropdown-toggle filter-btn" data-toggle="dropdown">
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
    <!-- <div>
        <img src="{{asset('imgs/logo-new-small_rev.png')}}" alt="" style="height: 70px;">
    </div> -->
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
    var depthQuestion = {{ $data['depth'] }};

    // Handle the event of click filter dropdown
    $( '.dropdown-menu a' ).on( 'click', function( event ) {
        let survey_id = @php echo $data['survey'] -> survey_id; @endphp;
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
            url: '{{ route("realestate.filter-proximity-by-activity") }}',
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
                        $proxBar = $('#proximity_bar');
                        strHtml = `<div class="high-bar text-center" style="width: ${res.rsf_percent_data.high_percent}%;">
                                        <div class="title font-bold">High</div>
                                        <div class="value">${numberFormatter.format(res.rsf_percent_data.high_hours)}</div>
                                    </div>
                                    <div class="med-bar text-center" style="width: ${res.rsf_percent_data.med_percent}%;">                    
                                        <div class="title font-bold">Med</div>
                                        <div class="value">${numberFormatter.format(res.rsf_percent_data.med_hours)}</div>
                                    </div>
                                    <div class="low-bar text-center" style="width: ${res.rsf_percent_data.low_percent}%;">                    
                                        <div class="title font-bold">Low</div>
                                        <div class="value">${numberFormatter.format(res.rsf_percent_data.low_hours)}</div>
                                    </div>`;
                        $proxBar.html(strHtml);

                        rows = res.rows;
                        $tableContainer = $('.tableContainer'); 

                        strHtml = `<table id="proximityActivityTable" class="table table-bordered table-sm">
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
                                    <th style="border-bottom: none;border-right:1px solid;">Low</th>
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
                            strHtml += `<td style="border-right:1px solid;">`;
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
    });
</script>
