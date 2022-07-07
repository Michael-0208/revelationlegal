<x-app-layout>
    <x-slot name="header">

    </x-slot>
    <link rel="stylesheet" href="{{ asset('css/additional-styling.css') }}">
    <div class=" flex justify-center">
        <div class="container">
            <div class="cont-mtitle mt-4 mt-md-5">
                <h1 class="text-survey font-bold text-lg mb-0">Create a Support ticket</h1>
            </div>
            <div class="emailFormArea my-3 my-md-5">
                @if (isset($data['sent']) && $data['sent'] == 1)
                    <p class="alert alert-success">Message sent. We will get back to you soon</p>
                @endif                
                @if (isset($data['error']) && $data['error'] == 1)
                    <p class="alert alert-success">Please input a validated email</p>
                @endif                
                <form id="emailForm" method="POST" action="{{ route('support.contact') }}">
                    @csrf
                    <div class="form-group flex flex-wrap  items-center justify-between">
                        <label for="email" class="col-12 col-md-4 pl-0"><span>Contact E-mail:</span> </label>
                        <input type="email" class="form-control col-12 col-md-8" placeholder="Contact Email" id="email" name="email">
                    </div>
                    <div class="form-group flex flex-wrap items-center justify-between">
                        <label for="phone" class="col-12 col-md-4 pl-0"><span>Contact Phone:</span> </label>
                        <input type="text" class="form-control col-12 col-md-8" placeholder="Contact Phone" id="phone" name="phone">
                    </div>
                    <div class="form-group flex flex-wrap">
                        <label for="message" class="col-12 col-md-4 pl-0">
                            <span>Message:</span>
                        </label>
                        <textarea class="form-control col-12 col-md-8" name="message" id="message" cols="30" rows="7"></textarea>
                    </div>
                    <div class="flex flex-wrap items-center mt-2">
                        <div class="col-12 col-md-4 pl-0">
                            <button type="submit" class="revnor-btn">Send</button>
                        </div>
                        <div class="col-12 col-md-8 pl-0 text-left text-md-center mt-2 text-dark text-sm">
                            You may also contact us directly at <span class="text-nowrap">+1 312.720.6145</span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script> 
        $(document).ready(function () {
            setTimeout(() => {
                $('.alert').slideUp();
            }, 2000);
        });
    </script>
</x-app-layout>