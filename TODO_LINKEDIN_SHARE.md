# TODO: LinkedIn Share Feature for Student Competences

## Task: Add LinkedIn sharing option for students to share their competences

### Steps:
- [x] 1. Update `templates/front/competence/index.html.twig` - Add LinkedIn share button
- [x] 2. Update `templates/front/overview/index.html.twig` - Add LinkedIn share button in competence cards

### Implementation Details:
- Use LinkedIn's share URL API: `https://www.linkedin.com/sharing/share-offsite/?url={encoded_url}`
- Include competence name, category, and level in the share content
- Style the button with LinkedIn brand colors
- Open share dialog in a new popup window

### Expected Result:
Students can click a "Share to LinkedIn" button on their competence cards/table to share their skills directly to LinkedIn.

## COMPLETED ✅

