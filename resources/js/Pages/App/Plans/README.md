# Plans Module

The Plans module provides a comprehensive task and project management system with a beautiful dark UI theme. It allows users to create, organize, edit, and track various items with customizable statuses, priorities, and categories.

## Features

- **Dark Theme UI**: A modern, eye-friendly dark interface with glass-like effects and smooth transitions
- **Responsive Layout**: Adapts to various screen sizes with both grid and list views
- **Item Management**: Full CRUD (Create, Read, Update, Delete) functionality for items
- **Filters and Sorting**: Filter by category, status, priority, and search terms with customizable sorting
- **File Attachments**: Upload and manage files associated with items
- **Status Tracking**: Assign and update item statuses with visual indicators
- **Priority Management**: Set and update item priorities
- **Due Dates**: Set due dates with recurring options (daily, weekly, monthly, yearly)
- **Keyboard Navigation**: Enhanced keyboard accessibility for navigating through items
- **Archive System**: Archive and restore items as needed
- **Export**: Export selected items to CSV format
- **Toast Notifications**: User-friendly feedback messages

## Components

### Main Components

- **Main.vue**: The main entry point for the Plans module
- **PlansItem.vue**: Displays individual plan items with interactive controls
- **PlansModal.vue**: Modal for creating and editing plan items
- **PlansSettings.vue**: Settings panel for managing categories, statuses, and priorities
- **ChecklistModal.vue**: Modal for managing checklists within items

### Helper Components

- **FileViewer.vue**: Displays file attachments with preview and download options
- **LoadingSpinner.vue**: Provides visual feedback during loading operations
- **Toast.vue**: Displays user feedback notifications
- **ToastContainer.vue**: Manages and displays multiple toast notifications

## Usage

### Creating a New Item

1. Click the "Add Item" button in the header
2. Fill in the required fields (Title, Category, Status, Priority)
3. Optionally add a description, due date, attachments, and assign users
4. Click "Save" to create the item

### Updating an Item

Items can be updated in several ways:

1. **Inline Updates**: Click on the status, priority, or due date fields within an item to update them directly
2. **Full Edit**: Click the edit button (pencil icon) in the item's action menu to open the edit modal
3. **Bulk Actions**: Select multiple items using checkboxes and use the bulk action dropdown

### Working with Attachments

1. In the item creation/edit modal, use the file upload area to add attachments
2. Files can be dragged and dropped or selected via file browser
3. View and download attachments from the item detail view

### Filtering and Sorting

1. Click the "Filters" button in the header to open the filter panel
2. Select categories, statuses, or priorities to filter by
3. Use the search box for text-based filtering
4. Set sorting options in the filter panel

### Settings

Click the settings icon (gear) in the header to open the settings panel where you can:
1. Create, edit, and delete categories
2. Create, edit, and delete statuses
3. Create, edit, and delete priorities

## Development

### API Routes

The module uses several API endpoints for data operations:

- `items.*` - Base CRUD operations for items
- `items.status`, `items.priority`, `items.date` - Inline update operations
- `items.attachments.*` - File attachment operations
- `items.export` - CSV export functionality
- `category.*`, `status.*`, `priority.*` - Settings management

### State Management

The module uses Vue's Composition API with the following patterns:

1. **Composables**: The `usePlans` composable provides shared state and methods
2. **Props and Emit**: Component communication via props down and events up
3. **Toast Service**: Centralized notification system

## Customization

### Themes

The dark theme is set via CSS variables in `app.css`. To customize the appearance:

1. Update the color variables in the `:root` section
2. Adjust component-specific styles in their respective files

### Layout

Toggle between grid and list views using the view toggle buttons in the header.

## Accessibility

The module includes several accessibility features:

- Keyboard navigation support
- ARIA attributes
- Focus management
- Color contrast conforming to WCAG guidelines
- Screen reader friendly structure

## Troubleshooting

### Common Issues

1. **File Upload Errors**: Check file size limits and permitted MIME types
2. **API Connection Issues**: Verify API routes and authentication status
3. **Performance**: For large datasets, use pagination or filtering to improve performance

For additional support, please refer to the API documentation or contact the development team. 