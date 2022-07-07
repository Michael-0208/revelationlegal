<link rel="stylesheet" href="{{ asset('css/filter.css') }}">
<div class="flex flex-wrap justify-between items-center" id="reportsupfilters">  
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
    {{-- <div id="filter-surveystatus" class="flex-1">
        <div class="text-ns">Survey Status</div>
        <div class="button-group">
            <button type="button" class=" dropdown-toggle filter-btn" data-toggle="dropdown">
                <span class="filter-caption">completed the survey</span> <span class="caret"></span>
            </button>
            <ul class="dropdown-menu scrollable-menu" role="menu">
                <li><a href="#" class="small" data-value="all" data-field="survey_status" tabIndex="-1">&nbsp;<span>All</a></span></li>
                <li><a href="#" class="small" data-value="completed" data-field="survey_status" tabIndex="-1">&nbsp;<span>completed the survey</span></a></li>
                <li><a href="#" class="small" data-value="participated" data-field="survey_status" tabIndex="-1">&nbsp;<span>participated without completing the survey</span></a></li>
                <li><a href="#" class="small" data-value="option5" data-field="survey_status" tabIndex="-1">&nbsp;<span>did not participate</span></a></li>
            </ul>
        </div>
    </div> --}}

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
        mask_height = $('body').height();
        $('.loading-mask').css('height', mask_height);
        $('.loading-mask').fadeIn();
        // Update the data of page by filter update
        $.ajax({
            url: '{{ route('realestate-getRespList') }}',
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "survey_id": survey_id,
                "position": JSON.stringify(options['position']),
                "department": JSON.stringify(options['department']),
                "group": JSON.stringify(options['group']),
                "category": JSON.stringify(options['category']),
                "location": JSON.stringify(options['location']),
                "survey_status": JSON.stringify(options['survey_status']),
            },
            dataType: 'json',
            beforeSend: function () {
                $('.dropdown-menu').removeClass('show');
                $('#detailHoursTable').empty();
                $('#respondentChart').empty();
                $('#respondent_data').html(`&larr; Select a respondent from the list on the left to get started.`);
                $('#legal_tbody').empty();
                $('#support_tbody').empty();
                $('#CategoryChartContainer').empty();
                $('#chartContainer').empty();
                $('#respondentList').empty();
                $('#individualReportContainer').css('display', 'none');
                $('.third_part').hide();
                $('.info-high span').text('0%');
                $('.info-med span').text('0%');
                $('.info-low span').text('0%');
            },
            success: function (data) {
                var respDataAry = data.resps;
                var rsfData = data.rsf_percent_data;
                $('#respondentList').empty();
                respDataAry.forEach(resp => {
                    let high_percent = resp.total_hours > 0 ? Math.round(100 * resp.prox_high_hours / resp.total_hours) : 0;
                    let med_percent = resp.total_hours > 0 ? Math.round(100 * resp.prox_medium_hours / resp.total_hours) : 0;
                    let virtual_percent = resp.total_hours > 0 ? Math.round(100 * resp.prox_virtual_hours / resp.total_hours) : 0;
                    let low_percent = resp.prox_low_hours > 0 ? 100 - high_percent - med_percent : 0;
                    $('#respondentList').append(`<div class="resp_item row resp-${resp.resp_id}" onclick="selectRespondent(${resp.resp_id}, ${resp.support_hours}, ${resp.legal_hours}, ${low_percent}, ${med_percent}, ${high_percent});">
                                                        <div class="col-5 text-right" title="${resp.resp_last}, ${resp.resp_first}">${resp.resp_last}, ${resp.resp_first}</div>
                                                        <div class="col-7">
                                                            <div class="high-bar" data-percent="${high_percent}" style="width:${high_percent}%;"></div>
                                                            <div class="medium-bar" data-percent="${med_percent}" style="width:${med_percent}%;"></div>
                                                            <div class="virtual-bar" data-percent="${virtual_percent}" style="width:${virtual_percent}%;"></div>
                                                            <div class="low-bar" data-percent="${low_percent}" style="width:${low_percent}%;"></div>
                                                        </div>
                                                    </div>`);
                });
                $('.info-high span').text(rsfData.high_percent + '%');
                $('.info-med span').text(rsfData.med_percent + '%');
                $('.info-low span').text(rsfData.low_percent + '%');
                $('.info-virtual span').text(rsfData.low_percent + '%');
                $('#searchCloseBtn').css('display', 'none');
                $('.loading-mask').fadeOut();
            },
            error: function(request, error) {
                alert("Request: " + JSON.stringify(request));
            }
        });
    });
</script>
