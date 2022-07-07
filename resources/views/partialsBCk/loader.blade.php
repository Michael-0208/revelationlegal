<style>
    .overlay {
        width: 100%;
        height: 100%;
        position: fixed;
        top: 0px;
        left: 0px;
        background-color: rgba(0,0,0,0.5);
        z-index: 9999;
    }

</style>


<div class="overlay" style="display: none;">
    <div class="row" style="height: 100vh;">
        <div class="col-12 h-20 my-auto text-center">
            <span class="fa fa-4x fa-spinner fa-spin text-white"></span>
        </div>
    </div>
</div>


<script>
    function showLoader()
    {
        $('html, body').css({'margin': 0, 'height': '100%', 'overflow': 'hidden'});
        $('.overlay').show().fadeIn('slow');
    }

    function hideLoader()
    {
        // $('.modal').modal('hide');
        $('html, body').css({'margin': '', 'height': '', 'overflow': ''});
        $('.overlay').fadeIn('slow', function(){
            $('.overlay').hide();
        });
    }
</script>
