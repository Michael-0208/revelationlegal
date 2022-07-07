<link rel="stylesheet" href="{{ asset('css/filter.css') }}">
<div class="flex flex-wrap justify-between items-center " id="reportsupfilters">
    <div id="filter-position" class="flex-1">
        <div class="text-ns">Position</div>
        <div class="button-group">
            <button type="button" class=" dropdown-toggle filter-btn" data-toggle="dropdown">
                <span class="filter-caption">All</span> <span class="caret"></span>
            </button>
            <ul class="dropdown-menu scrollable-menu" role="menu">
                <li><a href="#" class="small" data-value="all" data-field="position" tabIndex="-1"><input type="checkbox" />&nbsp;<span>Select All</span></a></li>
                @foreach ($data['position'] as $position)
                    <li><a href="#" class="small" data-value="{{ $position }}" data-field="position" tabIndex="-1"><input type="checkbox" />&nbsp;<span>{{ $position }}</span></a></li>
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
                <li><a href="#" class="small" data-value="all" data-field="department" tabIndex="-1"><input type="checkbox" />&nbsp;<span>Select All</span></a></li>
                @foreach ($data['department'] as $department)
                    <li><a href="#" class="small" data-value="{{ $department }}" data-field="department" tabIndex="-1"><input type="checkbox" />&nbsp;<span>{{ $department }}</span></a></li>
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
                <li><a href="#" class="small" data-value="all" data-field="group" tabIndex="-1"><input type="checkbox" />&nbsp;<span>Select All</span></a></li>
                @foreach ($data['group'] as $group)
                    <li><a href="#" class="small" data-value="{{ $group }}" data-field="group" tabIndex="-1"><input type="checkbox" />&nbsp;<span>{{ $group }}</span></a></li>
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
                <li><a href="#" class="small" data-value="all" data-field="location" tabIndex="-1"><input type="checkbox" />&nbsp;<span>Select All</span></a></li>
                @foreach ($data['location'] as $location)
                    <li><a href="#" class="small" data-value="{{ $location }}" data-field="location" tabIndex="-1"><input type="checkbox" />&nbsp;<span>{{ $location }}</span></a></li>
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
                <li><a href="#" class="small" data-value="all" data-field="category" tabIndex="-1"><input type="checkbox" />&nbsp;<span>Select All</span></a></li>
                @foreach ($data['category'] as $category)
                    <li><a href="#" class="small" data-value="{{ $category }}" data-field="category" tabIndex="-1"><input type="checkbox" />&nbsp;<span>{{ $category }}</span></a></li>
                @endforeach
            </ul>
        </div>
    </div> 
    <div id="filter-surveystatus" class="flex-1 text-right">

        <button class="revnor-btn mx-2 my-4 mb-3 mb-md-0" id="filterData">Filter Data</button>




        {{-- <button type="button" class="btn btn-default btn-sm dropdown-toggle filter-btn" data-toggle="dropdown">
                <span class="filter-caption">completed the survey</span> <span class="caret"></span>
            </button>
            <ul class="dropdown-menu scrollable-menu" role="menu">
                
                <li><a href="#" class="small" data-value="1" data-field="survey_status" tabIndex="-1">&nbsp;<span>All</a></span></li>
                
                <li><a href="#" class="small" data-value="1" data-field="survey_status" tabIndex="-1">&nbsp;<span>completed the survey</span></a></li>
                
                <li><a href="#" class="small" data-value="0" data-field="survey_status" tabIndex="-1">&nbsp;<span>participated without completing the survey</span></a></li>
               {{--  <li><a href="#" class="small" data-value="" data-field="survey_status" tabIndex="-1">&nbsp;<span>did not participate</span></a></li> --}
            </ul> --}}

    </div>
</div>

<script>
    // Define filter variables
    var origin_options = [];
    origin_options['position'] = @json($data['position']);
    origin_options['position'].push('all');
    origin_options['department'] = @json($data['department']);
   origin_options['department'].push('all');
    origin_options['group'] = @json($data['group']);
   origin_options['group'].push('all');
    origin_options['location'] = @json($data['location']);
    origin_options['location'].push('all');
    origin_options['category'] = @json($data['category']);
    origin_options['category'].push('all');
    origin_options['survey_status'] = [];
    var options = Array();
    options['position'] = [];
    options['department'] = [];
    options['group'] = [];
    options['location'] = [];
    options['category'] = [];
    /*  options['position'] = @json($data['position']);
    //  options['position'].push('all');
     options['department'] = @json($data['department']);
     options['department'].push('all');
     options['group'] = @json($data['group']);
     options['group'].push('all');
     options['location'] = @json($data['location']);
     options['location'].push('all');
     options['category'] = @json($data['category']);
     options['category'].push('all');
     options['survey_status'] = []; */



    $('#filter-position').children('.button-group').children('ul').children('li').find('input[type="checkbox"]').eq(0).on('change', function() {

            $('#filter-position').children('.button-group').children('ul').children('li').find(
                'input[type="checkbox"]').prop('checked', this.checked);

            if ($(this).prop('checked')) {
                $('#filter-position').children('.button-group').children('button').children('.filter-caption').text(
                    'All');
                $(this).siblings('span').text('Deselect All');
            } else {
                $('#filter-position').children('.button-group').children('button').children('.filter-caption').text(
                    'None');
                $(this).siblings('span').text('Select All');
            }

        });


    $('#filter-department').children('.button-group').children('ul').children('li').find('input[type="checkbox"]').eq(0).on('change', function() {
            $('#filter-department').children('.button-group').children('ul').children('li').find(
                'input[type="checkbox"]').prop('checked', this.checked);

            if ($(this).prop('checked')) {
                $('#filter-department').children('.button-group').children('button').children('.filter-caption')
                    .text(
                        'All');
                $(this).siblings('span').text('Deselect All');
            } else {
                $('#filter-department').children('.button-group').children('button').children('.filter-caption')
                    .text(
                        'None');
                $(this).siblings('span').text('Select All');
            }
        });

    $('#filter-group').children('.button-group').children('ul').children('li').find('input[type="checkbox"]').eq(0).on('change',
        function() {
            $('#filter-group').children('.button-group').children('ul').children('li').find(
                'input[type="checkbox"]').prop('checked', this.checked);

            if ($(this).prop('checked')) {
                $('#filter-group').children('.button-group').children('button').children('.filter-caption').text(
                    'All');
                $(this).siblings('span').text('Deselect All');
            } else {
                $('#filter-group').children('.button-group').children('button').children('.filter-caption').text(
                    'None');
                $(this).siblings('span').text('Select All');
            }
        });

    $('#filter-location').children('.button-group').children('ul').children('li').find('input[type="checkbox"]').eq(0).on('change', function() {
            $('#filter-location').children('.button-group').children('ul').children('li').find(
                'input[type="checkbox"]').prop('checked', this.checked);
            if ($(this).prop('checked')) {
                $('#filter-location').children('.button-group').children('button').children('.filter-caption').text(
                    'All');
                $(this).siblings('span').text('Deselect All');
            } else {
                $('#filter-location').children('.button-group').children('button').children('.filter-caption').text(
                    'None');
                $(this).siblings('span').text('Select All');
            }

        });

    $('#filter-category').children('.button-group').children('ul').children('li').find('input[type="checkbox"]').eq(0).on('change', function() {
            $('#filter-category').children('.button-group').children('ul').children('li').find(
                'input[type="checkbox"]').prop('checked', this.checked);

            if ($(this).prop('checked')) {
                $('#filter-category').children('.button-group').children('button').children('.filter-caption').text(
                    'All');
                $(this).siblings('span').text('Deselect All');
            } else {
                $('#filter-category').children('.button-group').children('button').children('.filter-caption').text(
                    'None');
                $(this).siblings('span').text('Select All');
            }
        });


    // Handle the event of click filter dropdown
    $('body').on('click', '#filterData', function() {

        var filterOption = $('.dropdown-menu');
        var filterCount = $('.dropdown-menu').length;

        //options = [];
        for (let i = 0; i < filterCount; i++) {
            var field = filterOption.eq(i).find('a').data('field');

           

            if (field == 'position') {
                // alert(field);
                options['position'] = [];
                var PositionFilterValue = $('#filter-position').children('.button-group').children('ul')
                    .children('li').find('input[type="checkbox"]:checked').parent();

                var PositionFilterValueLength = PositionFilterValue.length;


                var SelectedValueText = $('#filter-position').children('.button-group').children('.filter-btn')
                    .find('span.filter-caption');

                console.log(origin_options['position'].length);
                if (PositionFilterValueLength == 1) {

                    SelectedValueText.text(PositionFilterValue.data('value'));
                }else if(PositionFilterValueLength < origin_options['position'].length && PositionFilterValueLength > 0){
                    SelectedValueText.text('Multiple Values');
                }

                //
                console.log(PositionFilterValueLength);
                for (let j = 0; j < PositionFilterValueLength; j++) {


                    options[field][j] = PositionFilterValue.eq(j).data('value');


                }


            }

            if (field == 'department') {
                // alert(field);
                options['department'] = [];
                var DepartmentFilterValue = $('#filter-department').children('.button-group').children('ul')
                    .children('li').find('input[type="checkbox"]:checked').parent();
                var DepartmentFilterValueLength = DepartmentFilterValue.length;
                var SelectedValueText = $('#filter-department').children('.button-group').children(
                    '.filter-btn').find('span.filter-caption');
                console.log(DepartmentFilterValueLength);

                if (DepartmentFilterValueLength == 1) {

                    SelectedValueText.text(DepartmentFilterValue.data('value'));
                }else if(DepartmentFilterValueLength < origin_options['department'].length && DepartmentFilterValueLength > 0){
                    SelectedValueText.text('Multiple Values');
                }


                for (let k = 0; k < DepartmentFilterValueLength; k++) {


                    options[field][k] = DepartmentFilterValue.eq(k).data('value');


                }
                //    console.log(options);

            }
            if (field == 'group') {
                // alert(field);

                options['group'] = [];

                var GroupFilterValue = $('#filter-group').children('.button-group').children('ul').children(
                    'li').find('input[type="checkbox"]:checked').parent();
                var SelectedValueText = $('#filter-group').children('.button-group').children('.filter-btn')
                    .find('span.filter-caption');
                var GroupFilterValueLength = GroupFilterValue.length;

                if (GroupFilterValueLength == 1) {

                    SelectedValueText.text(GroupFilterValue.data('value'));
                }else if(GroupFilterValueLength < origin_options['group'].length && GroupFilterValueLength > 0){
                    SelectedValueText.text('Multiple Values');
                } 

                console.log(GroupFilterValueLength);
                for (let l = 0; l < GroupFilterValueLength; l++) {


                    options[field][l] = GroupFilterValue.eq(l).data('value');


                }
                //console.log(options);
            }
            if (field == 'location') {
                //    alert(field);

                options['location'] = [];
                var LocationFilterValue = $('#filter-location').children('.button-group').children('ul')
                    .children('li').find('input[type="checkbox"]:checked').parent();
                var SelectedValueText = $('#filter-location').children('.button-group').children('.filter-btn')
                    .find('span.filter-caption');
                var LocationFilterValueLength = LocationFilterValue.length;

                if (LocationFilterValueLength == 1) {

                    SelectedValueText.text(LocationFilterValue.data('value'));
                }else if(LocationFilterValueLength < origin_options['location'].length && LocationFilterValueLength > 0){
                    SelectedValueText.text('Multiple Values');
                }

                console.log(LocationFilterValueLength);
                for (let m = 0; m < LocationFilterValueLength; m++) {


                    options[field][m] = LocationFilterValue.eq(m).data('value');


                }
                //console.log(options);
            }
            if (field == 'category') {
                // alert(field);

                options['category'] = [];
                var CategoryFilterValue = $('#filter-category').children('.button-group').children('ul')
                    .children('li').find('input[type="checkbox"]:checked').parent();
                var CategoryFilterValueLength = CategoryFilterValue.length;
                var SelectedValueText = $('#filter-category').children('.button-group').children('.filter-btn')
                    .find('span.filter-caption');

                if (CategoryFilterValueLength == 1) {

                    SelectedValueText.text(CategoryFilterValue.data('value'));
                }else if(CategoryFilterValueLength < origin_options['category'].length && CategoryFilterValueLength > 0){
                    SelectedValueText.text('Multiple Values');
                }

                console.log(CategoryFilterValueLength);
                for (let n = 0; n < CategoryFilterValueLength; n++) {


                    options[field][n] = CategoryFilterValue.eq(n).data('value');


                }

            }


        }

        //  return;
        /*
        return; */

        /* alert('test');
        return; */
        let survey_id = @php echo $data['survey']->survey_id; @endphp;
        /*  var $target = $(event.currentTarget), */
        /* val = $target.attr('data-value'),
        field = $target.attr('data-field'),
        $inp = $target.find('input'),
        idx; */




        //console.log(val);

        /*  if (val == 'all') {
             if ((idx = options[field].indexOf(val)) > -1) {
                 options[field] = [];
                 setTimeout(function() {
                     $('#filter-' + field).find('input').prop('checked', false);
                 }, 0);
                 $(this).find('span').html('Select All');
             } else {
                 options[field] = origin_options[field];
                 setTimeout(function() {
                     $('#filter-' + field).find('input').prop('checked', true);
                     $('#filter-' + field).find('.filter-caption').html('All');
                 }, 0);
                 $(this).find('span').html('Deselect All');
             }
         } else {
             if ((idx = options[field].indexOf(val)) > -1) {
                 options[field].splice(idx, 1);
                 setTimeout(function() {
                     $inp.prop('checked', false)
                 }, 0);
             } else {
                 options[field].push(val);
                 setTimeout(function() {
                     $inp.prop('checked', true)
                 }, 0);
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
                     setTimeout(function() {
                         $('#filter-' + field).find('input').prop('checked', false);
                         $('#filter-' + field).find('a[data-value="all"] span').html('Select All');
                     }, 0);
                 } else {
                     $('#filter-' + field).find('.filter-caption').html(val);
                 }
                 break;

             case 2:
                 if ((idx = options[field].indexOf('all')) > -1) {
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
                 if ((idx = options[field].indexOf('all')) < 0) {
                     $('#filter-' + field).find('.filter-caption').html('All');
                     $('#filter-' + field).find('[data-value="all"]').find('input').prop('checked', true);
                 } else {
                     $('#filter-' + field).find('.filter-caption').html('Multiple values');
                 }
                 break;
             default:
                 $('#filter-' + field).find('.filter-caption').html('Multiple values');
                 break;
         } */

        /* onsole.log(options);
        return; */
        console.log(options);

        $(event.target).blur();
        mask_height = $('body').height();
        $('.loading-mask').css('height', mask_height);
        $('.loading-mask').fadeIn();
        // Update the data of page by filter update
        $.ajax({
            url: '{{ route('getRespondentList') }}',
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
            beforeSend: function() {
                $('#detailHoursTable').empty();
                $('#respondentChart').empty();
                $('#respondent_data').html(
                    `&larr; Select a respondent from the list on the left to get started.`);
                $('#legal_tbody').empty();
                $('#support_tbody').empty();
                $('#CategoryChartContainer').empty();
                $('#chartContainer').empty();
                $('#respondentList').empty();
                $('#individualReportContainer').css('display', 'none');
                $('.third_part').hide();
                $('.dropdown-menu').removeClass('show');
            },
            success: function(data) {
                var respDataAry = data;
                var supportPointAry = new Array();
                var legalPointAry = new Array();
                let supportColor = "#82BD5E";
                let legalColor = "#367BC1";
                respDataAry.forEach(resp => {
                    let support_percent = Math.round(100 * resp.support_hours / (resp
                        .support_hours + (resp.legal_hours ? resp.legal_hours : 0)));
                    let legal_percent = resp.legal_hours > 0 ? 100 - support_percent : 0;
                    $('#respondentList').append(`<div class="resp_item row" onclick="selectRespondent(${resp.resp_id}, ${resp.support_hours}, ${resp.legal_hours});">
                                                    <div class="col-5 text-right">${resp.resp_last}, ${resp.resp_first}</div>
                                                    <div class="col-7">
                                                        <div class="support-bar" style="width:${support_percent}%;"></div>
                                                        <div class="legal-bar" style="width:${legal_percent}%;"></div>
                                                    </div>
                                                </div>`);
                });
                $('.loading-mask').fadeOut();
            },
            error: function(request, error) {
                alert("Request: " + JSON.stringify(request));
            }
        });
    });
</script>

