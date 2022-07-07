<link rel="stylesheet" href="{{ asset('css/filter.css') }}">
<div class=" text-ns my-2 ">
    Show the profile of the following participants:
</div>
<div class="flex flex-wrap justify-between items-center" id="reportsupfilters">
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
        <div class="text-ns pr-2">Department</div>
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
                <li><a href="#" class="small" data-value="all" data-field="group" tabIndex="-1"><input type="checkbox" checked/>&nbsp;<span>All</span></a></li>
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

        let metric = $('input[name="metric"]:checked').attr('id');
        let metric_text = $('input[name="metric"]:checked').attr('data-text');

        let min_text = $('#min_rates').val();
        let ind_position = min_text.indexOf(' individuals');
        let min_rates = min_text.substr(0, ind_position);

        mask_height = $('body').height();
        $('.loading-mask').css('height', mask_height);
        $('.loading-mask').fadeIn();
        // Update the data of page by filter update
        $.ajax({
            url: '{{ route('getDemographicData') }}',
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                'survey_id': survey_id,
                'metric': metric,
                'position': JSON.stringify(options['position']),
                'department': JSON.stringify(options['department']),
                'group': JSON.stringify(options['group']),
                'location': JSON.stringify(options['location']),
                'category': JSON.stringify(options['category']),
                'min_rates': min_rates,
            },
            dataType: 'json',
            beforeSend: function () {
                $('.dropdown-menu').removeClass('show');
                // $('#chartContainer').empty();
                // $('#stat_avgAnnualHours').html('0');
                // $('#stat_nonparticipatedComp').html('$0');
                // $('#stat_respondentsComp').html('$0');
                // $('#stat_totalHours').html('0');
                // $('#stat_sent').html('0');
                // $('#stat_completed').html('0');
            },
            success: function (data) {
                let completed = data.stat.completed;
                let sent = data.stat.sent;
                let participated = data.stat.participated;
                let non_participants = parseInt(sent) - parseInt(completed) - parseInt(participated);
                let supportColor ="#82BD5E";
                let legalColor = "#367BC1";
                let percent_completed = Math.round((parseInt(completed) / parseInt(sent)) * 100);
                let percent_participated = Math.round((parseInt(participated) / parseInt(sent)) * 100);
                let percent_nonparticipated = Math.round((non_participants / parseInt(sent)) * 100);
                let completedCaption = completed + " individuals completed the survey. That is " + percent_completed + "% of surveys sent";
                let participatedCaption = participated + " individuals participated without completing the survey. That is " + percent_participated + "% of surveys sent";
                let NotParticipatedCaption = non_participants + " individuals did not participate. That is " + percent_nonparticipated + "% of surveys sent";
                let chart = new CanvasJS.Chart("chartContainer",
                    {
                        title:{
                            text: ""
                        },
                        legend: {
                        maxWidth: 350,
                        itemWidth: 120,
                        // fontSize: 18
                        },
                        toolTip:{
                            enabled: true,       //disable here
                            animationEnabled: false, //disable here
                            // tooltipContent: "{indexLabel}"
                            content: "{indexLabel}"
                        },
                        data: [
                            {
                            type: "doughnut",
                            showInLegend: true,
                            legendText: "{legend}",
                            animationEnabled: true,

                            dataPoints: [
                                { y: completed, indexLabel: completedCaption, color: "#77b55a",  indexLabelFontColor: "#77b55a", legend: "Completed Survey"},
                                { y: participated, indexLabel: participatedCaption, color: "#367BC1", indexLabelFontColor: "#367BC1", legend: "Participated without completing" },
                                { y: non_participants, indexLabel: NotParticipatedCaption, color: "#e15659", indexLabelFontColor: "#e15659", legend: "Did not participate"}
                            ]
                        }
                    ]
                });
                chart.render();
                $('#stat_avgAnnualHours').html(data.stat.avgAnnualHours);
                $('#stat_nonparticipatedComp').html('$' + data.stat.nonparticipatedComp);
                $('#stat_respondentsComp').html('$' + data.stat.respondentsComp);
                $('#stat_totalHours').html(data.stat.totalHours);
                $('#stat_sent').html(data.stat.sent);
                $('#stat_completed').html(data.stat.completed);

                let res = data.metric;
                $('.rate_category .rate_title span').html(metric_text);
                $('.rate_category .rate_body').empty();
                res.category.forEach(element => {
                    let percent = 0;
                    if (metric == 'invites_radio') {
                        percent = element.invite_num;
                    } else if (metric == 'surveycomplete_radio'
                            || metric == 'surveynotfinished_radio'
                            || metric == 'surveynotstarted_radio') {
                        percent = element.complete_num;
                    } else if (metric == 'costtofirminvite_radio' || metric == 'costtofirmparticipants_radio') {
                        percent = new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD', minimumFractionDigits: 0 }).format(Math.round(element.cost));
                    } else if (metric == 'percentinvite_radio' || metric == 'percentinvite_radio') {
                        percent = element.percentVal + '%';
                    } else if (metric == 'avgannualhours_radio') {
                        percent = element.hours;
                    } else {
                        percent = element.percent + '%';
                    }
                    if (element.field != "") {
                        $('.rate_category .rate_body').append(`<div class="bar-item flex justify-between items-center ${ element.field == 'xothers' ? 'border-t border-gray-400' : '' }">
                                                                    <div class="bar-index flex justify-between items-center">
                                                                        <div title="${element.field == 'xothers' ? 'All Other Categories' : element.field}">${ element.field == 'xothers' ? 'All Other Categories' : element.field }</div>
                                                                        <div>${ element.invite_num } invites</div>
                                                                    </div>
                                                                    <div class="bar-graph flex items-center justify-start">
                                                                        <div class="bg-revelation" style="width:calc(80% * ${element.percent} / 100);height:24px;"></div>
                                                                        <span class="px-1">${percent}</span>
                                                                    </div>
                                                                </div>`);
                    }
                });

                $('.rate_location .rate_title span').html(metric_text);
                $('.rate_location .rate_body').empty();
                res.location.forEach(element => {
                    let percent = 0;
                    if (metric == 'invites_radio') {
                        percent = element.invite_num;
                    } else if (metric == 'surveycomplete_radio'
                            || metric == 'surveynotfinished_radio'
                            || metric == 'surveynotstarted_radio') {
                        percent = element.complete_num;
                    } else if (metric == 'costtofirminvite_radio' || metric == 'costtofirmparticipants_radio') {
                        percent = new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD', minimumFractionDigits: 0 }).format(Math.round(element.cost));
                    } else if (metric == 'avgannualhours_radio') {
                        percent = element.hours;
                    } else {
                        percent = element.percent + '%';
                    }
                    if (element.field != "") {
                        $('.rate_location .rate_body').append(`<div class="bar-item flex justify-between items-center ${ element.field == 'xothers' ? 'border-t border-gray-400' : '' }">
                                                                    <div class="bar-index flex justify-between items-center">
                                                                        <div title="${element.field == 'xothers' ? 'All Other Cities' : element.field}">${ element.field == 'xothers' ? 'All Other Cities' : element.field }</div>
                                                                        <div>${ element.invite_num } invites</div>
                                                                    </div>
                                                                    <div class="bar-graph flex items-center justify-start">
                                                                        <div class="bg-revelation" style="width:calc(80% * ${element.percent} / 100);height:24px;"></div>
                                                                        <span class="px-1">${percent}</span>
                                                                    </div>
                                                                </div>`);
                    }
                });

                $('.rate_department .rate_title span').html(metric_text);
                $('.rate_department .rate_body').empty();
                res.department.forEach(element => {
                    let percent = 0;
                    if (metric == 'invites_radio') {
                        percent = element.invite_num;
                    } else if (metric == 'surveycomplete_radio'
                            || metric == 'surveynotfinished_radio'
                            || metric == 'surveynotstarted_radio') {
                        percent = element.complete_num;
                    } else if (metric == 'costtofirminvite_radio' || metric == 'costtofirmparticipants_radio') {
                        percent = new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD', minimumFractionDigits: 0 }).format(Math.round(element.cost));
                    } else if (metric == 'avgannualhours_radio') {
                        percent = element.hours;
                    } else {
                        percent = element.percent + '%';
                    }
                    if (element.field != "") {                    
                        $('.rate_department .rate_body').append(`<div class="bar-item flex justify-between items-center ${ element.field == 'xothers' ? 'border-t border-gray-400' : '' }">
                                                                    <div class="bar-index flex justify-between items-center">
                                                                        <div title="${element.field == 'xothers' ? 'All Other Departments' : element.field}">${ element.field == 'xothers' ? 'All Other Departments' : element.field }</div>
                                                                        <div>${ element.invite_num } invites</div>
                                                                    </div>
                                                                    <div class="bar-graph flex items-center justify-start">
                                                                        <div class="bg-revelation" style="width:calc(80% * ${element.percent} / 100);height:24px;"></div>
                                                                        <span class="px-1">${percent}</span>
                                                                    </div>
                                                                </div>`);
                    }
                });

                $('.rate_group .rate_title span').html(metric_text);
                $('.rate_group .rate_body').empty();
                res.group.forEach(element => {
                    let percent = 0;
                    if (metric == 'invites_radio') {
                        percent = element.invite_num;
                    } else if (metric == 'surveycomplete_radio'
                            || metric == 'surveynotfinished_radio'
                            || metric == 'surveynotstarted_radio') {
                        percent = element.complete_num;
                    } else if (metric == 'costtofirminvite_radio' || metric == 'costtofirmparticipants_radio') {
                        percent = new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD', minimumFractionDigits: 0 }).format(Math.round(element.cost));
                    } else if (metric == 'avgannualhours_radio') {
                        percent = element.hours;
                    } else {
                        percent = element.percent + '%';
                    }
                    if (element.field != "") {                    
                        $('.rate_group .rate_body').append(`<div class="bar-item flex justify-between items-center ${ element.field == 'xothers' ? 'border-t border-gray-400' : '' }">
                                                            <div class="bar-index flex justify-between items-center">
                                                                <div title="${element.field == 'xothers' ? 'All Other Groups' : element.field}">${ element.field == 'xothers' ? 'All Other Groups' : element.field }</div>
                                                                <div>${ element.invite_num } invites</div>
                                                            </div>
                                                            <div class="bar-graph flex items-center justify-start">
                                                                <div class="bg-revelation" style="width:calc(80% * ${element.percent} / 100);height:24px;"></div>
                                                                <span class="px-1">${percent}</span>
                                                            </div>
                                                        </div>`);
                    }
                });

                $('.rate_position .rate_title span').html(metric_text);
                $('.rate_position .rate_body').empty();
                res.position.forEach(element => {
                    let percent = 0;
                    if (metric == 'invites_radio') {
                        percent = element.invite_num;
                    } else if (metric == 'surveycomplete_radio'
                            || metric == 'surveynotfinished_radio'
                            || metric == 'surveynotstarted_radio') {
                        percent = element.complete_num;
                    } else if (metric == 'costtofirminvite_radio' || metric == 'costtofirmparticipants_radio') {
                        percent = new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD', minimumFractionDigits: 0 }).format(Math.round(element.cost));
                    } else if (metric == 'avgannualhours_radio') {
                        percent = element.hours;
                    } else {
                        percent = element.percent + '%';
                    }
                    if (element.field != "") {                    
                        $('.rate_position .rate_body').append(`<div class="bar-item flex justify-between items-center ${ element.field == 'xothers' ? 'border-t border-gray-400' : '' }">
                                                                    <div class="bar-index flex justify-between items-center">
                                                                        <div title="${element.field == 'xothers' ? 'All Other Titles' : element.field}">${ element.field == 'xothers' ? 'All Other Titles' : element.field }</div>
                                                                        <div>${ element.invite_num } invites</div>
                                                                    </div>
                                                                    <div class="bar-graph flex items-center justify-start">
                                                                        <div class="bg-revelation" style="width:calc(80% * ${element.percent} / 100);height:24px;"></div>
                                                                        <span class="px-1">${percent}</span>
                                                                    </div>
                                                                </div>`);
                    }
                });

                $('.bar-item').click(function () {
                    $progressBar = $(this).find('.progress-bar');
                    $titleBar = $(this).find('.bar-index');
                    if ($progressBar.hasClass('progress-bar-animated')) {
                        $progressBar.removeClass('progress-bar-animated');
                        $titleBar.css('background', 'none');
                    } else {
                        $progressBar.addClass('progress-bar-animated');
                        $titleBar.css('background', '#cce2f9');
                    }
                });

                $('.loading-mask').fadeOut();
            },
            error: function(request, error) {
                alert("Request: " + JSON.stringify(request));
            }
        });
    });
</script>
