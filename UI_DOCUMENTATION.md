# UI Documentation & Design System

This document provides a comprehensive guide to the UI/UX design system used in the Personal Finance application. It serves as a reference for developers and designers to ensure consistency, accessibility, and maintainability.

## 1. Design Principles

*   **Minimalist Professional**: The design focuses on clarity, trust, and professionalism. We use a muted color palette with strong typography.
*   **Content-First**: Data (numbers, charts) is the hero. The UI supports the content, not distracts from it.
*   **Accessibility**: All components are designed to meet WCAG 2.1 AA standards (contrast, focus states, semantic HTML).
*   **Responsive**: The layout adapts seamlessly from mobile to desktop.

## 2. Global Styling

### Typography
*   **Primary Font**: `Inter` (UI elements, headings, body text).
*   **Data Font**: `Roboto Mono` (Financial figures, dates, IDs).
*   **Scale**: We use a responsive type scale defined in CSS variables (`--font-size-xs` to `--font-size-4xl`).

### Color Palette
*   **Primary**: Deep Teal (`#0F766E`) - Trustworthy, professional.
*   **Secondary**: Gray (`#4B5563`) - Neutral, balanced.
*   **Background**: Soft Gray (`#F9FAFB`) & White (`#FFFFFF`).
*   **Semantic Colors**:
    *   **Success**: Emerald (`#059669`) - Income, positive actions.
    *   **Danger**: Red (`#DC2626`) - Expense, destructive actions.
    *   **Warning**: Amber (`#D97706`) - Alerts, attention needed.
    *   **Info**: Blue (`#2563EB`) - Information, links.

### Spacing
We use a consistent spacing scale based on 4px increments (`--space-1` = 4px, `--space-4` = 16px, etc.).

## 3. Component Library

### Buttons
*   `.btn`: Base class.
*   `.btn-primary`: Main call-to-action.
*   `.btn-secondary`: Alternative actions.
*   `.btn-danger`: Destructive actions.
*   `.btn-ghost`: Subtle actions (icon buttons).
*   **States**: Hover, Focus, Disabled, Loading (`.btn-loading`).

### Cards
*   `.card-custom`: Standard card with border and soft shadow.
*   `.card-gradient-header`: Card with a colored gradient header.
*   **Variants**: `.card-income`, `.card-expense`, `.card-balance`.

### Inputs
*   `.input-custom`: Standard text input, select, textarea.
*   `.input-group`: Inputs with prefixes/suffixes.
*   `.input-floating`: Floating label inputs.
*   **States**: Focus, Error (`.input-error`), Success (`.input-success`).

### Alerts & Badges
*   `.alert-custom`: Base alert class.
*   `.badge`: Pill-shaped indicators.
*   **Variants**: `-success`, `-danger`, `-warning`, `-info`.

## 4. Utilities & Helpers

*   **Text**: `.text-xs`, `.text-sm`, `.font-bold`, `.text-muted`.
*   **Spacing**: `.m-4`, `.p-6`, `.mb-8` (standard margin/padding helpers).
*   **Layout**: `.flex`, `.grid` (Tailwind classes are also available).
*   **Data**: `.tabular-nums` (for aligning numbers in tables).

## 5. JavaScript Behaviors

### Form Loading State
Forms automatically show a loading state on submit. The submit button receives the `.btn-loading` class and is disabled to prevent double submission.
*   **Opt-out**: Add class `no-loading` to the `<form>` tag.

### Toast Notifications
Toasts are automatically dismissed after 5 seconds.

## 6. Maintenance Guide

### Adding New Components
1.  Define the component structure in HTML.
2.  Add styles in `public/custom.css` under the "Reusable UI Components" section.
3.  Use CSS variables for colors, spacing, and typography to maintain consistency.

### Updating Styles
*   **Do not** hardcode hex colors. Use `var(--color-...)`.
*   **Do not** use arbitrary pixel values for spacing. Use `var(--space-...)`.

### Accessibility Check
Before finalizing any UI change:
1.  Check color contrast (min 4.5:1 for normal text).
2.  Ensure all interactive elements have visible focus states.
3.  Verify keyboard navigation (Tab order).
4.  Use semantic HTML tags (`<nav>`, `<main>`, `<button>`, etc.).
