<div class="kt-card shadow-md">
    <div class="kt-card-content">
        <h2 class="mb-6 pb-3 text-lg font-semibold text-slate-700">
            User Activity Log
        </h2>

        <!-- Filtering Controls -->
        <div class="mb-4 flex flex-col gap-3 sm:flex-row">
            <input type="text" placeholder="Search event, resource, or IP address..."
                class="kt-input flex-grow rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm" />
            <select class="kt-select w-full rounded-md border border-slate-300 px-3 py-2 text-sm sm:w-40">
                <option>All Statuses</option>
                <option>Success</option>
                <option>Denied</option>
                <option>Warning</option>
            </select>
            <button type="button"
                class="kb-btn kt-btn-outline rounded-md px-4 py-2 text-sm font-medium text-slate-700 transition duration-150 hover:bg-slate-50">
                Export CSV <i class="fas fa-download ml-1"></i>
            </button>
        </div>

        <!-- Audit Log Table -->
        <div class="custom-scrollbar max-h-[500px] overflow-hidden overflow-y-auto rounded-md border border-border">
            <table class="kt-table kt-table-border kt-table-dense min-w-full divide-y divide-slate-200">
                <thead class="sticky top-0 bg-slate-50">
                    <tr>
                        <th
                            class="w-1/5 px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                            Timestamp
                        </th>
                        <th
                            class="w-2/5 px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                            Action Detail
                        </th>
                        <th
                            class="w-1/5 px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                            Source IP
                        </th>
                        <th
                            class="w-1/5 px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody id="audit-log-body" class="divide-y divide-slate-100 text-xs">
                    <!-- Log entries populated by JS -->
                </tbody>
            </table>
        </div>
    </div>
</div>
