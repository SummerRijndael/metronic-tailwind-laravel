   <div class="kt-card shadow-md">
       <div class="kt-card-content">
           <h2 class="mb-6 pb-3 text-lg font-semibold text-slate-700">
               Authentication & Session Management
           </h2>

           <!-- Policy & Credential Health -->
           <div class="mb-8 grid grid-cols-1 gap-4 md:grid-cols-2">
               <div class="flex items-center justify-between rounded-md border border-border bg-slate-50 p-4">
                   <div>
                       <p class="font-medium text-slate-700">Password Health</p>
                       <p class="text-sm text-slate-500" id="last-password-change">
                           Changed 12 days ago
                       </p>
                   </div>
                   <button type="button"
                       class="rounded-md border border-border px-4 py-2 text-sm font-medium text-red-600 transition duration-150 hover:bg-red-50">
                       Force Reset
                   </button>
               </div>
               <div class="flex items-center justify-between rounded-md border border-border p-4">
                   <div>
                       <p class="font-medium text-slate-700">Multi-Factor Authentication (MFA)</p>
                       <p class="text-sm font-medium text-green-600" id="mfa-status">
                           Enabled (TOTP App)
                       </p>
                   </div>
                   <button type="button"
                       class="rounded-md border border-border px-4 py-2 text-sm font-medium text-indigo-600 transition duration-150 hover:bg-indigo-50">
                       View Setup
                   </button>
               </div>
           </div>

           <!-- Active Sessions List -->
           <h3 class="mb-4 flex items-center justify-between pb-3 text-base font-semibold text-slate-700">
               Active Sessions
               <button type="button" id="revoke-all-sessions-btn" data-user-id="{{ $profileUser->public_id }}"
                   data-revoke-all-url="{{ route('admin.user_management.sessions.revoke_all', ['user' => $profileUser->public_id]) }}"
                   class="kt-btn kt-btn-outline kt-btn-sm kt-btn-secondary">
                   Revoke All Sessions
               </button>
           </h3>
           <div id="active-sessions-list" class="space-y-3">
               <!-- Sessions populated by JS -->
           </div>
       </div>
   </div>
