 <div class="kt-card">
     <div class="kt-card-header" id="auth_password">
         <h3 class="kt-card-title">
             Password
         </h3>
     </div>
     <form data-ajax-form="true" method="POST" action="{{ route('password.update') }}">
         @csrf
         @method('PUT')
         <div class="kt-card-content grid gap-5">
             <div class="w-full">
                 <div class="flex flex-wrap items-baseline gap-2.5 lg:flex-nowrap">
                     <label class="kt-form-label max-w-56">
                         Current Password
                     </label>
                     <div class="flex flex-1 flex-col">
                         <input class="kt-input" type="password" name="current_password"
                             placeholder="Your Current Password" type="text" value=""
                             @error('current_password', 'updatePassword') aria-invalid="true" @enderror required>

                         <div id="error-current_password" class="text-destructive mt-1 text-sm">
                             {{-- The JS will inject the error here --}}
                         </div>

                         @error('current_password', 'updatePassword')
                             <span class="text-destructive mt-1 text-sm">{{ $message }}</span>
                         @enderror

                     </div>
                 </div>
             </div>
             <div class="w-full">
                 <div class="flex flex-wrap items-baseline gap-2.5 lg:flex-nowrap">
                     <label class="kt-form-label max-w-56">
                         New Password
                     </label>
                     <div class="flex flex-1 flex-col">
                         <input class="kt-input" type="password" name="password" placeholder="New Password"
                             type="text" value="" required
                             @error('password', 'updatePassword') aria-invalid="true" @enderror required>

                         <div id="error-password" class="text-destructive mt-1 text-sm">
                             {{-- The JS will inject the error here --}}
                         </div>

                         @error('password', 'updatePassword')
                             <span class="text-destructive mt-1 text-sm">{{ $message }}</span>
                         @enderror
                     </div>

                 </div>
             </div>
             <div class="w-full">
                 <div class="flex flex-wrap items-baseline gap-2.5 lg:flex-nowrap">
                     <label class="kt-form-label max-w-56">
                         Confirm New Password
                     </label>
                     <div class="flex flex-1 flex-col">
                         <input class="kt-input" type="password" name="password_confirmation"
                             placeholder="Confirm New Password" type="text" value="" required
                             @error('password_confirmation', 'updatePassword') aria-invalid="true" @enderror required>

                         <div id="error-password_confirmation" class="text-destructive mt-1 text-sm">
                             {{-- The JS will inject the error here --}}
                         </div>

                         @error('password_confirmation', 'updatePassword')
                             <span class="text-destructive mt-1 text-sm">{{ $message }}</span>
                         @enderror
                     </div>

                 </div>
             </div>
             <div class="flex justify-end pt-2.5">
                 <button data-loading-button="true" type="submit" class="kt-btn kt-btn-primary">
                     Reset Password
                 </button>
             </div>

         </div>
     </form>
 </div>
