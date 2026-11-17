---
name: frontend-visual-review
description: Use after implementing or modifying a Vue/Inertia UI, and when reviewing screenshots or an existing page for visual quality, responsive behavior, and accessibility. Uses the Playwright CLI to inspect the real rendered app.
---

# Frontend Visual Review

Review the rendered interface as a product designer, not merely as a code formatter. A source-code review alone is not a visual review.

## Required browser tool

Use the official Playwright CLI for browser interaction and screenshots:

- CLI: `playwright-cli`
- Official documentation: https://playwright.dev/agent-cli/introduction
- The CLI is a machine-level developer tool, not an application runtime dependency.

If `playwright-cli` is not installed, tell the developer to run these commands on their host machine:

```bash
npm install -g @playwright/cli@latest
playwright-cli install
playwright-cli install --skills
```

Do not add Playwright to the application's `package.json` just to perform an agent visual review. Do not install browser binaries inside the Sail container unless the task specifically requires containerized browser testing.

## Review workflow

1. Identify the changed route and the primary user workflow. Inspect its Inertia page, parent layout, shared components, and relevant route definitions.
2. Start the app using the repository's existing Laravel Sail workflow. Keep the app running while reviewing.
3. Open the actual local page with `playwright-cli open <url>`. If authentication is required, sign in through the browser. Never invent credentials or bypass authorization.
4. Inspect the page snapshot and browser console. Use the CLI to identify the actual rendered page, not just source markup.
5. Capture and inspect screenshots at each viewport:
   - Mobile: 390 × 844
   - Tablet: 768 × 1024
   - Desktop: 1440 × 900
6. When supported by the app, inspect both light and dark themes. Use the real theme control; do not assume a theme exists.
7. Inspect full-page screenshots for long pages and viewport screenshots for above-the-fold composition.
8. Review screenshots visually. Fix the most important issues, then repeat the affected captures.
9. Close the browser session when finished.

Example command sequence (replace the URL with the actual route):

```bash
mkdir -p .playwright-cli/review
playwright-cli open http://localhost
playwright-cli snapshot
playwright-cli console error
playwright-cli resize 390 844
playwright-cli screenshot --filename=.playwright-cli/review/mobile.png
playwright-cli resize 768 1024
playwright-cli screenshot --filename=.playwright-cli/review/tablet.png
playwright-cli resize 1440 900
playwright-cli screenshot --filename=.playwright-cli/review/desktop.png
playwright-cli screenshot --full-page --filename=.playwright-cli/review/desktop-full.png
playwright-cli close
```

Use `playwright-cli --help` or `playwright-cli --help <command>` if a command's options differ in the installed version. For an authenticated route, save browser state only when useful for repeat reviews, keep it under the ignored `.playwright-cli/` directory, and never commit credentials, cookies, or storage state.

## Visual checklist

### Hierarchy and composition
- Is the page title clear and appropriately scaled?
- Is the primary action easy to find without overpowering the content?
- Are related details grouped logically?
- Is there a clear reading order?
- Does the page use whitespace instead of unnecessary containers?
- Does the screen feel designed for this product rather than generated from a generic admin template?

### Typography and content
- Are headings, labels, metadata, and values visually distinct?
- Are long Portuguese names and congregation names handled without clipping?
- Are dates and talk details easy to scan?
- Is text contrast sufficient in both themes?

### Components and consistency
- Are buttons, inputs, badges, tables, and navigation consistent with existing components?
- Are borders, radii, shadows, and colors used consistently?
- Are icons aligned and meaningful?
- Are hover, focus, disabled, loading, empty, and error states considered?

### Responsive behavior
- Check for horizontal overflow and clipped content at all three viewport sizes.
- Check that navigation and actions remain usable on touch screens.
- Check that columns collapse in a meaningful order.
- Check that tables or dense data have a deliberate mobile treatment.

### Accessibility and runtime
- Use semantic buttons and links for actions and navigation.
- Ensure keyboard focus is visible.
- Ensure controls have accessible names and form fields have labels.
- Do not rely on color alone to communicate status.
- Respect reduced-motion preferences.
- Inspect console errors and obvious failed network requests. Distinguish existing unrelated errors from regressions introduced by the current change.

## Report format

Summarize only actionable findings:
- **Must fix:** broken layout, inaccessible controls, unreadable text, clipped or hidden key information, runtime errors introduced by the change.
- **Should fix:** weak hierarchy, inconsistent spacing, unnecessary visual noise, awkward responsive behavior.
- **Polish:** subtle alignment, hover, transition, and icon refinements.

Include the viewport and screenshot path for each visually confirmed finding. Clearly distinguish:
- **Confirmed visually:** observed in an actual Playwright screenshot/browser session.
- **Confirmed in browser diagnostics:** observed in console/network/runtime output.
- **Source-only concern:** inferred from code but not verified in the browser.

Never claim a rendered review was completed if the browser could not be launched, the page could not be reached, or screenshots were not inspected. In that case, state exactly what blocked verification and provide the next command or setup step.
