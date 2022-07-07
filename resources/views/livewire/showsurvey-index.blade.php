<div>
<h1>{{$survey->survey_name}}</h1>
    <div class="p-3">
        <div>
            <p>Survey is Currently: {{$survey_active}}</p>
            <p>Response Rate: {{$total_resp}} of {{$sent}} ({{$percent_total}}%)</p>
            <p>Completion Rate: {{$completed_resp}}  of {{$total_resp}} ({{$percent_completed}}%)</p>
        </div>


    </div>
</div>
<script>
    setTimeout(function (){
        $("#sidebar").show();
    },500)
</script>