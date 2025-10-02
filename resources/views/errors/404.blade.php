<x-guest-layout>

<div class="flex flex-col items-center justify-center grow h-full">
        <div class="mb-10">
         <img alt="image" class="dark:hidden max-h-[160px]" src="{{ asset('assets/media/illustrations/19.svg') }}">
         <img alt="image" class="light:hidden max-h-[160px]" src="{{ asset('assets/media/illustrations/19-dark.svg') }}">
        </div>
        <span class="kt-badge kt-badge-primary kt-badge-outline mb-3">
         404 Error
        </span>
        <h3 class="text-2xl font-semibold text-mono text-center mb-2">
         We have lost this page
        </h3>
        <div class="text-base text-center text-secondary-foreground mb-10">
         The requested page is missing. Check the URL or
         <a class="text-primary font-medium hover:text-primary" href="/">
          Return Home
         </a>
         .
        </div>
      
</div>

</x-guest-layout>       