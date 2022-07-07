<ul class="respondents_list">
        @foreach($resps as $resp)
            <li class="respondent" id="{{$resp->resp_id}}">{{$resp->resp_last}}, {{$resp->resp_first}}</li>
        @endforeach
</ul>