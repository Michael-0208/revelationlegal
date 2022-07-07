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

    var depthQuestion = 2;

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
        // Update the data of page by filter update
        $.ajax({
            url: "{{ route('realestate.filter-opportunity-summary') }}",
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "survey_id": survey_id,
                "position": JSON.stringify(options['position']),
                "department": JSON.stringify(options['department']),
                "group": JSON.stringify(options['group']),
                "location": JSON.stringify(options['location']),
            },
            dataType: 'json',
            beforeSend: function () {
                mask_height = $('body').height();
                $('.loading-mask').css('height', mask_height);
                $('.loading-mask').show();
                $('.dropdown-menu').removeClass('show');
            },
            success: function (res) {
                $tableContainer = $('.tableDiv');

                strHtml = `<table id="opportunitySummaryTable" 
                            data-toggle="table">
                            <thead>
                                <tr>
                                    <th class="text-center" colspan="4"><b>Current Cost</b></th>
                                    <th class="border-none"></th>
                                    <th class="text-center" colspan="6"><b>Potential Savings</b></th>
                                </tr>
                                <tr>
                                    <th colspan="4"></th>
                                    <th class="border-none"></th>
                                    <th class="text-center" colspan="2">Adjacent</th>
                                    <th class="text-center" colspan="2">Regional</th>
                                    <th class="text-center" colspan="2">Other</th>
                                </tr>
                                <tr>
                                    <th>Proximity Factor</th>
                                    <th class="text-right" data-field="rsf" data-sortable="false" data-formatter="table_numberFormatter">RSF</th>
                                    <th class="text-right" data-field="blended_rate" data-sortable="false" data-formatter="table_rateFormatter">Blended Rate*</th>
                                    <th class="text-right" data-field="rsf_cost_current" data-sortable="false" data-formatter="table_costFormatter">RSF Cost(Current)</th>
                                    <th class="border-none"></th>
                                    <th class="text-right" data-field="rsf_cost_adjacent" data-sortable="false" data-formatter="table_costFormatter">(M)</th>
                                    <th class="text-right" data-field="percent_adjacent" data-sortable="false" data-formatter="table_percentFormatter">.</th>
                                    <th class="text-right" data-field="rsf_cost_regional" data-sortable="false" data-formatter="table_costFormatter">(L)</th>
                                    <th class="text-right" data-field="percent_regional" data-sortable="false" data-formatter="table_percentFormatter">.</th>
                                    <th class="text-right" data-field="rsf_cost_other" data-sortable="false" data-formatter="table_costFormatter">(LA)</th>
                                    <th class="text-right" data-field="percent_other" data-sortable="false" data-formatter="table_percentFormatter">.</th>
                                </tr>
                            </thead>
                            <tbody>`;

                for (let prox in res) {
                    bledend_rate = res[prox].rsf > 0 ? res[prox].rsf_cost_current / res[prox].rsf : 0;
                    adjacent_percent = res[prox].rsf_cost_adjacent > 0 ? 100 * (res[prox].rsf_cost_current - res[prox].rsf_cost_adjacent) / res[prox].rsf_cost_current : 0;
                    regional_percent = res[prox].rsf_cost_regional > 0 ? 100 * (res[prox].rsf_cost_current - res[prox].rsf_cost_regional) / res[prox].rsf_cost_current : 0;
                    other_percent = res[prox].rsf_cost_other > 0 ? 100 * (res[prox].rsf_cost_current - res[prox].rsf_cost_other) / res[prox].rsf_cost_current : 0;
                    strHtml += `<tr>
                                    <td>${ prox[0].toUpperCase() + prox.slice(1) }</td>
                                    <td class="text-right ${prox}-tr">${ Math.round(res[prox].rsf) }</td>
                                    <td class="text-right ${prox}-tr">${ Math.round(100 * bledend_rate) / 100 }</td>
                                    <td class="text-right ${prox}-tr">${ res[prox].rsf_cost_current }</td>
                                    <td class="border-none"></td>
                                    <td class="text-right ${prox}-tr">${ res[prox].rsf_cost_adjacent > 0 ? res[prox].rsf_cost_current - res[prox].rsf_cost_adjacent : 0 }</td>
                                    <td class="text-right ${prox}-tr">${ Math.round(adjacent_percent * 100) / 100 }</td>
                                    <td class="text-right ${prox}-tr">${ res[prox].rsf_cost_regional > 0 ? res[prox].rsf_cost_current - res[prox].rsf_cost_regional : 0 }</td>
                                    <td class="text-right ${prox}-tr">${ Math.round(regional_percent * 100) / 100 }</td>
                                    <td class="text-right ${prox}-tr">${ res[prox].rsf_cost_other > 0 ? res[prox].rsf_cost_current - res[prox].rsf_cost_other : 0 }</td>
                                    <td class="text-right ${prox}-tr">${ Math.round(other_percent * 100) / 100 }</td>
                                </tr>`;
                }

                strHtml += `</tbody>
                        </table>`;

                $tableContainer.html(strHtml);

                $('#opportunitySummaryTable').bootstrapTable();

                $('.loading-mask').hide();
            },
            error: function(request, error) {
                alert("Request: " + JSON.stringify(request));
            }
        });
    });
</script>
