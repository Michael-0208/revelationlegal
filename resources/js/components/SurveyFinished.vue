<template>
    <div class="container text-white p-3">
        <div id="message" class="row">
            <div v-html="$store.getters.getEndPage" class="col-12 py-2"></div>
        </div>

        <div v-if="$store.getters.getShowSummary" class="row">
            <div id="responseSummaryCol" class="col-9 mt-3 mb-1 px-0">
                <h3 class="text-white">Survey Response Summary - {{ name }}</h3>
            </div>
            <div class="col-3 text-right mt-3 mb-1 px-0 my-auto">
                <button @click="printSummary" id="printBtn" class="text-white btn" style="background-color: #248ABC;">Print Summary</button>
            </div>
            <div v-html="surveySummary" class="col-12 bg-white px-0">

            </div>
        </div>
    </div>
</template>
<script>
    export default {
        name: 'SurveyFinished',
        data() {
            return {
                surveySummary: '<div class="mx-auto my-auto text-center text-black"><span class="fas fa-spin fa-spinner"></span>&nbsp;&nbsp;Loading Results... Please Wait.</div>'
            }
        },
        methods: {
            printSummary() {
                $('#message').hide();
                $('#printBtn').hide();
                $('body .surveypdfhide').hide();
                $('body #google_translate_element').hide();
                $('#responseSummaryCol').removeClass('col-9').addClass('col-12');
                
                 window.print();
                
                window.onafterprint = function() {
                    $('#message').show();
                    $('#printBtn').show();
                    $('body .surveypdfhide').show();
                    $('body #google_translate_element').show(); 
                    $('#responseSummaryCol').removeClass('col-12').addClass('col-9');
                }

            }
        },
        computed: {
            name() {
                return survey_name;
            }
        },
        created() {
            localStorage.clear();

            const app = this;

            $.get('/questionnaire/summary', { survey_id, respondent_id })
                .done(function(data){
                    app.surveySummary = data;
                    console.log('test');
                })
                .catch(function(data){
                    alert('Somthing Went Wrong...');
                });
        }
    }
</script>
