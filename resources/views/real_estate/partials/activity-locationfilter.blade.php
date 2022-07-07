<link rel="stylesheet" href="{{ asset('css/filter.css') }}">
<div class="flex flex-wrap justify-between border-b-4">
    <div class="flex-auto flex-wrap border-r-2 col-md-8">
        <div class="text-ns py-2">Show utilization for the following:</div> 
        <div class="flex justify-between items-center py-2" id="reportsupfilters"> 
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
            <div id="filter-proximity" class="flex-1">
                <div class="text-ns">Proximity Factor</div>
                <div class="button-group">
                    <button type="button" class="dropdown-toggle filter-btn" data-toggle="dropdown">
                        <span class="filter-caption">All</span> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu scrollable-menu" role="menu">
                        <li><a href="#" class="small" data-value="all" data-field="proximity" tabIndex="-1"><input type="checkbox" checked/>&nbsp;<span>Deselect All</span></a></li>
                        <li><a href="#" class="small" data-value="3" data-field="proximity" tabIndex="-1"><input type="checkbox" checked/>&nbsp;<span>High</span></a></li>
                        <li><a href="#" class="small" data-value="2" data-field="proximity" tabIndex="-1"><input type="checkbox" checked/>&nbsp;<span>Medium</span></a></li>
                        <li><a href="#" class="small" data-value="1" data-field="proximity" tabIndex="-1"><input type="checkbox" checked/>&nbsp;<span>Low</span></a></li>
                        <li><a href="#" class="small" data-value="0" data-field="proximity" tabIndex="-1"><input type="checkbox" checked/>&nbsp;<span>Virtual</span></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div  class="flex-auto col-md-4"> 
        <div class=" py-2" >Metrics</div> 
        <div class="flex justify-between items-center py-2" id="reportsupfilters">
            <div id="filter-metric" class="flex-1">
                <div class="text-ns">Metric to Display</div>
                <div class="button-group">
                    <button type="button" class="dropdown-toggle filter-btn" data-toggle="dropdown">
                        <span class="filter-caption">Hours</span> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu scrollable-menu" role="menu">
                        <li><a href="#" class="small" data-value="hours" data-text="Hours" data-field="metric" tabIndex="-1"><span>Hours</span></a></li>
                        <li><a href="#" class="small" data-value="rsf" data-text="RSF" data-field="metric" tabIndex="-1"><span>RSF</span></a></li>
                        <li><a href="#" class="small" data-value="rsf_cost" data-text="RSF Cost" data-field="metric" tabIndex="-1"><span>RSF Cost</span></a></li>
                    </ul>
                </div>
            </div>
            <div id="filter-rsf" class="flex-1">
                <div class="text-ns">RSF Filter</div>
                <div class="button-group">
                    <button type="button" class="dropdown-toggle filter-btn" data-toggle="dropdown">
                        <span class="filter-caption">Current</span> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu scrollable-menu" role="menu">
                        <li><a href="#" class="small" data-value="current" data-text="Current" data-field="rsf" tabIndex="-1"><span>Current</span></a></li>
                        <li><a href="#" class="small" data-value="adjacent" data-text="Adjacent" data-field="rsf" tabIndex="-1"><span>Adjacent</span></a></li>
                        <li><a href="#" class="small" data-value="regional" data-text="Regional" data-field="rsf" tabIndex="-1"><span>Regional</span></a></li>
                        <li><a href="#" class="small" data-value="other" data-text="Other" data-field="rsf" tabIndex="-1"><span>Other</span></a></li>
                    </ul>
                </div>
            </div>
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
    origin_options['category'] = @json($data['category']);
    origin_options['category'].push('all');
    origin_options['proximity'] = ['3', '2', '1', 'all'];
    var options = [];
    options['position'] = @json($data['position']);
    options['position'].push('all');
    options['department'] = @json($data['department']);
    options['department'].push('all');
    options['group'] =  @json($data['group']);
    options['group'].push('all');
    options['category'] = @json($data['category']);
    options['category'].push('all');
    options['proximity'] = ['3', '2', '1', 'all'];
    options['metric'] = 'hours';
    options['rsf'] = 'current';
    var metric = 'hours';
    var rsf_filter = 'current';
    var layerData = new Array ();
    var questionResps = new Array ();
    
    var initRespData = @json($data['resps']);

    // Handle the event of click filter dropdown
    $( '.dropdown-menu a' ).on( 'click', function( event ) {
        let survey_id = @php echo $data['survey'] -> survey_id; @endphp;
        var $target = $( event.currentTarget ),
            val = $target.attr( 'data-value' ),
            field = $target.attr('data-field'),
            text = $target.attr('data-text'),
            $inp = $target.find( 'input' ),
            idx;
        
        let parent_id = 0;
        let parent_hours = {{ $data['total_hours'] }};
        let parent_cost = {{ $data['total_cost'] }};

        mask_height = $('body').height();
        $('.loading-mask').css('height', mask_height);

        if (field != 'rsf' && field != 'metric') {
            for (let i = 1; i < 8; i++) {
                $('#question' + i + '-layer').empty();
            }
            $('.loading-mask').fadeIn();
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
                const posAll = options[field].indexOf('all');
                if (posAll > -1) {
                    options[field].splice(posAll, 1);
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
            // Update the data of page by filter update
            $.ajax({
                url: "{{ route('realestate.filter-activity-by-location') }}",
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "survey_id": survey_id,
                    "position": JSON.stringify(options['position']),
                    "department": JSON.stringify(options['department']),
                    "group": JSON.stringify(options['group']),
                    "category": JSON.stringify(options['category']),
                    "proximity": JSON.stringify(options['proximity']),
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
                        console.log(response)
                        let rows = response.locationData;
                        let resps = response.resps;

                        if (strHtml == '') {
                            strHtml = `<div>
                                        <a class="btn btn-revelation-primary btn-block text-left service_bar" data-toggle="collapse" href="#Root1" role="button" aria-expanded="false" aria-controls="Root1">
                                            <span class="service_bar_title">Cost Distribution By Location </span> | ${response.respondents_num} respondents
                                        </a>
                                        <div class="collapse" id="Root1">
                                            <div class="card card-body">
                                                <table class="service_table">
                                                    <tbody>`;
                        }
                        for (const i in rows) {
                            strHtml += `<tr>
                                            <td class="text-sm" onclick="getLowBranchData('${i}', ${rows[i].hours}, ${rows[i].rsf}, 1, '${i}');" style="width:35%;text-align:right;">${i}</td>
                                            <td style="width:55%;" onclick="getLowBranchData('${i}', ${rows[i].hours}, ${rows[i].rsf}, 1, '${i}');">
                                                <div class="bar-graph flex items-center justify-start" style="width: 100%;">
                                                    <div class="bg-hours text-hours stat_item" style="width:calc(80% * ${rows[i].percent} / 100);height:24px;padding-top: 0;"></div>
                                                    <span class="px-1 text-hours stat_item" style="padding-top: 0; color:black;">${rows[i].percent}% | ${numberFormatter.format(rows[i].hours)}</span>
                                                    <div class="bg-rsf text-rsf stat_item" style="width:calc(80% * ${rows[i].rsf_percent} / 100);height:24px;display: none;padding-top: 0;"></div>
                                                    <span class="px-1 text-rsf stat_item" style="display: none;padding-top: 0; color:black;">${rows[i].rsf_percent}% | ${numberFormatter.format(rows[i].rsf)}</span>
                                                    <div class="bg-rsf-current text-rsf-current stat_item" style="width:calc(80% * ${rows[i].rsf_cost_current_percent} / 100);height:24px;display: none;padding-top: 0;"></div>
                                                    <span class="px-1 text-rsf-current stat_item" style="display: none;padding-top: 0; color:black;">${rows[i].rsf_cost_current_percent}% | ${numberFormatter.format(rows[i].rsf_cost_current)}</span>
                                                    <div class="bg-rsf-adjacent text-rsf-adjacent stat_item" style="width:calc(80% * ${rows[i].rsf_cost_adjacent_percent} / 100);height:24px;display: none;padding-top: 0;"></div>
                                                    <span class="px-1 text-rsf-adjacent stat_item" style="display: none;padding-top: 0;">${rows[i].rsf_cost_adjacent_percent}% | ${numberFormatter.format(rows[i].rsf_cost_adjacent)}</span>
                                                    <div class="bg-rsf-regional text-rsf-regional stat_item" style="width:calc(80% * ${rows[i].rsf_cost_regional_percent} / 100);height:24px;display: none;padding-top: 0;"></div>
                                                    <span class="px-1 text-rsf-regional stat_item" style="display: none;padding-top: 0;">${rows[i].rsf_cost_regional_percent}% | ${numberFormatter.format(rows[i].rsf_cost_regional)}</span>
                                                    <div class="bg-rsf-other text-rsf-other stat_item" style="width:calc(80% * ${rows[i].rsf_cost_regional_percent} / 100);height:24px;display: none;padding-top: 0;"></div>
                                                    <span class="px-1 text-rsf-other stat_item" style="display: none;padding-top: 0;">${rows[i].rsf_cost_regional_percent}% | ${numberFormatter.format(rows[i].rsf_cost_other)}</span>
                                                </div>
                                            </td>
                                            <td class="btn-detailList" style="width:10%;text-align:right;">
                                                <button class="btn btn-revelation-primary" onclick="getDetailRespByLocation('${i}');" title="View Participants for ${i}">
                                                    <svg class="respDetailBtn" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1.25em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 640 512"><path d="M96 224c35.3 0 64-28.7 64-64s-28.7-64-64-64s-64 28.7-64 64s28.7 64 64 64zm448 0c35.3 0 64-28.7 64-64s-28.7-64-64-64s-64 28.7-64 64s28.7 64 64 64zm32 32h-64c-17.6 0-33.5 7.1-45.1 18.6c40.3 22.1 68.9 62 75.1 109.4h66c17.7 0 32-14.3 32-32v-32c0-35.3-28.7-64-64-64zm-256 0c61.9 0 112-50.1 112-112S381.9 32 320 32S208 82.1 208 144s50.1 112 112 112zm76.8 32h-8.3c-20.8 10-43.9 16-68.5 16s-47.6-6-68.5-16h-8.3C179.6 288 128 339.6 128 403.2V432c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48v-28.8c0-63.6-51.6-115.2-115.2-115.2zm-223.7-13.4C161.5 263.1 145.6 256 128 256H64c-35.3 0-64 28.7-64 64v32c0 17.7 14.3 32 32 32h65.9c6.3-47.4 34.9-87.3 75.2-109.4z" fill="white"/></svg>
                                                </button>    
                                            </td>
                                        </tr>`;
                        }

                        strHtml += `    </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>`;

                        $('#question1-layer').html(strHtml);
                        $('#question1-layer .btn-revelation-primary.btn-block').click();

                        initRespData = resps;
                        $('#totalInfo .text-hours b').html(numberFormatter.format(response.total_hours));
                        $('#totalInfo .text-rsf b').html(numberFormatter.format(response.total_rsf));
                        $('#totalInfo .text-rsf-current b').html(numberFormatter.format(response.total_rsf_cost.current));
                        $('#totalInfo .text-rsf-adjacent b').html(numberFormatter.format(response.total_rsf_cost.adjacent));
                        $('#totalInfo .text-rsf-regional b').html(numberFormatter.format(response.total_rsf_cost.regional));
                        $('#totalInfo .text-rsf-other b').html(numberFormatter.format(response.total_rsf_cost.other));

                        $('.loading-mask').fadeOut();

                        $('.service_table tbody tr').click(function() {
                            $(this).parent().find('tr').removeClass('selected-tr');
                            $(this).addClass('selected-tr');
                        });
                    }
                },
                error: function(request, error) {
                    alert("Request: " + JSON.stringify(request));
                }
            });
        } else {
            $('#filter-' + field).find('.filter-caption').html(text);
            $('.stat_item').hide();
            if (field == 'rsf') {
                $('.rsf-cost-item').show();
                rsf_filter = val;
                $('.rsf-item').hide();
                $('.text-rsf-' + rsf_filter).show();
            } else {
                metric = val;
                if (metric == 'rsf_cost') {
                    $('.rsf-cost-item').show();
                    $('.rsf-item').hide();
                    $('.text-rsf-' + rsf_filter).show();
                } else {
                    $('.text-' + metric).show();
                }
            }
            options[field] = val;
        }


        $( event.target ).blur();
    });
</script>
