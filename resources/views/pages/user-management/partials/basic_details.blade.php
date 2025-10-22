 <div class="mx-auto max-w-4xl rounded-lg bg-white p-6 shadow-md">
     <div class="kt-card">
         <div class="kt-card-content">
             <div class="mb-6 flex items-center justify-between">
                 <h2 class="text-2xl font-semibold text-gray-800">Profile Information</h2>

             </div>

             <!-- User Profile Header -->
             <div class="mb-8 flex items-center">
                 {{-- Image Input Component --}}
                 <x-image-uploader name="avatar" id="imgupld" align="left" size="md" :preview="Auth::user()->avatar != 'blank.png'
                     ? asset('storage/' . Auth::user()->avatar)
                     : asset('assets/media/avatars/blank.png')">
                     <span class="mt-1 text-[8px] font-normal text-gray-400">PNG, JPG</span>
                 </x-image-uploader>
                 <div class="ml-4">
                     <p class="text-lg font-medium text-gray-900">Leslie Alexander</p>
                     <p class="text-sm text-gray-500">Customer Service Manager</p>
                 </div>
             </div>

             <!-- Personal Details Section -->
             <h3 class="mb-4 text-xl font-semibold text-gray-800">Personal Details</h3>
             <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-2">
                 <div>
                     <label for="first-name" class="block text-sm font-medium text-gray-700">First Name</label>
                     <input type="text" name="first-name" id="first-name" autocomplete="given-name" value="Leslie"
                         class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm" />
                 </div>
                 <div>
                     <label for="last-name" class="block text-sm font-medium text-gray-700">Last Name</label>
                     <input type="text" name="last-name" id="last-name" autocomplete="family-name" value="Alexander"
                         class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm" />
                 </div>
                 <div>
                     <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                     <input type="email" name="email" id="email" autocomplete="email" value="leslie@gmail.com"
                         class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm" />
                 </div>
                 <div>
                     <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                     <input type="tel" name="phone" id="phone" autocomplete="tel" value="+317-439-5139"
                         class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm" />
                 </div>
                 <div>
                     <label for="bio" class="block text-sm font-medium text-gray-700">Bio / Title</label>
                     <input type="text" name="bio" id="bio" value="Customer Service Manager"
                         class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm" />
                 </div>
                 <div>
                     <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                     <select id="gender" name="gender"
                         class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm">
                         <option>Female</option>
                         <option>Male</option>
                         <option>Other</option>
                     </select>
                 </div>
                 <div>
                     <label for="date-of-birth" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                     <input type="text" name="date-of-birth" id="date-of-birth" value="10 June, 1994"
                         class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm" />
                 </div>
                 <div>
                     <label for="national-id" class="block text-sm font-medium text-gray-700">National ID</label>
                     <input type="text" name="national-id" id="national-id" value="629 555-0129 333-0127"
                         class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm" />
                 </div>
             </div>

             <!-- Address Section -->
             <h3 class="mb-4 text-xl font-semibold text-gray-800">Address</h3>
             <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-2">
                 <div>
                     <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                     <select id="country" name="country" autocomplete="country-name"
                         class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm">
                         <option>United States</option>
                         <option>Canada</option>
                         <option>Mexico</option>
                     </select>
                 </div>
                 <div>
                     <label for="city-state" class="block text-sm font-medium text-gray-700">City/State</label>
                     <input type="text" name="city-state" id="city-state" autocomplete="address-level1"
                         value="Los Angeles"
                         class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm" />
                 </div>
                 <div>
                     <label for="postal-code" class="block text-sm font-medium text-gray-700">Postal Code</label>
                     <input type="text" name="postal-code" id="postal-code" autocomplete="postal-code" value="90001"
                         class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm" />
                 </div>
                 <div>
                     <label for="tax-id" class="block text-sm font-medium text-gray-700">TAX ID</label>
                     <input type="text" name="tax-id" id="tax-id" value="BH28F55219"
                         class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm" />
                 </div>
             </div>

         </div>
     </div>
 </div>
