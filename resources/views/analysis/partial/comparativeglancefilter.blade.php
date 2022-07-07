<link rel="stylesheet" href="{{ asset('css/filter.css') }}">
<div class="text-ns my-2">Show the profile of the following participants:</div> 
<div class="flex justify-between items-center" id="reportsupfilters">
    <div id="filter-position" class="flex-1">
        <div class="text-ns">Position</div>
        <div class="button-group">
            <button type="button" class=" dropdown-toggle filter-btn" data-toggle="dropdown">
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

        let depthQuestion = $('#depthQuestion').val();
        let depthText     = $('#depthQuestion option:selected').text();
        let minPercent    = $('#minPercent').val();
        let filterRespPrimary    = $('#filterRespPrimary').val();
        let filterRespSecondary  = $('#filterRespSecondary').val();
        // Update the data of page by filter update
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
                $('.dropdown-menu').removeClass('show');
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
                    strHtml += `    <td style="text-align: right;">${ numberFormatter.format(row.hours) }</td>
                    <td style="text-align: right;">${ formatter.format(row.cost) }</td>
                                    <td style="text-align: right;">${ row.percent }%</td>
                                    <td style="text-align: right;">${ Math.round(row.hours * 100 / res.grand_total_hours) }%</td>
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
</script>
