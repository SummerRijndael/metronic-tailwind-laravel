<?php
/**
 * Config Builder Blade (Extended)
 *
 * Now includes:
 * - type selector (route, url, label)
 * - icon selector
 * - route name
 * - external toggle
 * - collapsable toggle
 * - skip_url toggle
 * - permission (optional)
 * - badge (optional)
 */
?>

@extends('layouts.main.base')
@section('content')
<div class="p-5">
    <h3 class="text-base font-semibold mb-3">Menu Config Generator</h3>

    <div id="menu-generator" class="space-y-4">
        <!-- Menu Item Builder -->
        <div class="border p-4 rounded-md space-y-3">
            <div>
                <label class="block text-sm font-medium">Title</label>
                <input type="text" class="w-full p-2 border rounded-md mt-1" id="menu-title">
            </div>

            <div>
                <label class="block text-sm font-medium">Type</label>
                <select id="menu-type" class="w-full p-2 border rounded-md mt-1">
                    <option value="route">Route</option>
                    <option value="url">URL</option>
                    <option value="label">Label</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium">Route Name</label>
                <input type="text" class="w-full p-2 border rounded-md mt-1" id="menu-route">
            </div>

            <div>
                <label class="block text-sm font-medium">URL</label>
                <input type="text" class="w-full p-2 border rounded-md mt-1" id="menu-url">
            </div>

            <div>
                <label class="block text-sm font-medium">Icon (class)</label>
                <input type="text" class="w-full p-2 border rounded-md mt-1" id="menu-icon" placeholder="e.g. ki-filled ki-home-3">
            </div>

            <div class="flex items-center space-x-4">
                <label class="flex items-center space-x-1 text-sm"><input type="checkbox" id="menu-external"> <span>External?</span></label>
                <label class="flex items-center space-x-1 text-sm"><input type="checkbox" id="menu-collapsable"> <span>Collapsable?</span></label>
                <label class="flex items-center space-x-1 text-sm"><input type="checkbox" id="menu-skip-url"> <span>Skip URL?</span></label>
            </div>

            <div>
                <label class="block text-sm font-medium">Permission (optional)</label>
                <input type="text" class="w-full p-2 border rounded-md mt-1" id="menu-permission">
            </div>

            <div>
                <label class="block text-sm font-medium">Badge (optional)</label>
                <input type="text" class="w-full p-2 border rounded-md mt-1" id="menu-badge">
            </div>

            <div class="flex items-center mt-3">
                <input type="checkbox" id="menu-has-children" class="mr-2">
                <label for="menu-has-children" class="text-sm">Has Children?</label>
            </div>

            <div id="child-container" class="mt-3 hidden">
                <h4 class="font-medium text-sm">Child Items</h4>
                <button type="button" id="add-child" class="mt-2 px-3 py-1 rounded-md bg-blue-600 text-white hover:bg-primary/90">+ Add Child</button>
                <div id="children-fields" class="space-y-2 mt-2"></div>
            </div>
        </div>

        <!-- Generate -->
        <button id="generate-config" class="px-4 py-2 rounded-md bg-green-600 text-white hover:bg-primary/90">
            Generate Config
        </button>

        <!-- Output -->
        <pre id="config-output" class="bg-muted p-4 rounded-md text-xs overflow-x-auto"></pre>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    const hasChildren = document.getElementById("menu-has-children");
    const urlField = document.getElementById("menu-url");
    const childContainer = document.getElementById("child-container");
    const addChildBtn = document.getElementById("add-child");
    const childrenFields = document.getElementById("children-fields");
    const generateBtn = document.getElementById("generate-config");
    const outputBox = document.getElementById("config-output");

    hasChildren.addEventListener("change", () => {
        childContainer.classList.toggle("hidden", !hasChildren.checked);
        urlField.disabled = hasChildren.checked;
    });

    addChildBtn.addEventListener("click", () => {
        const wrapper = document.createElement("div");
        wrapper.classList.add("flex", "space-x-2");
        wrapper.innerHTML = `
            <input type="text" class="flex-1 p-2 border rounded-md" placeholder="Child Title">
            <input type="text" class="flex-1 p-2 border rounded-md" placeholder="Child URL">
        `;
        childrenFields.appendChild(wrapper);
    });

    generateBtn.addEventListener("click", () => {
        const config = {
            type: document.getElementById("menu-type").value,
            title: document.getElementById("menu-title").value,
            icon: document.getElementById("menu-icon").value || null,
            route: document.getElementById("menu-route").value || null,
            url: hasChildren.checked ? null : (document.getElementById("menu-url").value || null),
            external: document.getElementById("menu-external").checked,
            collapsable: document.getElementById("menu-collapsable").checked,
            skip_url: document.getElementById("menu-skip-url").checked,
            permission: document.getElementById("menu-permission").value || null,
            badge: document.getElementById("menu-badge").value || null,
            children: []
        };

        if (hasChildren.checked) {
            childrenFields.querySelectorAll("div").forEach(childDiv => {
                const inputs = childDiv.querySelectorAll("input");
                config.children.push({
                    title: inputs[0].value,
                    url: inputs[1].value,
                });
            });
        }

        outputBox.textContent = JSON.stringify(config, null, 2);
    });
});
</script>
@endpush