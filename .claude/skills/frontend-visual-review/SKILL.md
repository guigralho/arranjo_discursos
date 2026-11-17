---
name: frontend-visual-review
description: Use after implementing or modifying a Vue/Inertia UI, and when reviewing screenshots or an existing page for visual quality, responsive behavior, and accessibility.
---

# Frontend Visual Review

Review the rendered interface as a product designer, not merely as a code formatter.

## Review sequence

1. Inspect the changed page and identify its primary user task.
2. Run the project's existing checks/build using its documented Sail workflow when execution access is available.
3. If a browser or Playwright integration is already available, open the actual page and inspect it at:
   - Mobile: 390 × 844
   - Tablet: 768 × 1024
   - Desktop: 1440 × 900
4. Inspect both light and dark themes if the application supports them.
5. Capture screenshots when the available browser tooling supports it. Do not claim visual verification if you only inspected source code.
6. Fix the highest-impact issues and inspect again.

Do not install Playwright or another browser dependency just for this review without the user's approval. If browser automation is unavailable, state that limitation and provide the exact local steps needed for the user to verify the page.

## Visual checklist

### Hierarchy and composition
- Is the page title clear and appropriately scaled?
- Is the primary action easy to find without overpowering the content?
- Are related details grouped logically?
- Is there a clear reading order?
- Does the page use whitespace instead of unnecessary containers?

### Typography and content
- Are headings, labels, metadata, and values visually distinct?
- Are long names and Portuguese text handled without clipping?
- Are dates and talk details easy to scan?
- Is text contrast sufficient in both themes?

### Components and consistency
- Are buttons, inputs, badges, tables, and navigation consistent with existing components?
- Are borders, radii, shadows, and colors used consistently?
- Are icons aligned and meaningful?
- Are hover, focus, disabled, loading, empty, and error states considered?

### Responsive behavior
- Check for horizontal overflow and clipped content.
- Check that navigation and actions remain usable on touch screens.
- Check that columns collapse in a meaningful order.
- Check that tables or dense data have a deliberate mobile treatment.

### Accessibility
- Use semantic buttons and links for actions and navigation.
- Ensure keyboard focus is visible.
- Ensure controls have accessible names and form fields have labels.
- Do not rely on color alone to communicate status.
- Respect reduced-motion preferences.

## Report format

Summarize only actionable findings:
- **Must fix:** broken layout, inaccessible controls, unreadable text, clipped or hidden key information.
- **Should fix:** weak hierarchy, inconsistent spacing, unnecessary visual noise, awkward responsive behavior.
- **Polish:** subtle alignment, hover, transition, and icon refinements.

Do not invent browser findings. Clearly separate issues confirmed in the rendered page from concerns inferred from source code.
