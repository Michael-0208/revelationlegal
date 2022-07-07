<link rel="stylesheet" href="{{ asset('css/filter.css') }}">
<div class="flex justify-between items-center" id="reportsupfilters">
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
                <li><a href="#" class="small" data-value="3" data-field="proximity" tabIndex="-1"><span>4. Virtual</span></a></li>
            </ul>
        </div>
    </div>

</div>

<script>
    // Define filter variable
   
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
            url: "{{ route('realestate.filter-opportunity-detail') }}",
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
                rows = res.rows;
                var firstQr='';
                total_hours = res.total_hours;
                total_rsf_cost = res.total_rsf_cost;

                $tableContainer = $('.tableContainer');

                strHtml = `<table id="opportunityDetailTable" class="table table-bordered" style="margin:30px 0">
                                <thead>
                                    <tr>`;

                for (i = 0; i <= depthQuestion; i++) {
                    if(i < 2){
                        strHtml += `<th style="border-bottom:none;"></th>`; 
                    }
                    
                }

                strHtml += `<th class="text-center border-solid border-right" colspan="3">Current Cost</th>`;
                strHtml += `<th class="text-center" colspan="3">Potential Savings</th>`;
                strHtml += `<tr>`;

                for (i = 0; i <= depthQuestion; i++) {
                    if (i == depthQuestion) {
                        
                    } else {
                        strHtml += `<th class="jump-th">
                                        <div class="flex justify-center jump-btn">
                                            <svg onclick="JumpToQuestionsByDepth(${i});" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;cursor: pointer;" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 1024 1024"><path d="M328 544h368c4.4 0 8-3.6 8-8v-48c0-4.4-3.6-8-8-8H328c-4.4 0-8 3.6-8 8v48c0 4.4 3.6 8 8 8z" fill="currentColor"/><path d="M880 112H144c-17.7 0-32 14.3-32 32v736c0 17.7 14.3 32 32 32h736c17.7 0 32-14.3 32-32V144c0-17.7-14.3-32-32-32zm-40 728H184V184h656v656z" fill="currentColor"/></svg>
                                        </div>
                                    </th>`;
                    }
                }

                strHtml += `<th class="text-center">Proximity Factor</th>`;
                strHtml += `<th class="text-center">Hours</th>`;
                strHtml += `<th class="text-center border-right">RSF Cost(Current)</th>`;
                strHtml += `<th class="text-center">
                                <div class="">
                                    <span class="">RSF Cost</span>
                                    <select class="custom-select custom-select-sm" name="rsf_cost_sort" id="rsf_cost_sort" style="width:auto;">
                                        <option value="Adjacent">Adjacent</option>
                                        <option value="Regional">Regional</option>
                                        <option value="OTHER">Other</option>
                                    </select>
                                </div>
                            </th>`;
                strHtml += `<th class="text-center" scope="col">Variance</th>
                            <th class="text-center" scope="col">Percentage</th>
                        </tr>`;

                strHtml += 
                        `<tr>
                            <th class="text-right" colspan="2">Grand Total</th>
                            <th class="text-center"></th>
                            <th class="text-center">${numberFormatter.format(Math.round(total_hours))}</th>
                            <th class="text-center border-right">${numberFormatter.format(Math.round(total_rsf_cost.current))}</th>
                            <th class="text-center">
                                <span class="text-Adjacent">${formatter.format(Math.round(total_rsf_cost.adjacent))}</span>
                                <span class="text-Regional">${formatter.format(Math.round(total_rsf_cost.regional))}</span>
                                <span class="text-OTHER">${formatter.format(Math.round(total_rsf_cost.other))}</span>
                            </th>
                            <th class="text-center">
                                <span class="text-Adjacent">${formatter.format(Math.round(total_rsf_cost.variance_adjacent))}</span>
                                <span class="text-Regional">${formatter.format(Math.round(total_rsf_cost.variance_regional))}</span>
                                <span class="text-OTHER">${formatter.format(Math.round(total_rsf_cost.variance_other))}</span>
                            </th>
                            <th class="text-center">
                                <span class="text-Adjacent">${numberFormatter.format(Math.round(100 * total_rsf_cost.variance_adjacent / total_rsf_cost.current))}%</span>
                                <span class="text-Regional">${numberFormatter.format(Math.round(100 * total_rsf_cost.variance_regional / total_rsf_cost.current))}%</span>
                                <span class="text-OTHER">${numberFormatter.format(Math.round(100 * total_rsf_cost.variance_other / total_rsf_cost.current))}%</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>`;

                for (const i in rows) {
                    questionDescAry = rows[i].question_desc.split("..");
                    if(firstQr != questionDescAry[0]) {
                        firstQr =  questionDescAry[0]; 
                        console.log(questionDescAry[0]);
                        strHtml += `<tr><td  title="${questionDescAry[0]} "><strong>${questionDescAry[0]}</strong></td></tr>`;
                    }
                    strHtml += `<tr>`;
                    
                    for (j = 0; j < questionDescAry.length; j++) {
                        
                        strHtml += `<td class="questionDescTD${j}" title="${questionDescAry[j]}">${questionDescAry[j]}</td>`;
                    }
                    prox_desc = 'High';
                    tr_color = 'high-tr';
                    switch (rows[i].proximity_factor) {
                        case 1:
                            prox_desc = 'Low';
                            tr_color = 'low-tr';
                            break;
                    
                        case 2:
                            prox_desc = 'Med';
                            tr_color = 'med-tr';
                            break;
                    
                        case 3:
                            prox_desc = 'High';
                            tr_color = 'high-tr';
                            break;
                    
                        default:
                            break;
                    }

                    



                    /* $.each(questionDescAry, function(i,question_desc) {
                        
                        if(i != 0){
                           // console.log(i);
                            strHtml += `<td class="questionDescTD${i}" title="${question_desc}">${question_desc}</td>`;
                        }
                    });  */                                 
                    strHtml += `<td class="${tr_color} text-center content-td">${prox_desc}</td>
                                <td class="${tr_color} text-center content-td">${numberFormatter.format(Math.round(rows[i].hours))}</td>
                                <td class="${tr_color} border-right text-center content-td">${numberFormatter.format(Math.round(rows[i].rsf_cost_current))}</td>
                                <td class="${tr_color} text-center content-td">
                                    <span class="text-Adjacent">${rows[i].rsf_cost_adjacent > 0 ? formatter.format(Math.round(rows[i].rsf_cost_adjacent)) : ''}</span>
                                    <span class="text-Regional">${rows[i].rsf_cost_regional > 0 ? formatter.format(Math.round(rows[i].rsf_cost_regional)) : ''}</span>
                                    <span class="text-OTHER">${rows[i].rsf_cost_other > 0 ? formatter.format(Math.round(rows[i].rsf_cost_other)) : ''}</span>
                                </td>
                                <td class="${tr_color} text-center content-td">
                                    <span class="text-Adjacent">${rows[i].rsf_cost_adjacent > 0 ? formatter.format(Math.round(rows[i].variance_adjacent)) : ''}</span>
                                    <span class="text-Regional">${rows[i].rsf_cost_regional > 0 ? formatter.format(Math.round(rows[i].variance_regional)) : ''}</span>
                                    <span class="text-OTHER">${rows[i].rsf_cost_other ? formatter.format(Math.round(rows[i].variance_other)) : ''}</span>
                                </td>
                                <td class="${tr_color} text-center content-td">
                                    <span class="text-Adjacent">${rows[i].rsf_cost_adjacent > 0 ? numberFormatter.format(Math.round(100 * rows[i].variance_adjacent / rows[i].rsf_cost_current)) + '%' : ''}</span>
                                    <span class="text-Regional">${rows[i].rsf_cost_regional > 0 ? numberFormatter.format(Math.round(100 * rows[i].variance_regional / rows[i].rsf_cost_current)) + '%' : ''}</span>
                                    <span class="text-OTHER">${rows[i].rsf_cost_other > 0 ? numberFormatter.format(Math.round(100 * rows[i].variance_other / rows[i].rsf_cost_current)) + '%' : ''}</span>
                                </td>`;
                    strHtml += `</tr>`;
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
                
                $('#rsf_cost_sort').click(function () {
                    val = $(this).val();
                    $('.text-Adjacent').hide();
                    $('.text-Regional').hide();
                    $('.text-OTHER').hide();
                    $(`.text-${val}`).show();
                });
                setTimeout(() => {
                //     $('body .questionDescTD0').parent('tr').css('border-top','40px solid white');
                }, 500);
                $('.loading-mask').hide();
            },
            error: function(request, error) {
                alert("Request: " + JSON.stringify(request));
            }
        });
    });
</script>
