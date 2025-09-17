# Metronic Tailwind HTML Laravel Integration
Note: You need to purchase the theme for you to have the required assets

This project integrates Metronic Tailwind HTML themes into a Laravel application.

## Project Overview

**Goal**: Convert Metronic Tailwind HTML demo layouts (Demo1 through Demo10) into standard Laravel Blade views, providing a comprehensive showcase of Metronic's design system within Laravel's MVC architecture.

## Tech Stack

- **Laravel**: 12.x (Latest)
- **PHP**: 8.2+
- **Tailwind CSS**: 3.x
- **Vite**: 5.x for asset building
- **Node.js**: Latest LTS version

## Project Structure

```
app/Http/Controllers/
├── Demo1Controller.php
├── Demo2Controller.php
├── ...
└── Demo10Controller.php

resources/views/
├── layouts/
│   ├── partials/
│   │   ├── head.blade.php
│   │   └── scripts.blade.php
│   ├── demo1/
│   │   ├── base.blade.php
│   │   └── partials/
│   ├── demo2/
│   │   ├── base.blade.php
│   │   └── partials/
│   └── ... (demo3-demo10)
├── pages/
│   ├── demo1/
│   │   └── index.blade.php
│   ├── demo2/
│   │   └── index.blade.php
│   └── ... (demo3-demo10)
└── components/
    ├── demo1/
    ├── demo2/
    ├── ... (demo3-demo10)
    └── shared/

public/assets/
├── css/
│   └── styles.css
├── js/
│   ├── core.bundle.js
│   └── layouts/
│       ├── demo1.js
│       ├── demo2.js
│       └── ... (demo3-demo10.js)
├── media/
└── vendors/
```

## Features

### ✅ Core Implementation

1. **Laravel MVC Architecture**
   - Dedicated controllers for each demo (Demo1Controller - Demo10Controller)
   - Clean routing structure with named routes
   - Blade template inheritance and components

2. **Asset Management**
   - Metronic CSS and JavaScript assets properly integrated
   - Laravel asset helpers for proper path resolution
   - Vite integration for development workflow

3. **Template System**
   - Blade layouts for each demo with proper inheritance
   - Reusable partials for headers, sidebars, and footers
   - Component-based architecture for UI elements

4. **Responsive Design**
   - Mobile-first responsive layouts
   - Touch-friendly navigation
   - Adaptive components across all screen sizes
  
5. **Navigation menu helper and config generator**

### 🎨 Design System

- **Metronic Tailwind CSS** - Complete design system integration
- **Theme Support** - Light and dark mode switching
- **Custom Components** - Metronic-specific UI components
- **Icon System** - Comprehensive icon library integration

## Getting Started

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js (LTS version)
- A web server (Apache/Nginx) or use Laravel's built-in server

