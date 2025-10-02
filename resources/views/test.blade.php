@extends('layouts.main.base') <!-- or your main layout -->

@section('content')
<div class="flex justify-center items-center min-h-screen">
    <x-modal 
        id="welcome_modal" 
        title="Welcome to Metronic" 
        :autoShow="false"
        :image="'assets/media/illustrations/21.svg'"
    >
        Hello {{ auth()->user()->name }}! we're thrilled to have you on board and excited for the journey ahead together.

        <x-slot name="actions">
            <a href="{{ url('/dashboard') }}" class="kt-btn kt-btn-primary flex justify-center">
                Show me around
            </a>
            <a href="#" class="kt-btn kt-btn-outline flex justify-center ms-2">
                Skip
            </a>
            
        </x-slot>
    </x-modal>

<div class="grid w-full space-y-5">
  <div class="kt-card">
    <div class="kt-card-header min-h-16">
      <input
        type="text"
        placeholder="Search..."
        class="kt-input sm:w-48"
      /><button type="button" class="kt-btn kt-btn-outline">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="24"
          height="24"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
          class="lucide lucide-plus"
          aria-hidden="true"
        >
          <path d="M5 12h14"></path>
          <path d="M12 5v14"></path></svg
        >Add
      </button>
    </div>
    <div
      class="kt-card-table"
      data-kt-datatable="true"
      data-kt-datatable-page-size="5"
      data-kt-datatable-state-save="true"
    >
      <div class="kt-table-wrapper kt-scrollable">
        <table class="kt-table kt-table-border" data-kt-datatable-table="true">
          <thead>
            <tr>
              <th scope="col" class="w-5">
                <input
                  type="checkbox"
                  class="kt-checkbox"
                  data-kt-datatable-check="true"
                />
              </th>
              <th scope="col" class="w-30" data-kt-datatable-column="label">
                <span class="kt-table-col"
                  ><span class="kt-table-col-label">Label</span
                  ><span class="kt-table-col-sort"></span
                ></span>
              </th>
              <th scope="col" class="w-20" data-kt-datatable-column="method">
                <span class="kt-table-col"
                  ><span class="kt-table-col-label">Method</span
                  ><span class="kt-table-col-sort"></span
                ></span>
              </th>
              <th scope="col" class="w-24" data-kt-datatable-column="status">
                <span class="kt-table-col asc"
                  ><span class="kt-table-col-label">Status</span
                  ><span class="kt-table-col-sort"></span
                ></span>
              </th>
              <th
                scope="col"
                class="w-24"
                data-kt-datatable-column="lastSession"
              >
                <span class="kt-table-col"
                  ><span class="kt-table-col-label">Last Session</span
                  ><span class="kt-table-col-sort"></span
                ></span>
              </th>
              <th
                scope="col"
                class="w-16"
                data-kt-datatable-column="actions"
              ></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <input
                  type="checkbox"
                  class="kt-checkbox"
                  data-kt-datatable-row-check="true"
                  value="0"
                />
              </td>
              <td>Guy Hawkins</td>
              <td>Web</td>
              <td><span class="kt-badge kt-badge-success">Approved</span></td>
              <td>22 Jul 2024</td>
              <td class="text-end">
                <span class="inline-flex gap-2.5"
                  ><a
                    href="#"
                    class="kt-btn kt-btn-sm kt-btn-icon kt-btn-outline"
                    ><svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      class="lucide lucide-pencil"
                      aria-hidden="true"
                    >
                      <path
                        d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"
                      ></path>
                      <path d="m15 5 4 4"></path></svg></a
                  ><a
                    href="#"
                    class="kt-btn kt-btn-sm kt-btn-icon kt-btn-outline"
                    ><svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      class="lucide lucide-trash"
                      aria-hidden="true"
                    >
                      <path d="M3 6h18"></path>
                      <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                      <path
                        d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"
                      ></path></svg></a
                ></span>
              </td>
            </tr>
            <tr>
              <td>
                <input
                  type="checkbox"
                  class="kt-checkbox"
                  data-kt-datatable-row-check="true"
                  value="1"
                />
              </td>
              <td>Sales Dept</td>
              <td>SSH</td>
              <td>
                <span class="kt-badge kt-badge-destructive">Rejected</span>
              </td>
              <td>18 Jul, 2024</td>
              <td class="text-end">
                <span class="inline-flex gap-2.5"
                  ><a
                    href="#"
                    class="kt-btn kt-btn-sm kt-btn-icon kt-btn-outline"
                    ><svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      class="lucide lucide-pencil"
                      aria-hidden="true"
                    >
                      <path
                        d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"
                      ></path>
                      <path d="m15 5 4 4"></path></svg></a
                  ><a
                    href="#"
                    class="kt-btn kt-btn-sm kt-btn-icon kt-btn-outline"
                    ><svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      class="lucide lucide-trash"
                      aria-hidden="true"
                    >
                      <path d="M3 6h18"></path>
                      <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                      <path
                        d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"
                      ></path></svg></a
                ></span>
              </td>
            </tr>
            <tr>
              <td>
                <input
                  type="checkbox"
                  class="kt-checkbox"
                  data-kt-datatable-row-check="true"
                  value="2"
                />
              </td>
              <td>Sales Dept</td>
              <td>Kerberos</td>
              <td><span class="kt-badge kt-badge-primary">Verified</span></td>
              <td>15 Jul, 2024</td>
              <td class="text-end">
                <span class="inline-flex gap-2.5"
                  ><a
                    href="#"
                    class="kt-btn kt-btn-sm kt-btn-icon kt-btn-outline"
                    ><svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      class="lucide lucide-pencil"
                      aria-hidden="true"
                    >
                      <path
                        d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"
                      ></path>
                      <path d="m15 5 4 4"></path></svg></a
                  ><a
                    href="#"
                    class="kt-btn kt-btn-sm kt-btn-icon kt-btn-outline"
                    ><svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      class="lucide lucide-trash"
                      aria-hidden="true"
                    >
                      <path d="M3 6h18"></path>
                      <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                      <path
                        d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"
                      ></path></svg></a
                ></span>
              </td>
            </tr>
            <tr>
              <td>
                <input
                  type="checkbox"
                  class="kt-checkbox"
                  data-kt-datatable-row-check="true"
                  value="3"
                />
              </td>
              <td>Legal Dept</td>
              <td>Token</td>
              <td><span class="kt-badge kt-badge-warning">Pending</span></td>
              <td>30 Jul, 2024</td>
              <td class="text-end">
                <span class="inline-flex gap-2.5"
                  ><a
                    href="#"
                    class="kt-btn kt-btn-sm kt-btn-icon kt-btn-outline"
                    ><svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      class="lucide lucide-pencil"
                      aria-hidden="true"
                    >
                      <path
                        d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"
                      ></path>
                      <path d="m15 5 4 4"></path></svg></a
                  ><a
                    href="#"
                    class="kt-btn kt-btn-sm kt-btn-icon kt-btn-outline"
                    ><svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      class="lucide lucide-trash"
                      aria-hidden="true"
                    >
                      <path d="M3 6h18"></path>
                      <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                      <path
                        d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"
                      ></path></svg></a
                ></span>
              </td>
            </tr>
            <tr>
              <td>
                <input
                  type="checkbox"
                  class="kt-checkbox"
                  data-kt-datatable-row-check="true"
                  value="4"
                />
              </td>
              <td>Finance Dept</td>
              <td>API Key</td>
              <td><span class="kt-badge kt-badge-warning">Pending</span></td>
              <td>28 Jul, 2024</td>
              <td class="text-end">
                <span class="inline-flex gap-2.5"
                  ><a
                    href="#"
                    class="kt-btn kt-btn-sm kt-btn-icon kt-btn-outline"
                    ><svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      class="lucide lucide-pencil"
                      aria-hidden="true"
                    >
                      <path
                        d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"
                      ></path>
                      <path d="m15 5 4 4"></path></svg></a
                  ><a
                    href="#"
                    class="kt-btn kt-btn-sm kt-btn-icon kt-btn-outline"
                    ><svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      class="lucide lucide-trash"
                      aria-hidden="true"
                    >
                      <path d="M3 6h18"></path>
                      <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                      <path
                        d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"
                      ></path></svg></a
                ></span>
              </td>
            </tr>
            <tr>
              <td>
                <input
                  type="checkbox"
                  class="kt-checkbox"
                  data-kt-datatable-row-check="true"
                  value="5"
                />
              </td>
              <td>Design Dept</td>
              <td>FIDO U2F</td>
              <td><span class="kt-badge kt-badge-success">Approved</span></td>
              <td>16 Jul, 2024</td>
              <td class="text-end">
                <span class="inline-flex gap-2.5"
                  ><a
                    href="#"
                    class="kt-btn kt-btn-sm kt-btn-icon kt-btn-outline"
                    ><svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      class="lucide lucide-pencil"
                      aria-hidden="true"
                    >
                      <path
                        d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"
                      ></path>
                      <path d="m15 5 4 4"></path></svg></a
                  ><a
                    href="#"
                    class="kt-btn kt-btn-sm kt-btn-icon kt-btn-outline"
                    ><svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      class="lucide lucide-trash"
                      aria-hidden="true"
                    >
                      <path d="M3 6h18"></path>
                      <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                      <path
                        d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"
                      ></path></svg></a
                ></span>
              </td>
            </tr>
            <tr>
              <td>
                <input
                  type="checkbox"
                  class="kt-checkbox"
                  data-kt-datatable-row-check="true"
                  value="6"
                />
              </td>
              <td>Compliance Dept</td>
              <td>OpenID</td>
              <td>
                <span class="kt-badge kt-badge-destructive">Rejected</span>
              </td>
              <td>11 Aug, 2024</td>
              <td class="text-end">
                <span class="inline-flex gap-2.5"
                  ><a
                    href="#"
                    class="kt-btn kt-btn-sm kt-btn-icon kt-btn-outline"
                    ><svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      class="lucide lucide-pencil"
                      aria-hidden="true"
                    >
                      <path
                        d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"
                      ></path>
                      <path d="m15 5 4 4"></path></svg></a
                  ><a
                    href="#"
                    class="kt-btn kt-btn-sm kt-btn-icon kt-btn-outline"
                    ><svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      class="lucide lucide-trash"
                      aria-hidden="true"
                    >
                      <path d="M3 6h18"></path>
                      <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                      <path
                        d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"
                      ></path></svg></a
                ></span>
              </td>
            </tr>
            <tr>
              <td>
                <input
                  type="checkbox"
                  class="kt-checkbox"
                  data-kt-datatable-row-check="true"
                  value="7"
                />
              </td>
              <td>Alice Smith</td>
              <td>Biometric</td>
              <td><span class="kt-badge kt-badge-success">Approved</span></td>
              <td>19 Jul, 2024</td>
              <td class="text-end">
                <span class="inline-flex gap-2.5"
                  ><a
                    href="#"
                    class="kt-btn kt-btn-sm kt-btn-icon kt-btn-outline"
                    ><svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      class="lucide lucide-pencil"
                      aria-hidden="true"
                    >
                      <path
                        d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"
                      ></path>
                      <path d="m15 5 4 4"></path></svg></a
                  ><a
                    href="#"
                    class="kt-btn kt-btn-sm kt-btn-icon kt-btn-outline"
                    ><svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      class="lucide lucide-trash"
                      aria-hidden="true"
                    >
                      <path d="M3 6h18"></path>
                      <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                      <path
                        d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"
                      ></path></svg></a
                ></span>
              </td>
            </tr>
            <tr>
              <td>
                <input
                  type="checkbox"
                  class="kt-checkbox"
                  data-kt-datatable-row-check="true"
                  value="8"
                />
              </td>
              <td>HR Dept</td>
              <td>Basic auth</td>
              <td><span class="kt-badge kt-badge-success">Approved</span></td>
              <td>6 Aug, 2024</td>
              <td class="text-end">
                <span class="inline-flex gap-2.5"
                  ><a
                    href="#"
                    class="kt-btn kt-btn-sm kt-btn-icon kt-btn-outline"
                    ><svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      class="lucide lucide-pencil"
                      aria-hidden="true"
                    >
                      <path
                        d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"
                      ></path>
                      <path d="m15 5 4 4"></path></svg></a
                  ><a
                    href="#"
                    class="kt-btn kt-btn-sm kt-btn-icon kt-btn-outline"
                    ><svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      class="lucide lucide-trash"
                      aria-hidden="true"
                    >
                      <path d="M3 6h18"></path>
                      <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                      <path
                        d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"
                      ></path></svg></a
                ></span>
              </td>
            </tr>
            <tr>
              <td>
                <input
                  type="checkbox"
                  class="kt-checkbox"
                  data-kt-datatable-row-check="true"
                  value="9"
                />
              </td>
              <td>Guy Hawkins</td>
              <td>Web</td>
              <td><span class="kt-badge kt-badge-success">Approved</span></td>
              <td>22 Jul 2024</td>
              <td class="text-end">
                <span class="inline-flex gap-2.5"
                  ><a
                    href="#"
                    class="kt-btn kt-btn-sm kt-btn-icon kt-btn-outline"
                    ><svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      class="lucide lucide-pencil"
                      aria-hidden="true"
                    >
                      <path
                        d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"
                      ></path>
                      <path d="m15 5 4 4"></path></svg></a
                  ><a
                    href="#"
                    class="kt-btn kt-btn-sm kt-btn-icon kt-btn-outline"
                    ><svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      class="lucide lucide-trash"
                      aria-hidden="true"
                    >
                      <path d="M3 6h18"></path>
                      <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                      <path
                        d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"
                      ></path></svg></a
                ></span>
              </td>
            </tr>
            <tr>
              <td>
                <input
                  type="checkbox"
                  class="kt-checkbox"
                  data-kt-datatable-row-check="true"
                  value="10"
                />
              </td>
              <td>Sales Dept</td>
              <td>SSH</td>
              <td>
                <span class="kt-badge kt-badge-destructive">Rejected</span>
              </td>
              <td>18 Jul, 2024</td>
              <td class="text-end">
                <span class="inline-flex gap-2.5"
                  ><a
                    href="#"
                    class="kt-btn kt-btn-sm kt-btn-icon kt-btn-outline"
                    ><svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      class="lucide lucide-pencil"
                      aria-hidden="true"
                    >
                      <path
                        d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"
                      ></path>
                      <path d="m15 5 4 4"></path></svg></a
                  ><a
                    href="#"
                    class="kt-btn kt-btn-sm kt-btn-icon kt-btn-outline"
                    ><svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      class="lucide lucide-trash"
                      aria-hidden="true"
                    >
                      <path d="M3 6h18"></path>
                      <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                      <path
                        d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"
                      ></path></svg></a
                ></span>
              </td>
            </tr>
            <tr>
              <td>
                <input
                  type="checkbox"
                  class="kt-checkbox"
                  data-kt-datatable-row-check="true"
                  value="11"
                />
              </td>
              <td>Sales Dept</td>
              <td>Kerberos</td>
              <td><span class="kt-badge kt-badge-success">Approved</span></td>
              <td>15 Jul, 2024</td>
              <td class="text-end">
                <span class="inline-flex gap-2.5"
                  ><a
                    href="#"
                    class="kt-btn kt-btn-sm kt-btn-icon kt-btn-outline"
                    ><svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      class="lucide lucide-pencil"
                      aria-hidden="true"
                    >
                      <path
                        d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"
                      ></path>
                      <path d="m15 5 4 4"></path></svg></a
                  ><a
                    href="#"
                    class="kt-btn kt-btn-sm kt-btn-icon kt-btn-outline"
                    ><svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      class="lucide lucide-trash"
                      aria-hidden="true"
                    >
                      <path d="M3 6h18"></path>
                      <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                      <path
                        d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"
                      ></path></svg></a
                ></span>
              </td>
            </tr>
            <tr>
              <td>
                <input
                  type="checkbox"
                  class="kt-checkbox"
                  data-kt-datatable-row-check="true"
                  value="12"
                />
              </td>
              <td>Legal Dept</td>
              <td>Token</td>
              <td><span class="kt-badge kt-badge-warning">Pending</span></td>
              <td>30 Jul, 2024</td>
              <td class="text-end">
                <span class="inline-flex gap-2.5"
                  ><a
                    href="#"
                    class="kt-btn kt-btn-sm kt-btn-icon kt-btn-outline"
                    ><svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      class="lucide lucide-pencil"
                      aria-hidden="true"
                    >
                      <path
                        d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"
                      ></path>
                      <path d="m15 5 4 4"></path></svg></a
                  ><a
                    href="#"
                    class="kt-btn kt-btn-sm kt-btn-icon kt-btn-outline"
                    ><svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      class="lucide lucide-trash"
                      aria-hidden="true"
                    >
                      <path d="M3 6h18"></path>
                      <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                      <path
                        d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"
                      ></path></svg></a
                ></span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <!--begin:pagination-->
      <div class="kt-datatable-toolbar">
        <div class="kt-datatable-length">
          Show<select
            class="kt-select kt-select-sm w-16"
            name="perpage"
            data-kt-datatable-size="true"
          ></select
          >per page
        </div>
        <div class="kt-datatable-info">
          <span data-kt-datatable-info="true"></span>
          <div
            class="kt-datatable-pagination"
            data-kt-datatable-pagination="true"
          ></div>
        </div>
      </div>
      <!--end:pagination-->
    </div>
  </div>
</div>


</div>
@endsection
