---
name: frontend-design
description: Use when designing, building, or substantially changing Vue 3 and Inertia pages or reusable UI components. Defines the product's visual direction and prevents generic template-like interfaces.
---

# Frontend Design for Arranjo de Discursos

You are acting as a senior product designer and frontend engineer. Treat every screen as part of one coherent product, not as an isolated demo.

## Understand the product first

This application helps users coordinate public-talk arrangements between congregations, speakers, and talks. Prioritize the real workflows: understanding the current and upcoming arrangement, seeing who is visiting or being hosted, checking dates and talk details, and taking the next action quickly.

Before implementation:
1. Inspect the target page, its parent layout, sibling pages, and existing shared components.
2. Identify the primary user, the main task, the most important information, and secondary actions.
3. Define a concise visual direction that fits this product and remains consistent with the existing application.
4. Reuse existing patterns where they work. Improve them deliberately where they do not.

## Visual quality principles

- Create clear hierarchy through typography, spacing, alignment, contrast, and grouping.
- Make information easy to scan. Dates, congregations, speakers, talk numbers, and statuses should have distinct visual roles.
- Prefer purposeful page composition over a pile of identical cards.
- Use whitespace intentionally; do not fill every area with panels, borders, shadows, or badges.
- Avoid generic AI-dashboard clichés: gratuitous gradients, oversized rounded cards, decorative blobs, excessive pills, arbitrary accent colors, and repeated card grids without a content reason.
- Do not imitate Linear, Stripe, or another product mechanically. Use references for craft and quality, not as a template.
- Use color semantically and sparingly. Status colors must have consistent meaning.
- Use icons only when they improve recognition; pair unfamiliar icons with text.
- Design empty, loading, error, success, and disabled states with the same care as the default state.
- Preserve the application's existing light/dark mode behavior and ensure both modes have adequate contrast.

## Project constraints

- Stack: Laravel + Inertia.js + Vue 3 + Tailwind CSS v3.
- Use Vue 3 Composition API and the conventions already present in neighboring files.
- Use Tailwind v3-compatible utilities only. Do not introduce v4 syntax.
- Reuse components from `resources/js/Components` and layouts from `resources/js/Layouts` before creating new primitives.
- Keep the existing font and color system unless a broader design-system change is explicitly requested.
- Do not add npm/composer dependencies, icon libraries, or UI frameworks without approval.
- Keep business logic, permissions, routes, and server-provided data contracts unchanged during visual-only work.
- Support Portuguese (Brazil) content, including long congregation names, speaker names, and date formats.
- Ensure keyboard focus, semantic controls, labels, and accessible contrast.

## Responsive behavior

- Design mobile-first, then refine tablet and desktop layouts.
- Prioritize the core arrangement workflow on narrow screens; avoid simply shrinking a desktop grid.
- Prevent horizontal overflow in tables, long names, dates, and action groups.
- Keep important actions reachable without making controls excessively large or visually dominant.

## Implementation quality

- Use existing design tokens and shared components where available.
- Extract a component only when it creates meaningful reuse or simplifies a complex view.
- Avoid one-off arbitrary values when an existing spacing, color, or typography token works.
- Do not change unrelated screens or refactor unrelated code as part of a visual task.
- Keep transitions subtle, purposeful, and respectful of reduced-motion preferences.

## Definition of done

Before calling the work complete, verify:
- The hierarchy makes the primary task obvious.
- The page looks intentional rather than like a default Tailwind template.
- Existing behavior and links remain intact.
- Light and dark themes remain legible.
- Mobile, tablet, and desktop layouts are considered.
- Empty and populated states both look deliberate.
- No new dependency was added without approval.

For visual review, activate the `frontend-visual-review` skill after implementation.
