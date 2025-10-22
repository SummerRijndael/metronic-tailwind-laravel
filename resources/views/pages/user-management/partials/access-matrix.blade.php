  <div class="kt-card shadow-md">
      <div class="kt-card-content">
          <h2 class="mb-6 pb-3 text-lg font-semibold text-slate-700">
              Permission Matrix & Role Assignment
          </h2>

          <!-- Role Quick Change -->
          <div class="mb-6 flex items-center justify-between rounded-md border border-border p-3">
              <label for="role-select" class="flex items-center text-sm font-medium text-indigo-700">
                  <i class="fas fa-tag mr-2"></i> Primary Role Assignment:
              </label>
              <select class="kt-select kt-select-md" id="role-select" data-kt-select="true" data-kt-select-multiple="true"
                  data-kt-select-placeholder="Select a framework"
                  data-kt-select-config='{
          "optionsClass": "kt-scrollable overflow-auto max-h-[250px]"
        }'>
                  <option value="admin">Super Admin</option>
                  <option value="manager">Manager</option>
                  <option value="analyst">Analyst (Current)</option>
                  <option value="guest">Guest</option>
              </select>
          </div>

          <!-- Permission Table -->
          <div class="custom-scrollbar max-h-[500px] overflow-hidden overflow-y-auto rounded-md border border-border">
              <table class="kt-table kt-table-border min-w-full divide-y">
                  <thead class="sticky top-0">
                      <tr>
                          <th
                              class="w-1/4 px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                              Permission Key
                          </th>
                          <th
                              class="w-1/4 px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                              Source
                          </th>
                          <th
                              class="w-1/6 px-4 py-3 text-left text-center text-xs font-semibold uppercase tracking-wider text-slate-600">
                              Effective Status
                          </th>
                          <th
                              class="w-1/4 px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                              Notes / Override
                          </th>
                          <th
                              class="w-1/12 px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-600">
                              Action
                          </th>
                      </tr>
                  </thead>
                  <tbody id="permission-matrix-body" class="divide-y divide-slate-100 text-sm">
                      <!-- Populated by JS -->
                  </tbody>
              </table>
          </div>

          <!-- Direct Assignment Controls -->
          <div class="mt-4 flex items-center justify-end gap-3">
              <select id="direct-perm-select" class="kt-select" data-kt-select="true"
                  data-kt-select-placeholder="Select a framework"
                  data-kt-select-config='{
          "optionsClass": "kt-scrollable overflow-auto max-h-[250px]"
        }'>
                  <option value="">Select permission to manage...</option>
                  <option value="create_users">create_users</option>
                  <option value="delete_audit_logs">delete_audit_logs</option>
                  <option value="override_budget">override_budget</option>
              </select>
              <button type="button" class="kt-btn kt-btn-success rounded-md px-4 py-1.5">
                  <i class="fas fa-plus mr-1"></i> Direct Grant
              </button>
              <button type="button" class="kt-btn kt-btn-destructive rounded-md px-4 py-1.5">
                  <i class="fas fa-ban mr-1"></i> Explicit Deny
              </button>
          </div>
      </div>
  </div>
