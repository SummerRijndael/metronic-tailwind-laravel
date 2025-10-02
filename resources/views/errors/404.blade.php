<x-guest-layout>

@push('styles')


<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Arvo'>

<style>
.page_404{ padding:40px 0; background:#fff; font-family: 'Arvo', serif;
}

.page_404  img{ width:100%;}


/* Style for the background image container */
.four_zero_four_bg {
    /* * IMPORTANT: Replace 'path/to/your/404.gif' with the actual path to your 400px image.
     */
    background-image: url("{{ asset('assets/media/images/animan.gif') }}"); 
    
    /* --- The Fixes for 400px Image --- */
    /* Prevents the image from being cut off by scaling it down to fit. */
    background-size: cover; 
     height: 400px;
     width: 600px;
    /* Ensures the image does not tile/repeat. */
    background-repeat: no-repeat; 
    
    /* Centers the image within the 400px height box. */
    background-position: center; 
}

 
 .four_zero_four_bg h1{
 font-size:80px;
 }
 
  .four_zero_four_bg h3{
			 font-size:80px;
			 }
			 
			 .link_404{			 
	color: #fff!important;
    padding: 10px 20px;
    background: #39ac31;
    margin: 20px 0;
    display: inline-block;}
	.contant_box_404{ margin-top:-50px;}

</style>
@endpush

<div class="flex flex-col items-center justify-center grow h-full">

 <section class="page_404 py-16 min-h-screen flex items-center justify-center">
    <div class="mx-auto px-4 sm:px-6 lg:px-8 w-full"> 
        
        <div class="flex flex-col items-center justify-center">

            <div class="w-full text-center">
                
                <div class="four_zero_four_bg h-[400px] mb-8"> 
                    <h1 class="text-9xl md:text-[12rem] lg:text-[15rem] font-extrabold">404</h1>
                </div>
                
                <div class="contant_box_404 mt-6">
                    <h3 class="text-3xl sm:text-4xl font-bold mb-4">
                        Looks Like You've Strayed Off Course
                    </h3>
                    
                    <p class="text-xl mb-8">We searched high and low, but this page seems to be unavailable.</p>
                     
                    <div class="flex items-center justify-center gap-4">
                                <!-- Go Back Button -->
                                <a href="{{ url()->previous() ?: route('home') }}" 
                                class="px-4 py-2 kt-btn rounded-full">
                                        Go Back
                                </a>

                                <!-- Go Home Button -->
                                <a href="{{ route('dashboard') }}" 
                                class="px-4 py-2 kt-btn kt-btn-secondary rounded-full">
                                        Go Home
                                </a>
                     </div>
                </div>
            </div>
        </div>
    </div>
</section>

      
</div>

</x-guest-layout>       